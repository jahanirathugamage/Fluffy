<div class="font-['Montserrat'] max-w-6xl mx-auto px-4 py-10 grid grid-cols-1 md:grid-cols-2 gap-10">

    {{-- Left: Product Image --}}
    <div class="flex flex-col items-center justify-center border-black border-4 bg-white p-4">
        <img src="{{ asset($product->image_path) }}" 
             alt="{{ $product->name }}" 
             class="w-[200px] md:w-[300px] object-contain">
    </div>

    {{-- Right: Product Info --}}
    <div class="flex flex-col gap-6">

        {{-- Product Name & Price --}}
        <div>
            <h2 class="text-[20px] md:text-[24px] font-bold leading-tight">{{ $product->name }}</h2>
            <p class="text-[18px] md:text-[20px] font-semibold mt-2">
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
                    <button type="button"
                            wire:click="$set('selectedSpecId', {{ $spec->id }})"
                            class="px-4 py-2 border-2 text-sm transition-colors {{ $selectedSpecId === $spec->id ? 'bg-black text-white border-black' : 'bg-white text-black border-black hover:bg-gray-100' }}">
                        {{ $spec->name }}
                    </button>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Quantity Selector --}}
        <div class="flex items-center gap-4">
            <p class="font-semibold text-sm md:text-base">Quantity:</p>
            <div class="flex items-center border-2 border-gray-400 bg-white">
                <button type="button" wire:click="decrementQuantity" class="px-3 py-1 hover:bg-gray-100 text-lg font-bold">-</button>
                <input type="text" value="{{ $quantity }}" readonly class="w-12 text-center border-l border-r border-gray-400 py-1 font-bold focus:outline-none bg-transparent">
                <button type="button" wire:click="incrementQuantity" class="px-3 py-1 hover:bg-gray-100 text-lg font-bold">+</button>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-4">
            {{-- Add to Cart --}}
            <button type="button" 
                    wire:click="addToCart" 
                    class="w-full py-3 bg-[#4FB5D0] text-white font-bold border-2 border-black hover:bg-black transition-colors flex-1 uppercase tracking-wide">
                Add to Cart
            </button>

            {{-- Add to Favorites --}}
            <button type="button" wire:click="toggleFavorite" class="flex items-center justify-center group">
                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-12 h-12 transition-colors {{ $this->isFavorite ? 'fill-black stroke-black' : 'fill-none stroke-black' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </button>
        </div>

        {{-- Product Deeds (Accordion) --}}
        <div class="mt-4 border-t-2 border-black pt-4 select-none">
            {{-- Details --}}
            <div class="border-b border-gray-200">
                <button wire:click="toggleSection('details')" class="flex justify-between w-full font-semibold py-3 items-center hover:bg-gray-50">
                    Product Details
                    <span class="transform transition-transform {{ $openSection === 'details' ? 'rotate-180' : 'rotate-0' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </button>
                @if($openSection === 'details')
                    <div class="text-sm text-gray-700 pb-4 animate-in fade-in slide-in-from-top-2 duration-300">
                        {!! nl2br(e($product->details)) !!}
                    </div>
                @endif
            </div>

            {{-- Benefits --}}
            <div class="border-b border-gray-200">
                <button wire:click="toggleSection('benefits')" class="flex justify-between w-full font-semibold py-3 items-center hover:bg-gray-50">
                    Benefits
                    <span class="transform transition-transform {{ $openSection === 'benefits' ? 'rotate-180' : 'rotate-0' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </button>
                @if($openSection === 'benefits')
                    <div class="text-sm text-gray-700 pb-4 animate-in fade-in slide-in-from-top-2 duration-300">
                         {!! nl2br(e($product->benefits)) !!}
                    </div>
                @endif
            </div>

            {{-- Nutrition --}}
            <div>
                <button wire:click="toggleSection('nutrition')" class="flex justify-between w-full font-semibold py-3 items-center hover:bg-gray-50">
                    Nutritional Information
                    <span class="transform transition-transform {{ $openSection === 'nutrition' ? 'rotate-180' : 'rotate-0' }}">
                         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </button>
                @if($openSection === 'nutrition')
                    <div class="text-sm text-gray-700 pb-4 animate-in fade-in slide-in-from-top-2 duration-300">
                         {!! nl2br(e($product->nutrition)) !!}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
