<?php

namespace App\Http\Controllers\Api\Gateway\Stripe;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Mail\UserCredentialsMail;
use App\Mail\UserCredntilasMail;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable; // if using $user->createSetupIntent()

class StripeCallBackController extends Controller
{
    public $redirectFail;
    public $redirectSuccess;

    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $this->redirectFail = env("APP_URL") . "/fail";
        $this->redirectSuccess = env("APP_URL") . "/success";
    }

    // __create intent



    public function createIntent(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            // add other validations if needed
        ]);

        try {


            // Check if user exists by email
            $user = User::where('email', $request->email)->first();

            // dd($user);

            if ($user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User already exists.'
                ], 409);
            } else {
                $password = Str::random(8); // generates 8-character random string like "aB3dE7kL"

                $user = new User();
                $user->name = $metadata['name'] ?? 'User ' . Str::random(1);
                $user->slug = Str::slug($metadata['name'] ?? 'user-' . Str::random(4)) . '-' . Str::random(4);
                $user->email = $request->email;
                $user->password = Hash::make($metadata['password'] ?? $password);
                $user->otp_verified_at = now();
                $user->status = 'active';
                $user->save();
            }



            // Prepare metadata and force plan_id = 2
            $metadata = $request->only([
                'name',
                'email',
                'age',
                'bmi',
                'body_part_focus',
                'body_satisfaction',
                'celebration_plan',
                'current_body_type',
                'current_weight',
                'dream_body',
                'height',
                'target_weight',
                'trying_duration',
                'urgent_improvement',
                'price'
            ]);
            $metadata['user_id'] = $user->id;

            // Create Stripe Setup Intent
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $intent = $user->createSetupIntent([
                'payment_method_types' => ['card'],
                'metadata' => $metadata
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Setup intent created successfully',
                'data' => ['intent' => $intent],
                'plain_password' => $password // temporary, if you need it for front-end

            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error creating setup intent',
                'details' => $e->getMessage()
            ], 500);
        }
    }


    public function subscribeWithTrial(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'setup_intent_id' => 'required|string', // <-- new field from frontend
        ]);

        $user = User::findOrFail($request->user_id);
        $plan = \App\Models\Plan::find(2);

        if (!$plan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Plan not found.'
            ], 404);
        }

        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            // 1️⃣ Retrieve the SetupIntent to get the metadata
            $intent = \Stripe\SetupIntent::retrieve($request->setup_intent_id);
            $metadata = $intent->metadata ?? [];

            // 2️⃣ Ensure the user has a Stripe customer
            if (!$user->stripe_id) {
                $user->createAsStripeCustomer();
            }

            // 3️⃣ Attach the payment method to the user
            $user->updateDefaultPaymentMethod($request->payment_method);

            // 4️⃣ Create subscription with trial
            $subscription = $user->newSubscription('default', $plan->stripe_price_id)
                ->trialDays(3)
                ->create($request->payment_method);


            $userInfo = new UserInfo();
            $userInfo->user_id = $user->id;
            $userInfo->price = $plan->price;
            $userInfo->age = $metadata['age'] ?? null;
            $userInfo->bmi = $metadata['bmi'] ?? null;
            $userInfo->body_part_focus = $metadata['body_part_focus'] ?? null;
            $userInfo->body_satisfaction = $metadata['body_satisfaction'] ?? null;
            $userInfo->celebration_plan = $metadata['celebration_plan'] ?? null;
            $userInfo->current_body_type = $metadata['current_body_type'] ?? null;
            $userInfo->current_weight = $metadata['current_weight'] ?? null;
            $userInfo->dream_body = $metadata['dream_body'] ?? null;
            $userInfo->height = $metadata['height'] ?? null;
            $userInfo->target_weight = $metadata['target_weight'] ?? null;
            $userInfo->trying_duration = $metadata['trying_duration'] ?? null;
            $userInfo->urgent_improvement = $metadata['urgent_improvement'] ?? null;
            $userInfo->payment_status = 'trial';
            $userInfo->subscription_id = $subscription->stripe_id ?? null;
            $userInfo->save();

            // 6️⃣ Send user credentials email
            Mail::to($user->email)->send(new UserCredntilasMail($user->email, $request->password, route('login')));

            return response()->json([
                'status' => 'success',
                'message' => 'Subscription created with trial period!',
                'data' => $subscription
            ], 200);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            // Delete user if subscription creation fails
            try {
            } catch (\Exception $deleteEx) {
                \Log::error("Failed to delete user after subscription failure: " . $deleteEx->getMessage());
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create subscription, user deleted',
                'details' => $e->getMessage()
            ], 500);
        }
    }



    public function success(Request $request)
    {

        try {
            $request->validate([
                'token' => 'required|string',
            ]);
            // Retrieve Stripe session
            $session = \Stripe\Checkout\Session::retrieve($request->token);
            $metadata = $session->metadata ?? [];
            $email = $metadata['email'] ?? null;

            if (!$email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email not found in session metadata',
                ], 400);
            }

            // Check if user already exists
            $existingUser = User::where('email', $email)->first();
            if ($existingUser) {
                return response()->json([
                    'success' => true,
                    'message' => 'User already exists',
                ], 200);
            }

            // Create new user
            $password = Str::random(10);
            $user = new User();
            $user->name = $metadata['name'] ?? 'User ' . Str::random(4);
            $user->slug = Str::slug($metadata['name'] ?? 'user-' . Str::random(4)) . '-' . Str::random(4);
            $user->email = $email;
            $user->password = Hash::make($metadata['password'] ?? $password);
            $user->otp_verified_at = now();
            $user->status = 'active';
            $user->save();

            // Optionally send credentials email
            Mail::to($user->email)->send(new UserCredntilasMail($email, $password, route('login')));

            // Create user info
            $userInfo = new UserInfo();
            $userInfo->user_id = $user->id;
            $userInfo->age = $metadata['age'] ?? null;
            $userInfo->bmi = $metadata['bmi'] ?? null;
            $userInfo->body_part_focus = $metadata['body_part_focus'] ?? null;
            $userInfo->body_satisfaction = $metadata['body_satisfaction'] ?? null;
            $userInfo->celebration_plan = $metadata['celebration_plan'] ?? null;
            $userInfo->current_body_type = $metadata['current_body_type'] ?? null;
            $userInfo->current_weight = $metadata['current_weight'] ?? null;
            $userInfo->dream_body = $metadata['dream_body'] ?? null;
            $userInfo->height = $metadata['height'] ?? null;
            $userInfo->target_weight = $metadata['target_weight'] ?? null;
            $userInfo->trying_duration = $metadata['trying_duration'] ?? null;
            $userInfo->urgent_improvement = $metadata['urgent_improvement'] ?? null;
            $userInfo->price = $metadata['price'] ?? null;
            $userInfo->payment_status = 'trial';
            $userInfo->subscription_id = $session->subscription ?? null;
            $userInfo->save();

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => [
                    'user' => $user,
                    'user_info' => $userInfo,
                ]
            ], 201);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function failure(Request $request)
    {
        return redirect()->to($this->redirectFail);
    }
}
