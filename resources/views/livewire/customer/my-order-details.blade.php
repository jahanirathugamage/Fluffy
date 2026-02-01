<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 font-['Montserrat']">
    
    {{-- Top Navigation --}}
    <div class="mb-6 flex items-center">
        <a href="{{ route('my-orders.index') }}" class="mr-4 hover:text-gray-600 transition">
           <svg class="h-8 w-8 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
               <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
           </svg>
        </a>
    </div>

    <div class="flex flex-col lg:flex-row lg:justify-between lg:gap-20">
        
        {{-- Left Column: Status & Summary --}}
        <div class="w-full lg:w-[400px] flex flex-col items-center lg:items-center">
            
            {{-- Status Image & Text --}}
            <div class="mb-10 text-center">
                <div class="relative w-48 h-48 mx-auto mb-6">
                     @if($order->delivery_status === 'processing' || $order->delivery_status === 'pending')
                        <img src="{{ asset('assets/images/preparation.png') }}" class="w-full h-full object-contain" alt="Processing">
                     @elseif($order->delivery_status === 'shipped')
                        <img src="{{ asset('assets/images/Shipped.png') }}" class="w-full h-full object-contain" alt="Shipped">
                     @elseif($order->delivery_status === 'delivered')
                        <img src="{{ asset('assets/images/Delivered.png') }}" class="w-full h-full object-contain" alt="Delivered">
                     @endif
                </div>
                
                <h2 class="text-3xl font-bold text-black mb-2">Order Status</h2>
                <p class="text-lg font-medium text-black">
                    @if($order->delivery_status === 'processing' || $order->delivery_status === 'pending')
                        Your order is being prepared
                    @elseif($order->delivery_status === 'shipped')
                        Your package is on the way
                    @elseif($order->delivery_status === 'delivered')
                        Order Delivered
                    @endif
                </p>
                
                {{-- Customer Action: Confirm Receipt --}}
                @if($order->delivery_status === 'shipped' && now()->greaterThanOrEqualTo($order->delivery_expected_at))
                    <div class="mt-4">
                        <button wire:click="confirmOrder" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2 px-6 rounded-none transition">
                            Order Received
                        </button>
                    </div>
                @endif
            </div>

            {{-- Order Summary Box --}}
            <div class="w-full border-2 border-black p-6 bg-white shadow-sm">
                <h3 class="text-xl font-bold mb-6 text-black">Order Summary</h3>
                
                <div class="flex justify-between mb-4">
                    <span class="text-gray-500 font-medium">Order ID</span>
                    <span class="font-medium">{{ $order->id }}</span>
                </div>

                <div class="flex justify-between items-start mb-4">
                    <span class="text-gray-500 font-medium">Shipping Address</span>
                    <span class="font-medium text-right max-w-[150px] leading-tight text-black">
                        {{ $order->address }},<br>{{ $order->city }}
                    </span>
                </div>
                
                 @if($order->delivery_status === 'shipped' || $order->delivery_status === 'processing' || $order->delivery_status === 'pending')
                    <div class="flex justify-between items-start">
                        <span class="text-gray-500 font-medium">Estimated Delivery</span>
                        <span class="font-medium text-right text-black">
                             {{ $order->delivery_expected_at ? $order->delivery_expected_at->format('d M Y') : 'Pending' }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right Column: Order Products --}}
        <div class="w-full mt-10 lg:mt-0 flex-1">
             {{-- Mobile Accordion / Desktop Header --}}
             <div class="flex justify-between items-center mb-8">
                 <h2 class="text-2xl font-normal text-black">Order Products</h2>
                 {{-- We can add a collapse toggle here for mobile if strictly needed, but list view is usually better --}}
             </div>

            <div class="space-y-8">
                @foreach($order->items as $item)
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4">
                            {{-- Product Image with Badge --}}
                            <div class="relative">
                                <img src="{{ $item->product->image_path ? asset($item->product->image_path) : asset('assets/images/placeholder.png') }}" 
                                     class="w-16 h-20 object-contain rounded-sm" alt="Product">
                                <span class="absolute -top-2 -right-2 bg-gray-200 text-black text-xs font-bold w-6 h-6 flex items-center justify-center rounded-full">
                                    {{ $item->quantity }}
                                </span>
                            </div>
                            
                            {{-- Details --}}
                            <div class="pt-1">
                                <h4 class="font-medium text-black text-base">
                                    {{ $item->product->name }}
                                    @if($item->specification)
                                        <span class="font-normal text-gray-500">({{ $item->specification->name }})</span>
                                    @endif
                                </h4>
                            </div>
                        </div>
                        
                        {{-- Price --}}
                        <div class="text-right pt-1">
                            <p class="font-medium text-black">LKR {{ number_format($item->price, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Totals Section --}}
            <div class="mt-12 pt-4 border-t border-transparent space-y-3">
                <div class="flex justify-between text-sm text-black">
                    <span>Subtotal • {{ $order->items->sum('quantity') }} items</span>
                    <span class="font-bold">LKR {{ number_format(($order->amount - 300) / 100, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm text-black">
                    <span class="flex items-center gap-1">Shipping <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg></span>
                    <span class="font-bold">LKR 300.00</span>
                </div>
                <div class="flex justify-between text-xl font-bold text-black mt-6 pt-4">
                    <span>Total</span>
                    <span>LKR {{ number_format($order->amount / 100, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
