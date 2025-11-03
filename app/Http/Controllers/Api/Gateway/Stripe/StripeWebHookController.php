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

        // Only process invoice payment succeeded events
        if ($event->type === 'invoice.payment_succeeded') {
            $invoice = $event->data->object;

            Log::info('Invoice payment succeeded received: ', ['invoice_id' => $invoice->id]);

            // Only mark as paid if amount is greater than 0
            if ($invoice->amount_paid > 0) {
                $subscriptionId = $this->getSubscriptionIdFromInvoice($invoice);

                Log::info('Subscription ID received in webhook: ' . ($subscriptionId ?? 'none'));

                if ($subscriptionId) {
                    $userInfo = UserInfo::where('subscription_id', $subscriptionId)->first();

                    if ($userInfo) {
                        $userInfo->payment_status = 'paid';
                        $userInfo->save();
                        Log::info("Payment marked as PAID for subscription: {$subscriptionId}");
                    } else {
                        Log::warning("No user info found for subscription: {$subscriptionId}");
                    }
                } else {
                    Log::warning("No subscription ID found in invoice object", [
                        'invoice_id' => $invoice->id,
                        'billing_reason' => $invoice->billing_reason ?? 'unknown'
                    ]);
                }
            } else {
                Log::info("Invoice amount is 0, skipping marking as PAID", [
                    'invoice_id' => $invoice->id,
                    'amount_paid' => $invoice->amount_paid
                ]);
            }
        } else {
            // Log other event types for debugging but don't process them
            Log::info('Other Stripe event type received (not processed)', [
                'type' => $event->type,
                'id' => $event->id
            ]);
        }

        return response('Webhook handled', 200);
    }

    /**
     * Extract subscription ID from invoice using multiple fallback methods
     */
    private function getSubscriptionIdFromInvoice($invoice)
    {
        $subscriptionId = null;

        // Method 1: Check if invoice has a direct subscription reference
        if (isset($invoice->subscription) && !empty($invoice->subscription)) {
            $subscriptionId = $invoice->subscription;
        }
        // Method 2: Try to get from invoice parent (subscription_details)
        else if (isset($invoice->parent) &&
                 isset($invoice->parent->type) &&
                 $invoice->parent->type === 'subscription_details' &&
                 isset($invoice->parent->subscription_details->subscription)) {
            $subscriptionId = $invoice->parent->subscription_details->subscription;
        }
        // Method 3: Try to get from line items (subscription_item_details)
        else if (isset($invoice->lines) &&
                 isset($invoice->lines->data) &&
                 count($invoice->lines->data) > 0) {

            $lineItem = $invoice->lines->data[0];

            if (isset($lineItem->parent) &&
                isset($lineItem->parent->type) &&
                $lineItem->parent->type === 'subscription_item_details' &&
                isset($lineItem->parent->subscription_item_details->subscription)) {
                $subscriptionId = $lineItem->parent->subscription_item_details->subscription;
            }
        }

        return $subscriptionId;
    }

}
