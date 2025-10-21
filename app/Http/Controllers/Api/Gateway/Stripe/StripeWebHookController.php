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
        return response('Invalid payload', 400);
    }

    if ($event->type === 'invoice.payment_succeeded') {
        $invoice = $event->data->object;
        $subscriptionId = $invoice->subscription; // 👈 Stripe subscription ID

        $userInfo = UserInfo::where('subscription_id', $subscriptionId)->first();
        if ($userInfo) {
            $userInfo->payment_status = 'paid';
            $userInfo->save();
            Log::info("Payment marked as PAID for user_id: {$userInfo->user_id}");
        } else {
            Log::warning("No user info found for subscription: {$subscriptionId}");
        }
    }

    return response('Webhook handled', 200);
}

}
