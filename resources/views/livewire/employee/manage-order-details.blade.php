<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('employee.orders') }}" class="inline-flex items-center text-black hover:text-gray-700 font-bold text-xl">
            <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Order Details
        </a>

    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Left Column: Order Summary Box --}}
        <div class="w-full lg:w-1/2">
            <div class="border-2 border-black p-8 relative">
                <h2 class="text-2xl font-bold mb-6">Order Summary</h2>
                
                <div class="space-y-4 text-gray-700">
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-500">Order ID</span>
                        <span class="font-bold text-black text-lg">{{ $order->id }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="font-medium text-gray-500">Customer</span>
                        <span class="font-bold text-black">{{ $order->fname }} {{ $order->lname }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="font-medium text-gray-500">Email</span>
                        <span class="font-bold text-black">{{ $order->customer_email }}</span>
                    </div>

                    <div class="flex justify-between items-start">
                        <span class="font-medium text-gray-500">Shipping Address</span>
                        <span class="font-bold text-black text-right max-w-xs">
                            {{ $order->address }}, <br>
                            {{ $order->city }}
                        </span>
                    </div>
                </div>

                {{-- Date Display based on status --}}
                @if($order->delivery_status === 'shipped' || $order->delivery_status === 'processing')
                    <div class="mt-8 flex justify-between border-t border-gray-200 pt-4">
                        <span class="font-medium text-gray-500">Estimated Delivery Date</span>
                        <span class="font-bold text-black">
                            {{ $order->delivery_expected_at ? $order->delivery_expected_at->format('d M Y') : 'N/A' }}
                        </span>
                    </div>
                @endif
            </div>

            {{-- Ship Button if Processing --}}
            @if($order->delivery_status === 'processing' || $order->delivery_status === 'pending')
                <div class="mt-8">
                    <button wire:click="shipOrder" class="w-full bg-white border-2 border-black text-black py-3 font-bold text-lg hover:bg-gray-50 transition">
                        Ship
                    </button>
                </div>
            @endif
        </div>

        {{-- Right Column: Order Products --}}
        <div class="w-full lg:w-1/2">
            <div class="flex justify-between items-center mb-6">
                 <h2 class="text-2xl font-normal">Order Products</h2>
                 <h2 class="text-2xl font-bold">LKR {{ number_format($order->amount / 100, 2) }}</h2> {{-- Assuming calculated total --}}
            </div>
           

            <div class="space-y-6">
                @foreach($order->items as $item)
                    <div class="flex items-start space-x-4">
                        {{-- Product Image --}}
                        <div class="relative">
                            <img src="{{ $item->product->image_path ? asset($item->product->image_path) : asset('assets/images/placeholder.png') }}" 
                                 class="w-20 h-24 object-cover" alt="{{ $item->product->name }}">
                            <span class="absolute -top-2 -right-2 bg-gray-200 text-gray-700 text-xs font-bold w-6 h-6 flex items-center justify-center rounded-full">
                                {{ $item->quantity }}
                            </span>
                        </div>
                        
                        {{-- Details --}}
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900">
                                {{ $item->product->name }}
                                @if($item->specification)
                                    <span class="font-normal text-gray-500">({{ $item->specification->name }})</span>
                                @endif
                            </h4>
                        </div>

                        {{-- Price --}}
                        <div class="text-right">
                            <p class="font-medium">LKR {{ number_format($item->price, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 border-t border-gray-200 pt-4 space-y-2">
                 <div class="flex justify-between text-sm">
                     <span class="text-gray-600">Subtotal • {{ $order->items->sum('quantity') }} items</span>
                     <span class="font-medium">LKR {{ number_format(($order->amount - 300) / 100, 2) }}</span>
                 </div>
                 <div class="flex justify-between text-sm">
                     <span class="text-gray-600">Shipping <span class="text-gray-400">?</span></span>
                     <span class="font-medium">LKR 300.00</span>
                 </div>
                 <div class="flex justify-between text-xl font-bold mt-4">
                     <span>Total</span>
                     <span>LKR {{ number_format($order->amount / 100, 2) }}</span>
                 </div>
            </div>
        </div>
    </div>
</div>
