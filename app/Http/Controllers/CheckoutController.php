<?php
// app\Http\Controllers\CheckoutController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $cart = $user->cart()->with(['items.product', 'items.specification'])->first();
        $cartItems = $cart ? $cart->items : collect();

        // Redirect to products if cart is empty
        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('products.index')
                ->with('error', 'Your cart is empty. Please add products before proceeding to checkout.');
        }

        $subtotal = $cartItems->sum(function ($item) {
            $price = $item->specification->price ?? 0;
            return $item->quantity * $price;
        });

        $shipping = 300;
        $total = $subtotal + $shipping;

        return view('checkout.show', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
        ]);
    }

    public function process(Request $request)
    {
        // Same validation rules as the legacy file (kept intentionally)
        $validated = $request->validate(
            [
                'email'      => ['required', 'email'],
                'fname'      => ['required', 'string', 'max:255'],
                'lname'      => ['required', 'string', 'max:255'],
                'address'    => ['required', 'string', 'max:500'],
                'city'       => ['required', 'string', 'max:100'],
                'phone'      => ['nullable', 'regex:/^[0-9]{10}$/'],

                'cardNumber' => ['required', 'regex:/^[0-9]{16}$/'],
                'expDate'    => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
                'cvv'        => ['required', 'regex:/^[0-9]{3}$/'],
                'cardName'   => ['required', 'string', 'max:255'],
            ],
            [
                'email.email' => 'Please enter a valid email address.',
                'fname.required' => 'First name is required.',
                'lname.required' => 'Last name is required.',
                'address.required' => 'Address is required.',
                'city.required' => 'Please select a city.',
                'phone.regex' => 'Phone number must be 10 digits.',
                'cardNumber.regex' => 'Card number must be 16 digits.',
                'expDate.regex' => 'Expiration date must be in MM/YY format.',
                'cvv.regex' => 'Security code must be 3 digits.',
                'cardName.required' => 'Name on card is required.',
            ]
        );

        // Safety: ensure cart exists and has items
        $user = Auth::user();
        $cart = $user->cart()->with('items.specification')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()
                ->route('checkout.show')
                ->withErrors(['cart' => 'Your cart is empty.'])
                ->withInput();
        }

        // Calculate Total
        $subtotal = $cart->items->sum(function ($item) {
             return $item->quantity * ($item->specification->price ?? 0);
        });
        $total = $subtotal + 300; // Shipping

        // Delivery Date will be calculated when the order is shipped by the employee
        // $deliveryDate = now()->addDay()->setTime(rand(8, 20), rand(0, 59));

        // Create Order
        $order = \App\Models\Order::create([
            'user_id' => $user->id,
            'stripe_payment_intent_id' => 'legacy_sim_' . uniqid(), // Simulation
            'amount' => $total,
            'payment_status' => 'completed',
            'delivery_status' => 'processing',
            // delivery_expected_at will be set when shipped
            'fname' => $validated['fname'],
            'lname' => $validated['lname'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'country' => 'Sri Lanka', // Defaulting for now
            'phone' => $validated['phone'],
        ]);

        // Create Order Items
        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->specification->price ?? 0,
            ]);
        }

        // Clear Cart
        $cart->items()->delete();

        // Use Jetstream banner
        session()->flash('flash.banner', 'Order Placed! Your order is now being processed.');
        session()->flash('flash.bannerStyle', 'success');

        return redirect()->route('my-orders.index');
    }
}
