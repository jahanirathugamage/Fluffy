<div class="flex flex-col items-center justify-center gap-4 font-['Montserrat'] text-black bg-[#69A985] m-10">
    <h2 class="text-[24px] md:text-[30px] font-bold mt-10">Top Picks</h2>

    <div class="w-full overflow-hidden relative px-6 md:px-40 mt-6">
        <div class="flex gap-6 md:gap-20">
            @foreach($this->visibleProducts() as $product)
                <div class="flex-shrink-0 w-[200px] md:w-[250px] h-auto border-[2px] md:border-[3px] border-black flex flex-col items-center justify-center gap-[6px] p-2 bg-white">
                    <img
                        src="{{ asset('assets/images/'.$product->animalName.'/'.$product->productImage) }}"
                        alt="{{ $product->productName }}"
                        class="w-[120px] md:w-[180px] h-[160px] md:h-[220px] object-contain mt-[10px]"
                    >

                    <p class="font-regular text-[#5A5A5A] text-[12px] md:text-[14px]">
                        {{ strtoupper($product->categoryName) }}
                    </p>

                    <a href="#" class="h-[48px] md:h-[56px] flex items-center justify-center text-center">
                        <p class="font-medium text-black text-[16px] md:text-[20px] text-center hover:underline">
                            {{ $product->productName }}
                        </p>
                    </a>

                    <p class="font-regular text-black text-[16px] md:text-[20px] text-center">
                        LKR {{ number_format((float)$product->productPrice, 2) }}
                    </p>

                    <div class="flex gap-[10px] md:gap-[20px] mt-[5px] mb-[10px] md:mb-[20px]">
                        <button class="w-[100px] md:w-[120px] h-[30px] md:h-[35px] border-[2px] border-black bg-white text-black text-[14px] md:text-[16px] flex items-center justify-center hover:text-white hover:bg-black">
                            Add to Cart
                        </button>

                        <button aria-label="favorite">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-6 h-6 md:w-8 md:h-8">
                                <path d="M378.9 80c-27.3 0-53 13.1-69 35.2l-34.4 47.6c-4.5 6.2-11.7 9.9-19.4 9.9s-14.9-3.7-19.4-9.9l-34.4-47.6c-16-22.1-41.7-35.2-69-35.2-47 0-85.1 38.1-85.1 85.1 0 49.9 32 98.4 68.1 142.3 41.1 50 91.4 94 125.9 120.3 3.2 2.4 7.9 4.2 14 4.2s10.8-1.8 14-4.2c34.5-26.3 84.8-70.4 125.9-120.3 36.2-43.9 68.1-92.4 68.1-142.3 0-47-38.1-85.1-85.1-85.1z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Nav buttons --}}
        <button wire:click="prev" class="absolute left-0 top-1/2 -translate-y-1/2 text-black text-3xl font-bold z-10">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-10 h-10">
                <path fill="#000000" d="M169.4 297.4C156.9 309.9 156.9 330.2 169.4 342.7L361.4 534.7C373.9 547.2 394.2 547.2 406.7 534.7C419.2 522.2 419.2 501.9 406.7 489.4L237.3 320L406.6 150.6C419.1 138.1 419.1 117.8 406.6 105.3C394.1 92.8 373.8 92.8 361.3 105.3L169.3 297.3z"/>
            </svg>
        </button>

        <button wire:click="next" class="absolute right-0 top-1/2 -translate-y-1/2 text-black text-3xl font-bold z-10">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-10 h-10">
                <path fill="#000000" d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
            </svg>
        </button>

        {{-- dots --}}
        <div class="flex gap-2 items-center justify-center mt-10 mb-10">
            @for($i = 0; $i < $this->totalPages(); $i++)
                <button wire:click="goTo({{ $i }})"
                    class="w-3 h-3 rounded-full transition-colors border-2 border-black {{ $current === $i ? 'bg-white' : 'bg-gray-400' }}">
                </button>
            @endfor
        </div>
    </div>
</div>
