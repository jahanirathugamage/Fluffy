<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\Order;

class StripeWebhookController extends Controller
{
    /**
     * Handle Stripe Webhook events.
     */
    public function handle(Request $request)
    {
        // 1. Verify Signature
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            Log::error('Stripe Webhook Error: Invalid Payload');
            return response()->json(['error' => 'Invalid Payload'], 400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            Log::error('Stripe Webhook Error: Invalid Signature');
            return response()->json(['error' => 'Invalid Signature'], 400);
        }

        // 2. Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handlePaymentSucceeded($paymentIntent);
                break;

            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->handlePaymentFailed($paymentIntent);
                break;
            
            default:
                // Unexpected event type
                Log::info('Stripe Webhook: Received unknown event type ' . $event->type);
        }

        return response()->json(['status' => 'success']);
    }

    protected function handlePaymentSucceeded($paymentIntent)
    {
        Log::info('Stripe Payment Succeeded', ['id' => $paymentIntent->id]);

        // Find the order by payment intent ID
        $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();

        if ($order) {
            // Update status to completed/paid if not already
            if ($order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'delivery_status' => $order->delivery_status === 'pending' ? 'processing' : $order->delivery_status,
                ]);
                Log::info('Order status updated to paid via webhook', ['order_id' => $order->id]);
            }
        } else {
            // Order not found? In a real scenario, we might create it here from metadata
            // if the client-side conformation failed. 
            // For now, we just log it as a warning or potentially implement creation logic.
            Log::warning('Order not found for successful payment intent', ['id' => $paymentIntent->id]);
        }
    }

    protected function handlePaymentFailed($paymentIntent)
    {
        Log::info('Stripe Payment Failed', ['id' => $paymentIntent->id]);
        
        $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();

        if ($order) {
            $order->update(['payment_status' => 'failed']);
            Log::info('Order status updated to failed via webhook', ['order_id' => $order->id]);
        }
    }
}
