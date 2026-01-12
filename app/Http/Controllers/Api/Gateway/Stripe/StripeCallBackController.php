<?php

namespace App\Http\Controllers\Api\Gateway\Stripe;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Mail\UserCredentialsMail;
use App\Mail\UserCredntilasMail;
use App\Models\Plan;
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
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\SetupIntent;

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

            $user = User::where('email', $request->email)->first();

            if ($user) {
                UserInfo::where('user_id', $user->id)->delete();
            } else {
                $user = new User();
            }

            $password = Str::random(8);

            // ✅ Update or create user info safely
            $user->name = $metadata['name'] ?? 'User ' . Str::random(11);
            $user->slug = Str::slug($metadata['name'] ?? 'user-' . Str::random(4)) . '-' . Str::random(4);
            $user->email = $request->email;
            $user->password = Hash::make($metadata['password'] ?? $password);
            $user->otp_verified_at = now();
            $user->status = 'active';
            $user->save();




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
        $plan = \App\Models\Plan::find(1);

        if (!$plan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Plan not found.'
            ], 404);
        }

        // Initialize userInfo variable

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


            $subscription = $user->subscription('default');

            if ($subscription && $subscription->valid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have an active subscription.'
                ], 400);
            }

            if ($subscription && $subscription->cancelled() && ! $subscription->ended()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have cancelled your plan. You can resubscribe after the current period ends.'
                ], 400);
            }

            $subscription = $user->newSubscription('default', $plan->stripe_price_id)
                ->trialDays(3)
                ->create($request->payment_method);


            $userInfo = UserInfo::where('user_id', $user->id)->first();

            if (!$userInfo) {
                $userInfo = new UserInfo();
                $userInfo->user_id = $user->id;
                $userInfo->subscription_id = $subscription->stripe_id ?? null;
            } else {
                $userInfo->subscription_id = $userInfo->subscription_id ?? $subscription->stripe_id ?? null;
            }
            $age = $metadata['age'] ?? null;
            $userInfo->age = $age ? now()->subYears((int)$age)->toDateString() : null;
            // Set or update other fields (same for both cases)
            $userInfo->price = $plan->price;
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

            // Save the record
            $userInfo->save();




            Mail::to($user->email)->send(new UserCredntilasMail($user->email, $request->password, route('login')));

            return response()->json([
                'status' => 'success',
                'message' => 'Subscription created with trial period!',
                'data' => $subscription
            ], 200);
        } catch (\Exception $e) {
            \Log::error("Subscription creation failed: " . $e->getMessage());



            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create subscription, user and info deleted',
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


    public function createIntent2(Request $request)
    {
        $request->validate([
            'plan_id' => 'required',
        ]);

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $user = auth('api')->user();
            $plan = \App\Models\Plan::findOrFail($request->plan_id);

            $amount = $plan->price;

            // 1️⃣ Ensure Stripe Customer Exists
            if ($user->stripe_id) {

                try {
                    // Try retrieving Stripe customer
                    \Stripe\Customer::retrieve($user->stripe_id);
                } catch (\Exception $e) {
                    // Customer does not exist → Create new one
                    $customer = \Stripe\Customer::create([
                        'email' => $user->email,
                        'name'  => $user->name,
                    ]);

                    $user->stripe_id = $customer->id;
                    $user->save();
                }
            } else {
                // No customer ID saved → create fresh customer
                $customer = \Stripe\Customer::create([
                    'email' => $user->email,
                    'name'  => $user->name,
                ]);

                $user->stripe_id = $customer->id;
                $user->save();
            }

            // 2️⃣ Create PaymentIntent
            $intent = \Stripe\PaymentIntent::create([
                'amount'               => $amount * 100, // convert to cents
                'currency'             => 'usd',
                'customer'             => $user->stripe_id,
                'payment_method_types' => ['card'],
                'description'          => 'Initial subscription payment',

                'metadata' => [
                    'plan_id'       => $plan->id,
                    'first_payment' => true,
                ],

                // Automatically allow future subscription billing
                'setup_future_usage' => 'off_session',
            ]);

            // 3️⃣ Return client secret for mobile app
            return response()->json([
                'client_secret'     => $intent->client_secret,
                'payment_intent_id' => $intent->id,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }


    public function createSubscription(Request $request)
    {
        $request->validate([
            'plan_id'           => 'required',
            'payment_intent_id' => 'required',
        ]);

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $user = auth('api')->user();
        $plan = \App\Models\Plan::findOrFail($request->plan_id);

        try {

            // PaymentIntent retrieve
            $intent = \Stripe\PaymentIntent::retrieve($request->payment_intent_id);
            // dd($intent);

            $paymentMethodId = $intent->payment_method;

            // 2️⃣ Attach Payment Method to Customer
            \Stripe\PaymentMethod::retrieve($paymentMethodId)
                ->attach(['customer' => $user->stripe_id]);

            \Stripe\Customer::update($user->stripe_id, [
                'invoice_settings' => [
                    'default_payment_method' => $paymentMethodId,
                ],
            ]);

            // 4️⃣ Create subscription with TRIAL
            $subscription = \Stripe\Subscription::create([
                'customer'               => $user->stripe_id,
                'items'                  => [
                    ['price' => $plan->stripe_price_id],
                ],
                'default_payment_method' => $paymentMethodId,

            ]);

            $user_inf = Userinfo::where('user_id', $user->id)->first();
            $user_inf->subscription_id = $subscription->id;

            $user_inf->save();

            return response()->json([
                'success'      => true,
                'subscription' => $subscription->status,
                'trial_ends'   => $subscription->trial_end,
            ]);
        } catch (\Exception $e) {
            \Log::error("Subscription creation failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create subscription',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
    public function subscriptions(Request $request)
    {
        // Validate request
        $request->validate([
            'email' => 'required|email',
            'payment_method' => 'required|string',
            'setup_intent_id' => 'required|string',
        ]);

        // Find the user
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        // Check existing subscription
        $subscription = $user->subscription('default');

        if ($subscription && $subscription->valid()) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an active subscription.'
            ], 400);
        }

        if ($subscription && $subscription->cancelled() && ! $subscription->ended()) {
            return response()->json([
                'success' => false,
                'message' => 'You have cancelled your plan. You can resubscribe after the current period ends.'
            ], 400);
        }

        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $intent = \Stripe\SetupIntent::retrieve($request->setup_intent_id);
            $metadata = $intent->metadata ?? [];

            $planId = $metadata->plan_id ?? $request->plan_id ?? null;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid SetupIntent: ' . $e->getMessage(),
            ], 400);
        }

        // Find the plan
        $plan = Plan::find($planId);
        if (!$plan) {
            return response()->json([
                'success' => false,
                'message' => 'Plan not found',
            ], 404);
        }
        if (!$user->stripe_id) {
            $user->createAsStripeCustomer();
        }

        // ✅ Attach the payment method to the customer and set it as default
        $user->updateDefaultPaymentMethod($request->payment_method);
        try {
            $subscription = $user->newSubscription('default', $plan->stripe_price_id)
                ->trialDays(3)
                ->create($request->payment_method);

            $userInfo = UserInfo::firstOrNew(['user_id' => $user->id]);
            $userInfo->subscription_id = $subscription->stripe_id;
            $userInfo->price =  $plan->price;

            $userInfo->save();

            return response()->json([
                'success' => true,
                'message' => 'Subscription created successfully',
                'subscription' => $subscription,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
