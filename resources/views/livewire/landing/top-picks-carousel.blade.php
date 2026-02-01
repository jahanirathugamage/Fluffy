<div class="flex flex-col items-center justify-center gap-4 font-['Montserrat'] text-black bg-[#69A985] my-10 mx-0 md:m-10 py-10 w-full md:w-auto">
    <h2 class="text-[24px] md:text-[30px] font-bold">Top Picks</h2>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            height: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #69A985; 
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #69A985; /* Slightly darker green for visibility, or same green if desired invisible */
            border-radius: 4px;
            border: 1px solid #69A985;
        }
        /* Firefox */
        .custom-scrollbar {
            scrollbar-color: #558b6e #69A985;
            scrollbar-width: thin;
        }
    </style>

    <div class="w-full relative px-0 md:px-20 mt-6">
        <div class="custom-scrollbar flex gap-4 md:gap-10 overflow-x-auto md:overflow-visible snap-x md:snap-none px-4 md:px-0 pb-4 md:pb-0 scroll-smooth justify-start md:justify-center items-stretch">
            @foreach($this->visibleProducts() as $product)
                <div class="flex-shrink-0 w-[55vw] sm:w-[45vw] md:w-auto snap-center flex justify-center">
                    <livewire:products.product-card :product="$product" :key="'top-pick-'.$product->id" />
                </div>
            @endforeach
        </div>

        {{-- Nav buttons (Desktop only) --}}
        <button wire:click="prev" class="hidden md:block absolute left-0 top-1/2 -translate-y-1/2 text-black text-3xl font-bold z-10 hover:scale-110 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-10 h-10">
                <path fill="#000000" d="M169.4 297.4C156.9 309.9 156.9 330.2 169.4 342.7L361.4 534.7C373.9 547.2 394.2 547.2 406.7 534.7C419.2 522.2 419.2 501.9 406.7 489.4L237.3 320L406.6 150.6C419.1 138.1 419.1 117.8 406.6 105.3C394.1 92.8 373.8 92.8 361.3 105.3L169.3 297.3z"/>
            </svg>
        </button>

        <button wire:click="next" class="hidden md:block absolute right-0 top-1/2 -translate-y-1/2 text-black text-3xl font-bold z-10 hover:scale-110 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-10 h-10">
                <path fill="#000000" d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
            </svg>
        </button>

        {{-- dots --}}
        <div class="flex gap-2 items-center justify-center mt-6">
            @for($i = 0; $i < $this->totalPages(); $i++)
                <button wire:click="goTo({{ $i }})"
                    class="w-3 h-3 rounded-full transition-colors border-2 border-black {{ $current === $i ? 'bg-white' : 'bg-gray-400' }}">
                </button>
            @endfor
        </div>
    </div>
</div>
