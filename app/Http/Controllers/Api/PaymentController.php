<?php
// app\Http\Controllers\Api\PaymentController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Stripe API key (test mode)
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a payment intent for the checkout.
     */
    public function createPaymentIntent(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'apartment' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'regex:/^[0-9]{10}$/'],
        ]);

        $user = Auth::user();
        $cart = $user->cart()->with(['items.product', 'items.specification'])->first();

        // Validate cart exists and has items
        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'error' => 'Your cart is empty.'
            ], 400);
        }

        // Calculate total (subtotal + shipping)
        $subtotal = $cart->items->sum(function ($item) {
            return $item->quantity * ($item->specification->price ?? 0);
        });
        $shipping = 300;
        $total = $subtotal + $shipping;

        // Convert to cents for Stripe (LKR cents)
        $amountInCents = (int) ($total * 100);

        // Log request details for debugging
        \Log::info('Creating payment intent', [
            'user_id' => $user->id,
            'cart_items' => $cart->items->count(),
            'total' => $total,
            'amount_cents' => $amountInCents,
            'stripe_key_set' => !empty(config('services.stripe.secret')),
        ]);

        try {
            // Create Stripe PaymentIntent (using USD for test mode)
            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => 'usd', // USD for test mode (change to 'lkr' in production if supported)
                'metadata' => [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'shipping_name' => $validated['fname'] . ' ' . $validated['lname'],
                ],
            ]);

            \Log::info('Payment intent created successfully', [
                'payment_intent_id' => $paymentIntent->id,
            ]);

            // Store shipping details in session for confirmation step
            session([
                'checkout_data' => $validated,
                'payment_intent_id' => $paymentIntent->id,
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'amount' => $total,
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            \Log::error('Stripe API error', [
                'message' => $e->getMessage(),
                'type' => get_class($e),
            ]);
            return response()->json([
                'error' => 'Stripe error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            \Log::error('Payment processing error', [
                'message' => $e->getMessage(),
                'type' => get_class($e),
            ]);
            return response()->json([
                'error' => 'Payment processing error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirm payment and create order.
     */
    public function confirmPayment(Request $request)
    {
        $validated = $request->validate([
            'payment_intent_id' => ['required', 'string'],
        ]);

        $user = Auth::user();
        $cart = $user->cart()->with(['items.product', 'items.specification'])->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'error' => 'Your cart is empty.'
            ], 400);
        }

        // Retrieve checkout data from session
        $checkoutData = session('checkout_data');
        if (!$checkoutData) {
            return response()->json([
                'error' => 'Checkout session expired. Please try again.'
            ], 400);
        }

        try {
            // Retrieve the PaymentIntent from Stripe to verify status
            $paymentIntent = PaymentIntent::retrieve($validated['payment_intent_id']);

            if ($paymentIntent->status !== 'succeeded') {
                return response()->json([
                    'error' => 'Payment not successful.'
                ], 400);
            }

            // Calculate total
            $subtotal = $cart->items->sum(function ($item) {
                return $item->quantity * ($item->specification->price ?? 0);
            });
            $shipping = 300;
            $total = $subtotal + $shipping;
            $amountInCents = (int) ($total * 100);

            // Create order and order items in a transaction
            DB::transaction(function () use ($user, $cart, $checkoutData, $paymentIntent, $amountInCents) {
                // Create order
                $order = Order::create([
                    'user_id' => $user->id,
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'amount' => $amountInCents,
                    'status' => 'completed',
                    'email' => $checkoutData['email'],
                    'fname' => $checkoutData['fname'],
                    'lname' => $checkoutData['lname'],
                    'address' => $checkoutData['address'],
                    'apartment' => $checkoutData['apartment'] ?? null,
                    'city' => $checkoutData['city'],
                    'country' => $checkoutData['country'],
                    'phone' => $checkoutData['phone'] ?? null,
                ]);

                // Create order items
                foreach ($cart->items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'specification_id' => $item->specification_id,
                        'quantity' => $item->quantity,
                        'price' => $item->specification->price ?? 0,
                    ]);
                }

                // Clear cart
                $cart->items()->delete();
            });

            // Clear session data
            session()->forget(['checkout_data', 'payment_intent_id']);

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Order processing error: ' . $e->getMessage()
            ], 500);
        }
    }
}
