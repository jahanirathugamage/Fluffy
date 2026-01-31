<div class="font-['Montserrat']">
    <!-- resources/views/livewire/cart/cart-drawer.blade.php -->
    {{-- Cart Overlay --}}
    <div
        class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity {{ $isOpen ? 'opacity-100' : 'opacity-0 pointer-events-none' }}"
        wire:click="closeCart"
    ></div>

    {{-- Cart Sidebar --}}
    <div
        class="fixed top-0 right-0 w-[380px] md:w-[520px] h-full bg-white transition-transform duration-300 z-50 shadow-lg flex flex-col font-[Montserrat] overflow-x-hidden
        {{ $isOpen ? 'translate-x-0' : 'translate-x-full' }}"
    >
        <div class="px-6 md:px-8 py-6 flex flex-col h-full">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl md:text-2xl font-bold">Your Cart</h2>
                <button wire:click="closeCart">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-6 h-6 fill-black">
                        <path d="M183.1 137.4C170.6 124.9 150.3 124.9 137.8 137.4C125.3 149.9 125.3 170.2 137.8 182.7L275.2 320L137.9 457.4C125.4 469.9 125.4 490.2 137.9 502.7C150.4 515.2 170.7 515.2 183.2 502.7L320.5 365.3L457.9 502.6C470.4 515.1 490.7 515.1 503.2 502.6C515.7 490.1 515.7 469.8 503.2 457.3L365.8 320L503.1 182.6C515.6 170.1 515.6 149.8 503.1 137.3C490.6 124.8 470.3 124.8 457.8 137.3L320.5 274.7L183.1 137.4z"/>
                    </svg>
                </button>
            </div>

            {{-- Scroll Area --}}
            <div class="flex-1 overflow-y-auto overflow-x-hidden pr-2">
                {{-- Column Headers --}}
                <div class="flex justify-between text-gray-500 text-xs font-bold mb-4 tracking-wider">
                    <span>PRODUCT</span>
                    <span>TOTAL</span>
                </div>

                {{-- Cart Items --}}
                <div class="space-y-6">
                    @forelse($this->cartItems as $item)
                        <div class="flex gap-4">
                            {{-- Image --}}
                            <div class="w-16 h-16 flex-shrink-0">
                                <img src="{{ asset($item->product->image_path) }}" alt="{{ $item->product->name }}" class="w-full h-full object-contain">
                            </div>

                            {{-- Details --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start gap-3">
                                    <div class="min-w-0">
                                        <h3 class="font-medium text-sm leading-tight text-gray-900 truncate">
                                            {{ $item->product->name }}
                                        </h3>
                                        <p class="text-xs text-gray-500 mt-1">
                                            ({{ $item->specification->name }})
                                        </p>
                                    </div>

                                    {{-- Delete Icon --}}
                                    <button wire:click="remove({{ $item->id }})" class="shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-5 h-5 fill-black">
                                            <path d="M135.2 17.7C140.8 7.6 151.3 0 163.2 0H284.8c11.9 0 22.4 7.6 28 17.7L328 32H432c8.8 0 16 7.2 16 16s-7.2 16-16 16H416l-25.6 364.3c-1.3 18.2-16.2 32.7-34.5 32.7H92.1c-18.3 0-33.2-14.5-34.5-32.7L32 64H16c-8.8 0-16-7.2-16-16s7.2-16 16-16H120l15.2-14.3zM171.2 192c-8.8 0-16 7.2-16 16v192c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm105.6 0c-8.8 0-16 7.2-16 16v192c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z"/>
                                        </svg>
                                    </button>
                                </div>

                                <div class="flex items-end justify-between mt-3">
                                    {{-- Quantity Selector --}}
                                    <div class="flex items-center border border-black">
                                        <button wire:click="decrement({{ $item->id }})" class="w-8 h-7 flex items-center justify-center hover:font-bold transition">-</button>
                                        <div class="w-10 h-7 flex items-center justify-center text-sm font-medium">{{ $item->quantity }}</div>
                                        <button wire:click="increment({{ $item->id }})" class="w-8 h-7 flex items-center justify-center hover:font-bold transition">+</button>
                                    </div>

                                    {{-- Price --}}
                                    <span class="font-medium text-sm">LKR {{ number_format($item->subtotal, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-gray-500">
                            <p>Your cart is empty.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Favorites (MATCH MOCKUP) --}}
                @if($this->favorites->isNotEmpty())
                    <div class="mt-10 bg-[#4FB5D0] px-8 md:px-10 py-6">
                        <h3 class="font-bold text-xl text-black text-center mb-6">Favorites</h3>

                        <div class="space-y-5">
                            @foreach($this->favorites as $fav)
                                <div class="flex items-center gap-6">
                                    {{-- Image Box (white square with black border) --}}
                                    <div class="w-[86px] h-[86px] bg-white border-2 border-black flex items-center justify-center shrink-0">
                                        <img src="{{ asset($fav->product->image_path) }}" class="w-16 h-16 object-contain">
                                    </div>

                                    {{-- Text --}}
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm md:text-base font-medium text-black leading-snug">
                                            {{ $fav->product->name }}
                                            @if($fav->specification?->name)
                                                <span class="font-medium text-black">({{ $fav->specification->name }})</span>
                                            @endif
                                        </p>
                                        <p class="text-sm text-black mt-1">
                                            LKR {{ number_format($fav->specification->price ?? 0, 2) }}
                                        </p>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex items-center gap-4 shrink-0">
                                        <button
                                            wire:click="moveToCart({{ $fav->id }})"
                                            class="w-[86px] h-[38px] bg-white border-2 border-black text-black font-medium text-sm uppercase hover:bg-black hover:text-white transition"
                                        >
                                            ADD
                                        </button>

                                        <button wire:click="removeFavorite({{ $fav->id }})" class="hover:opacity-80 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-6 h-6 fill-white">
                                                <path d="M135.2 17.7C140.8 7.6 151.3 0 163.2 0H284.8c11.9 0 22.4 7.6 28 17.7L328 32H432c8.8 0 16 7.2 16 16s-7.2 16-16 16H416l-25.6 364.3c-1.3 18.2-16.2 32.7-34.5 32.7H92.1c-18.3 0-33.2-14.5-34.5-32.7L32 64H16c-8.8 0-16-7.2-16-16s7.2-16 16-16H120l15.2-14.3zM171.2 192c-8.8 0-16 7.2-16 16v192c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm105.6 0c-8.8 0-16 7.2-16 16v192c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="mt-6 border-t border-gray-200 pt-6">
                <div class="flex justify-between items-center text-lg font-bold mb-1">
                    <span>Estimated total</span>
                    <span>LKR {{ number_format($this->total, 2) }}</span>
                </div>
                <p class="text-[10px] text-gray-500 text-center mb-4">
                    Taxes, Discounts and Shipping calculated at checkout
                </p>

                <!-- ✅ ONLY CHANGE: make checkout navigate -->
                <a
                    href="{{ route('checkout.show') }}"
                    class="w-full bg-[#69A985] text-black font-bold py-3 border-2 border-black hover:bg-black hover:text-white transition flex items-center justify-center gap-3"
                >
                    CHECKOUT
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-5 h-5 fill-current">
                        <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
