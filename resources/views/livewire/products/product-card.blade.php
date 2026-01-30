<div class="w-full md:w-[250px] h-auto border-[2px] md:border-[3px] border-black flex flex-col items-center justify-center gap-[6px] p-2 bg-white">
    {{-- Product Image --}}
    <a href="{{ route('products.show', $product) }}" class="block w-full">
        <img
            src="{{ asset($product->image_path) }}"
            alt="{{ $product->name }}"
            class="w-[120px] md:w-[180px] h-[160px] md:h-[220px] object-contain mt-[10px] mx-auto"
        >
    </a>

    {{-- Category text (legacy style) --}}
    <p class="font-regular text-[#5A5A5A] text-[12px] md:text-[14px]">
        {{ strtoupper($product->category->name) }}
    </p>

    {{-- Product name (legacy style + height box) --}}
    <a href="{{ route('products.show', $product) }}" class="h-[48px] md:h-[56px] flex items-center justify-center text-center px-2">
        <p class="font-medium text-black text-[16px] md:text-[20px] text-center hover:underline leading-tight">
            {{ $product->name }}
        </p>
    </a>

    {{-- Price --}}
    <p class="font-regular text-black text-[16px] md:text-[20px] text-center">
        @if($product->specifications->isNotEmpty())
            LKR {{ number_format($product->specifications->min('price'), 2) }}
        @else
            Out of Stock
        @endif
    </p>

    {{-- Cart + Favorites row (legacy spacing/sizing) --}}
    <div class="flex gap-[10px] md:gap-[20px] mt-[5px] mb-[10px] md:mb-[20px] items-center">
        <button
            wire:click="addToCart"
            class="w-[100px] md:w-[120px] h-[30px] md:h-[35px]
                   border-[2px] border-black bg-white text-black
                   text-[14px] md:text-[16px]
                   flex items-center justify-center
                   hover:text-white hover:bg-black transition-colors"
        >
            Add to Cart
        </button>

        <button wire:click="toggleFavorite" class="p-0">
            <svg xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 24 24"
                 class="w-6 h-6 md:w-8 md:h-8 text-black transition-colors
                        {{ $this->isFavorite ? 'fill-black' : 'fill-transparent hover:fill-black' }}"
                 fill="currentColor"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
            </svg>
        </button>
    </div>
</div>
