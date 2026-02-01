<x-app-layout>
<div class="min-h-screen flex flex-col font-['Montserrat']">
    <!-- resources\views\checkout\show.blade.php -->
    
    <!-- Content -->
    <div class="flex flex-col lg:flex-row lg:justify-center lg:gap-12 px-4 py-8 max-w-6xl mx-auto w-full">

        <!-- Left Section (Form) -->
        <form id="checkout-form" class="w-full lg:w-2/3 bg-white p-6 border-2 border-black shadow-sm">
            @csrf

            @if (session('success'))
                <div class="mb-4 p-3 border border-green-600 bg-green-50 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->has('cart'))
                <p class="text-red-500 text-sm mb-4">{{ $errors->first('cart') }}</p>
            @endif

            <div id="error-message" class="hidden mb-4 p-3 border border-red-600 bg-red-50 text-red-800"></div>

            <!-- Shipping -->
            <h2 class="text-lg font-semibold mb-4">Shipping Address</h2>
            <div class="space-y-3 mb-6">
                <select name="country" id="country" class="w-full border px-3 py-3 border-black" required>
                    <option disabled {{ old('country') ? '' : 'selected' }}>Country</option>
                    <option value="Sri Lanka" selected>Sri Lanka</option>
                </select>

                <div class="mb-3">
                    @error('email') <p class="text-red-500 text-sm mb-1">{{ $message }}</p> @enderror
                    <label for="email" class="sr-only">Email</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        placeholder="Email"
                        value="{{ old('email', auth()->user()->email) }}"
                        class="w-full border px-3 py-3 border-black"
                        required
                    >
                </div>

                <div class="flex flex-col md:flex-row md:space-x-3 space-y-3 md:space-y-0">
                    <div class="w-full md:w-1/2">
                        @error('fname') <p class="text-red-500 text-sm mb-1">{{ $message }}</p> @enderror
                        <input
                            type="text"
                            name="fname"
                            id="fname"
                            placeholder="First name"
                            value="{{ old('fname') }}"
                            class="w-full border px-3 py-3 border-black"
                            required
                        >
                    </div>
                    <div class="w-full md:w-1/2">
                        @error('lname') <p class="text-red-500 text-sm mb-1">{{ $message }}</p> @enderror
                        <input
                            type="text"
                            name="lname"
                            id="lname"
                            placeholder="Last name"
                            value="{{ old('lname') }}"
                            class="w-full border px-3 py-3 border-black"
                            required
                        >
                    </div>
                </div>

                @error('address') <p class="text-red-500 text-sm mb-1">{{ $message }}</p> @enderror
                <input
                    type="text"
                    name="address"
                    id="address"
                    placeholder="Address"
                    value="{{ old('address') }}"
                    class="w-full border px-3 py-3 border-black"
                    required
                >

                <input
                    type="text"
                    name="apartment"
                    id="apartment"
                    placeholder="Apartment, suite, etc. (optional)"
                    value="{{ old('apartment') }}"
                    class="w-full border px-3 py-3 border-black"
                >

                @error('city') <p class="text-red-500 text-sm mb-1">{{ $message }}</p> @enderror
                <select name="city" id="city" class="w-full border px-3 py-3 border-black" required>
                    <option disabled {{ old('city') ? '' : 'selected' }}>City</option>
                    <option value="Colombo" {{ old('city') === 'Colombo' ? 'selected' : '' }}>Colombo</option>
                    <option value="Kandy" {{ old('city') === 'Kandy' ? 'selected' : '' }}>Kandy</option>
                    <option value="Galle" {{ old('city') === 'Galle' ? 'selected' : '' }}>Galle</option>
                </select>

                @error('phone') <p class="text-red-500 text-sm mb-1">{{ $message }}</p> @enderror
                <input
                    type="text"
                    name="phone"
                    id="phone"
                    placeholder="Phone (optional)"
                    value="{{ old('phone') }}"
                    class="w-full border px-3 py-3 border-black"
                >
            </div>

            <!-- Delivery -->
            <div class="border p-3 border-black mb-6 flex justify-between items-center">
                <div>
                    <p class="font-medium">Standard Delivery</p>
                    <p class="text-sm text-gray-500">Standard Delivery within 48hrs</p>
                </div>
                <p class="font-medium">LKR 300.00</p>
            </div>

            <!-- Payment -->
            <h2 class="text-lg font-semibold mb-4">Payment</h2>
            <p class="text-sm text-gray-500 mb-4">All transactions are secure and encrypted</p>

            <!-- Stripe Card Element -->
            <div id="card-element" class="w-full border px-3 py-3 border-black mb-3 bg-white"></div>
            <div id="card-errors" role="alert" class="text-red-500 text-sm mb-3"></div>

            <!-- Pay Button -->
            <button type="submit" id="submit-button" class="w-full bg-black text-white py-3 font-medium">
                <span id="button-text">Pay now</span>
                <span id="spinner" class="hidden">Processing...</span>
            </button>

            <a href="{{ route('products.index') }}" class="flex items-center justify-center mt-6">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                    <path fill="#69a985" d="M169.4 297.4C156.9 309.9 156.9 330.2 169.4 342.7L361.4 534.7C373.9 547.2 394.2 547.2 406.7 534.7C419.2 522.2 419.2 501.9 406.7 489.4L237.3 320L406.6 150.6C419.1 138.1 419.1 117.8 406.6 105.3C394.1 92.8 373.8 92.8 361.3 105.3L169.3 297.3z"/>
                </svg>
                <p class="text-[#69A985] text-sm hover:underline">Return to shopping</p>
            </a>
        </form>

        <!-- Right Section (Order Summary) -->
        <div class="w-full lg:w-1/3 mt-8 lg:mt-0">
            <div class="bg-white p-6 border-2 border-black shadow-sm">
                <h2 class="text-lg font-semibold mb-4">Order Summary</h2>

                <div class="space-y-4">
                    @forelse($cartItems as $item)
                        @php
                            $lineTotal = $item->quantity * ($item->specification->price ?? 0);
                        @endphp

                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3 relative">
                                <div class="relative">
                                    <img src="{{ asset($item->product->image_path) }}" class="h-12" alt="{{ $item->product->name }}">
                                    <span class="absolute -top-2 -right-2 bg-black text-white text-xs rounded-full px-2 py-0.5">
                                        {{ $item->quantity }}
                                    </span>
                                </div>
                                <div class="flex flex-col">
                                    <p class="text-sm">{{ $item->product->name }}</p>
                                    <p class="text-[12px]">({{ $item->specification->name ?? 'Default' }})</p>
                                </div>
                            </div>
                            <p class="text-sm font-medium">LKR {{ number_format($lineTotal, 2) }}</p>
                        </div>
                    @empty
                        <div class="text-gray-500 text-sm">
                            Your cart is empty.
                        </div>
                    @endforelse
                </div>

                <!-- Totals -->
                <div class="mt-6 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <div class="flex gap-1">
                            <p>Subtotal</p>
                            <p>·</p>
                            <p>{{ $cartItems->sum('quantity') }} items</p>
                        </div>
                        <p>LKR {{ number_format($subtotal, 2) }}</p>
                    </div>
                    <div class="flex justify-between">
                        <p>Shipping</p>
                        <p>LKR {{ number_format($shipping, 2) }}</p>
                    </div>
                    <div class="flex justify-between font-semibold text-base">
                        <p>Total</p>
                        <p>LKR {{ number_format($total, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    console.log('Initializing Stripe checkout...');
    
    // Initialize Stripe with publishable key (test mode)
    const stripeKey = '{{ config('services.stripe.key') }}';
    console.log('Stripe key:', stripeKey ? 'Loaded (' + stripeKey.substring(0, 10) + '...)' : 'NOT FOUND');
    
    const stripe = Stripe(stripeKey);
    const elements = stripe.elements();
    
    // Create card element
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                fontFamily: 'Montserrat, sans-serif',
                color: '#000',
                '::placeholder': {
                    color: '#6b7280',
                },
            },
        },
    });
    cardElement.mount('#card-element');
    console.log('Card element mounted');

    // Handle real-time validation errors
    cardElement.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
            console.log('Card validation error:', event.error.message);
        } else {
            displayError.textContent = '';
        }
    });

    // Handle form submission
    const form = document.getElementById('checkout-form');
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');
    const errorMessage = document.getElementById('error-message');

    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        console.log('Form submitted, starting payment flow...');

        // Disable button and show spinner
        submitButton.disabled = true;
        buttonText.classList.add('hidden');
        spinner.classList.remove('hidden');
        errorMessage.classList.add('hidden');

        // Get form data
        const formData = {
            email: document.getElementById('email').value,
            fname: document.getElementById('fname').value,
            lname: document.getElementById('lname').value,
            address: document.getElementById('address').value,
            apartment: document.getElementById('apartment').value,
            city: document.getElementById('city').value,
            country: document.getElementById('country').value,
            phone: document.getElementById('phone').value,
        };

        console.log('Form data:', formData);

        try {
            // Step 1: Create payment intent
            console.log('Step 1: Creating payment intent...');
            const intentResponse = await fetch('/api/payment/create-intent', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                },
                credentials: 'same-origin',
                body: JSON.stringify(formData),
            });

            console.log('Payment intent response status:', intentResponse.status);
            const intentData = await intentResponse.json();
            console.log('Payment intent data:', intentData);

            if (!intentResponse.ok) {
                throw new Error(intentData.error || 'Failed to create payment intent');
            }

            // Step 2: Confirm card payment with Stripe
            console.log('Step 2: Confirming card payment with Stripe...');
            const { error, paymentIntent } = await stripe.confirmCardPayment(intentData.clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: formData.fname + ' ' + formData.lname,
                        email: formData.email,
                    },
                },
            });

            if (error) {
                console.error('Stripe payment error:', error);
                throw new Error(error.message);
            }

            console.log('Payment intent status:', paymentIntent.status);

            if (paymentIntent.status === 'succeeded') {
                console.log('Payment succeeded! Payment Intent ID:', paymentIntent.id);
                
                // Step 3: Confirm payment on backend and create order
                console.log('Step 3: Confirming payment on backend...');
                const confirmResponse = await fetch('/api/payment/confirm', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        payment_intent_id: paymentIntent.id,
                    }),
                });

                console.log('Confirm response status:', confirmResponse.status);
                const confirmData = await confirmResponse.json();
                console.log('Confirm data:', confirmData);

                if (!confirmResponse.ok) {
                    throw new Error(confirmData.error || 'Failed to process order');
                }

                // Success! Redirect to landing page
                console.log('Order created successfully! Redirecting...');
                window.location.href = '{{ route('my-orders.index') }}';
            } else {
                throw new Error('Payment status: ' + paymentIntent.status);
            }
        } catch (err) {
            // Show error message
            console.error('Payment error:', err);
            errorMessage.textContent = err.message;
            errorMessage.classList.remove('hidden');

            // Scroll to top to show error
            window.scrollTo({ top: 0, behavior: 'smooth' });

            // Re-enable button
            submitButton.disabled = false;
            buttonText.classList.remove('hidden');
            spinner.classList.add('hidden');
        }
    });
</script>
</div>
</x-app-layout>
