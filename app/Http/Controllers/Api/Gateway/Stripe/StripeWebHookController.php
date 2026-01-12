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
        $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
    } catch (\Exception $e) {
        Log::error('Stripe webhook signature verification failed: ' . $e->getMessage());
        return response('Invalid payload', 400);
    }

    Log::info('Stripe webhook received', [
        'type' => $event->type,
        'id' => $event->id
    ]);

    switch ($event->type) {

        // Payment succeeded (including trial $0 invoices)
        case 'invoice.payment_succeeded':
            $invoice = $event->data->object;
            $subscriptionId = $this->getSubscriptionIdFromInvoice($invoice);

            if (!$subscriptionId) break;

            $userInfo = UserInfo::where('subscription_id', $subscriptionId)->first();
            if (!$userInfo) break;

            $user = $userInfo->user;

            // Check if this is a trial (amount_paid = 0)
            if ($invoice->amount_paid == 0) {
                $userInfo->payment_status = 'trial';
            } else {
                $userInfo->payment_status = 'paid';

                // Update default payment method
                if (!empty($invoice->payment_intent)) {
                    try {
                        $paymentIntent = PaymentIntent::retrieve($invoice->payment_intent);
                        $paymentMethodId = $paymentIntent->payment_method ?? null;

                        if ($paymentMethodId) {
                            $user->updateDefaultPaymentMethod($paymentMethodId);
                            $userInfo->payment_method = $paymentMethodId;
                            Log::info("Updated default payment method for user: {$user->id}");
                        }
                    } catch (\Exception $e) {
                        Log::error("Failed to update payment method: " . $e->getMessage());
                    }
                }
            }

            $userInfo->save();
            Log::info("Invoice processed for user: {$user->id}, status: {$userInfo->payment_status}");
            break;

        // Payment failed
        case 'invoice.payment_failed':
            $invoice = $event->data->object;
            $subscriptionId = $this->getSubscriptionIdFromInvoice($invoice);

            if (!$subscriptionId) break;

            $userInfo = UserInfo::where('subscription_id', $subscriptionId)->first();
            if (!$userInfo) break;

            $userInfo->payment_status = 'unpaid';
            $userInfo->save();

            Log::warning("Subscription payment failed for user: {$userInfo->user_id}");
            break;

        // Subscription canceled or expired
        case 'customer.subscription.deleted':
            $subscription = $event->data->object;
            $userInfo = UserInfo::where('subscription_id', $subscription->id)->first();

            if ($userInfo) {
                $userInfo->payment_status = 'expired';
                $userInfo->save();
                Log::info("Subscription expired for user: {$userInfo->user_id}");
            }
            break;

        // Subscription updated (optional: trial ending, status change)
        case 'customer.subscription.updated':
            $subscription = $event->data->object;
            $userInfo = UserInfo::where('subscription_id', $subscription->id)->first();

            if ($userInfo) {
                // Update payment_status based on subscription status
                if ($subscription->status === 'active') {
                    // If active but no payment yet, it may still be trial
                    $userInfo->payment_status = $subscription->trial_end && $subscription->trial_end > time()
                        ? 'trial'
                        : 'paid';
                } elseif ($subscription->status === 'canceled' || $subscription->status === 'incomplete_expired') {
                    $userInfo->payment_status = 'expired';
                } else {
                    $userInfo->payment_status = 'unpaid';
                }

                $userInfo->save();
                Log::info("Subscription updated for user: {$userInfo->user_id}, status: {$userInfo->payment_status}");
            }
            break;

        default:
            Log::info('Unhandled Stripe event type: ' . $event->type);
            break;
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
