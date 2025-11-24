<?php

namespace App\Http\Controllers\Api\Gateway\Stripe;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\UserInfo;
use App\Services\StripeService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\SignatureVerificationException;
use Stripe\PaymentIntent;
use UnexpectedValueException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StripeWebHookController extends Controller
{
    protected $stripeService;
    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function intent(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return Helper::jsonResponse(false, 'Validation failed', 422, $validator->errors());
        }

        try {
            $data = $validator->validated();
            $uid = Str::uuid();

            $paymentIntent = PaymentIntent::create([
                'amount'   => $data['price'] * 100,
                'currency' => 'usd',
                'metadata' => [
                    'order_id' => $uid,
                    'user_id' => auth('api')->user()->id
                ],
            ]);

            $data = [
                'client_secret' => $paymentIntent->client_secret
            ];

            return Helper::jsonResponse(true, 'Payment intent created successfully', 200, $data);
        } catch (ApiErrorException $e) {

            return Helper::jsonResponse(false, $e->getMessage(), 500, []);
        } catch (Exception $e) {

            return Helper::jsonResponse(false, $e->getMessage(), 500, []);
        }
    }


public function webhook(Request $request)
{
    $payload = $request->getContent();
    $sig_header = $request->header('Stripe-Signature');
    $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

    try {
        $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
    } catch (\Exception $e) {
        Log::error('Stripe webhook signature verification failed: ' . $e->getMessage());
        return response('Invalid payload', 400);
    }

    Log::info('Stripe webhook received', [
        'type' => $event->type,
        'id' => $event->id
    ]);

    if ($event->type === 'invoice.payment_succeeded') {

        $invoice = $event->data->object;

        Log::info('Invoice payment succeeded', ['invoice_id' => $invoice->id]);

        $subscriptionId = $this->getSubscriptionIdFromInvoice($invoice);

        if (!$subscriptionId) {
            Log::warning("No subscription ID found for invoice", ['invoice_id' => $invoice->id]);
            return response('ok', 200);
        }

        $userInfo = UserInfo::where('subscription_id', $subscriptionId)->first();

        if (!$userInfo) {
            Log::warning("No user found for subscription", ['subscription_id' => $subscriptionId]);
            return response('ok', 200);
        }

        $user = $userInfo->user; // assuming UserInfo belongsTo User

        // Update default payment method and store in userInfo.payment_method
        if (!empty($invoice->payment_intent)) {
            try {
                $paymentIntent = \Stripe\PaymentIntent::retrieve($invoice->payment_intent);
                $paymentMethodId = $paymentIntent->payment_method ?? null;

                if ($paymentMethodId) {
                    // Update user's default payment method
                    $user->updateDefaultPaymentMethod($paymentMethodId);

                    // Store in userInfo table
                    $userInfo->payment_method = $paymentMethodId;

                    Log::info("Updated default payment method for user: {$user->id}");
                }
            } catch (\Exception $e) {
                Log::error("Failed to update payment method: " . $e->getMessage());
            }
        }

        // Update payment_status
        $userInfo->payment_status = $invoice->amount_paid == 0 ? 'trial' : 'paid';
        $userInfo->payment_method = 'stripe';

        $userInfo->save();
    }

    return response('Webhook handled', 200);
}

/**
 * Extract subscription ID from invoice
 */
private function getSubscriptionIdFromInvoice($invoice)
{
    if (!empty($invoice->subscription)) {
        return $invoice->subscription;
    }

    if (isset($invoice->parent->subscription_details->subscription)) {
        return $invoice->parent->subscription_details->subscription;
    }

    if (isset($invoice->lines->data[0]->parent->subscription_item_details->subscription)) {
        return $invoice->lines->data[0]->parent->subscription_item_details->subscription;
    }

    return null;
}



}
