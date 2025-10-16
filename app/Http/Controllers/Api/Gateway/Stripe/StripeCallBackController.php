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
            'price' => 'required|numeric',
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

            $successUrl = route('api.payment.stripe.success') . '?token={CHECKOUT_SESSION_ID}';
            $cancelUrl = route('api.payment.stripe.cancel') . '?token={CHECKOUT_SESSION_ID}';

            // Prepare metadata
            $metadata = [
                'price' => $data['price'], // <-- add price here
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

            // Prepare ordered description
            $descriptionLines = [];
            $counter = 1;
            foreach ($metadata as $key => $value) {
                if ($value !== '') {
                    $label = ucfirst(str_replace('_', ' ', $key));
                    $descriptionLines[] = "{$counter}. {$label}: {$value}";
                    $counter++;
                }
            }
            $description = implode("\n", $descriptionLines); // each field in a new line

            // Create Stripe Checkout session
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Payment Amount: $' . $data['price'],
                            'description' => $description,
                        ],
                        'unit_amount' => $data['price'] * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'metadata' => $metadata,
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
            ]);

            return Helper::jsonResponse(true, 'Checkout session created successfully', 200, [
                'checkout_url' => $session->url,
            ]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
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

                $email = 'user' . rand(1000, 9999) . '@example.com';
                $password = Str::random(10);
                // 1. Create a new user using save()
                $user = new User();
                $user->name = $metadata['name'] ?? 'User ' . Str::random(4);
                $user->slug = Str::slug($metadata['name'] ?? 'user-' . Str::random(4)) . '-' . Str::random(4);
                $user->email = $metadata['email'] ?? $email;
                $user->password = Hash::make($metadata['password'] ?? $password);
                $user->status = 'active';
                $user->save();
                 // <-- saves user to DB
                Mail::to($user->email)->send(new UserCredntilasMail($email, $password, route('login')));

                // 2. Create user_info record linked to this user using save()
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
                $userInfo->save();

                return redirect()->to($this->redirectSuccess);
            }

            // Payment failed or canceled
            return redirect()->to($this->redirectFail);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            \Log::error($e->getMessage());
            return redirect()->to($this->redirectFail);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return redirect()->to($this->redirectFail);
        }
    }

    public function failure(Request $request)
    {
        return redirect()->to($this->redirectFail);
    }
}
