<div class="font-['Montserrat'] max-w-6xl mx-auto px-4 py-10 grid grid-cols-1 md:grid-cols-2 gap-10">

    {{-- Left: Product Image --}}
    <div class="flex flex-col items-center justify-center border-black border-4 bg-white p-4 relative">
        <img
            src="{{ asset($product->image_path) }}"
            alt="{{ $product->name }}"
            class="w-[200px] md:w-[300px] object-contain {{ ($this->currentSpec && $this->currentSpec->stock <= 0) ? 'opacity-50 grayscale' : '' }}"
        >
        @if($this->currentSpec && $this->currentSpec->stock <= 0)
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="bg-black/80 text-white px-6 py-2 text-xl font-bold uppercase tracking-widest transform -rotate-12 border-2 border-white">
                    Out of Stock
                </div>
            </div>
        @endif
    </div>

    {{-- Right: Product Info --}}
    <div class="flex flex-col gap-4">

        {{-- Product Name & Price --}}
        <div>
            <h2 class="text-[20px] md:text-[24px] font-bold leading-tight">
                {{ $product->name }}
            </h2>

            <p class="text-[18px] md:text-[20px] font-semibold mt-1">
                @if($this->currentSpec)
                    LKR {{ number_format($this->currentSpec->price, 2) }}
                @else
                    Unavailable
                @endif
            </p>
        </div>

        {{-- Spec Options --}}
        @if($product->specifications->count() > 0)
            <div>
                <p class="font-semibold text-sm md:text-base">Spec:</p>

                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach($product->specifications as $spec)
                        <button
                            type="button"
                            wire:click="$set('selectedSpecId', {{ $spec->id }})"
                            class="px-4 py-2 border-2 border-black transition
                            {{ $selectedSpecId === $spec->id
                                ? 'bg-black text-white'
                                : 'bg-white text-black hover:bg-gray-100' }}"
                        >
                            {{ $spec->name }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Quantity Selector --}}
        <div class="flex items-center gap-4 mt-2">
            <p class="font-semibold text-sm md:text-base">Quantity:</p>

            <div class="flex border-2 border-black bg-white h-[42px]">
                <button
                    type="button"
                    wire:click="decrementQuantity"
                    class="w-[42px] flex items-center justify-center text-xl font-bold border-r-2 border-black hover:bg-gray-100"
                >
                    −
                </button>

                <div class="w-[48px] flex items-center justify-center font-bold text-base">
                    {{ $quantity }}
                </div>

                <button
                    type="button"
                    wire:click="incrementQuantity"
                    class="w-[42px] flex items-center justify-center text-xl font-bold border-l-2 border-black hover:bg-gray-100"
                >
                    +
                </button>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-4 mt-2">
            <button
                type="button"
                wire:click="addToCart"
                class="w-full py-2 font-bold border-2 border-black transition-colors flex-1
                {{ ($this->currentSpec && $this->currentSpec->stock > 0)
                    ? 'bg-[#4FB5D0] text-white hover:bg-black'
                    : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}"
                {{ (!$this->currentSpec || $this->currentSpec->stock <= 0) ? 'disabled' : '' }}
            >
                {{ ($this->currentSpec && $this->currentSpec->stock > 0) ? 'Add to Cart' : 'Out of Stock' }}
            </button>
            
            @error('quantity') 
                <div class="absolute top-full left-0 mt-2 text-red-600 font-bold text-sm">
                    {{ $message }}
                </div>
            @enderror

            {{-- Favorites --}}
            <button type="button" wire:click="toggleFavorite" class="flex items-center justify-center group">
                <svg xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 24 24"
                     class="w-12 h-12 transition-colors
                     {{ $this->isFavorite
                        ? 'fill-black stroke-black'
                        : 'fill-none stroke-black group-hover:fill-black' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </button>
        </div>

        {{-- Product Deeds --}}
        <div class="mt-4 border-t-2 border-black pt-4 select-none">

            {{-- Product Details --}}
            <div class="border-b border-gray-200">
                <button wire:click="toggleSection('details')" class="flex justify-between w-full font-semibold py-2 items-center">
                    Product Details
                    <span class="ml-3">
                        @if($openSection === 'details')
                            {{-- MINUS --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                 fill="none" stroke="black" stroke-width="3"
                                 viewBox="0 0 24 24">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                        @else
                            {{-- PLUS --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                 fill="none" stroke="black" stroke-width="3"
                                 viewBox="0 0 24 24">
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                        @endif
                    </span>
                </button>

                @if($openSection === 'details')
                    <div class="text-sm text-gray-700 mb-2">
                        {!! nl2br(e($product->details)) !!}
                    </div>
                @endif
            </div>

            {{-- Benefits --}}
            <div class="border-b border-gray-200">
                <button wire:click="toggleSection('benefits')" class="flex justify-between w-full font-semibold py-2 items-center">
                    Benefits
                    <span class="ml-3">
                        @if($openSection === 'benefits')
                            {{-- MINUS --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                 fill="none" stroke="black" stroke-width="3"
                                 viewBox="0 0 24 24">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                        @else
                            {{-- PLUS --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                 fill="none" stroke="black" stroke-width="3"
                                 viewBox="0 0 24 24">
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                        @endif
                    </span>
                </button>

                @if($openSection === 'benefits')
                    <div class="text-sm text-gray-700 mb-2">
                        {!! nl2br(e($product->benefits)) !!}
                    </div>
                @endif
            </div>

            {{-- Nutrition --}}
            <div>
                <button wire:click="toggleSection('nutrition')" class="flex justify-between w-full font-semibold py-2 items-center">
                    Nutritional Information
                    <span class="ml-3">
                        @if($openSection === 'nutrition')
                            {{-- MINUS --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                 fill="none" stroke="black" stroke-width="3"
                                 viewBox="0 0 24 24">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                        @else
                            {{-- PLUS --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                 fill="none" stroke="black" stroke-width="3"
                                 viewBox="0 0 24 24">
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                        @endif
                    </span>
                </button>

                @if($openSection === 'nutrition')
                    <div class="text-sm text-gray-700 mb-2">
                        {!! nl2br(e($product->nutrition)) !!}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
