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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:plans,id', // 👈 required
            'email' => 'required|email',
            'age' => 'nullable|integer',
            'bmi' => 'nullable|string',
            'body_part_focus' => 'nullable|string',
            'body_satisfaction' => 'nullable|string',
            'celebration_plan' => 'nullable|string',
            'current_body_type' => 'nullable|string',
            'current_weight' => 'nullable|numeric',
            'dream_body' => 'nullable|string',
            'height' => 'nullable|numeric',
            'target_weight' => 'nullable|numeric',
            'trying_duration' => 'nullable|string',
            'urgent_improvement' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return Helper::jsonResponse(false, 'Validation failed', 422, $validator->errors());
        }

        try {
            $data = $validator->validated();

            // ✅ Fetch the plan from DB
            $plan = \App\Models\Plan::findOrFail($data['plan_id']);

            // Check if email exists
            $existingUser = \App\Models\User::where('email', $data['email'])->first();
            if ($existingUser) {
                return Helper::jsonResponse(false, 'Email already exists', 409);
            }

            $successUrl = route('api.payment.stripe.success') . '?token={CHECKOUT_SESSION_ID}';
            $cancelUrl = route('api.payment.stripe.cancel') . '?token={CHECKOUT_SESSION_ID}';

            // ✅ Attach plan info in metadata
            $metadata = [
                'plan_id' => $plan->id,
                'plan_name' => $plan->name,
                'price' => $plan->price,
                'email' => $data['email'],
            ];

            foreach (
                $request->only([
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
                ]) as $key => $value
            ) {
                $metadata[$key] = $value ?? '';
            }

            // ✅ Create Stripe Checkout session for subscription
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $plan->stripe_price_id, // 👈 dynamic from DB
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'subscription_data' => [
                ],
                'metadata' => $metadata, // ✅ attach here for subscription

                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
            ]);

            return Helper::jsonResponse(true, 'Checkout session created successfully', 200, [
                'checkout_url' => $session->url,
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return Helper::jsonResponse(false, 'Something went wrong', 500);
        }
    }






    public function success(Request $request)
    {
        $validatedData = $request->validate([
            'token' => ['required', 'string'],
        ]);

        try {
            // Retrieve Stripe session
            $session = \Stripe\Checkout\Session::retrieve($validatedData['token']);



            if ($session->payment_status === 'paid') {

                // Collect metadata safely
                $metadata = $session->metadata ?? [];

                $email = $metadata['email'];
                $password = Str::random(10);
                // 1. Create a new user using save()
                $user = new User();
                $user->name = $metadata['name'] ?? 'User ' . Str::random(4);
                $user->slug = Str::slug($metadata['name'] ?? 'user-' . Str::random(4)) . '-' . Str::random(4);
                $user->email = $metadata['email'] ?? $email;
                $user->password = Hash::make($metadata['password'] ?? $password);
                $user->otp_verified_at =  now();
                $user->status = 'active';
                $user->save();


                Mail::to($user->email)->send(new UserCredntilasMail($email, $password, route('login')));


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
                $userInfo->payment_status = 'trail';
                $userInfo->subscription_id = $session->subscription; // 👈 save Stripe subscription ID

                $userInfo->save();

                return response()->json([
                    'success' => 'user created'
                ]);
            }

            // Payment failed or canceled
            return redirect()->to($this->redirectFail);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error($e->getMessage());
            return redirect()->to($this->redirectFail);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->to($this->redirectFail);
        }
    }

    public function failure(Request $request)
    {
        return redirect()->to($this->redirectFail);
    }
}
