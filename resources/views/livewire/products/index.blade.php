<div class="flex flex-col items-center justify-start m-[20px] sm:m-[40px] gap-[10px] font-['Montserrat']">
    {{-- Header Row (matches legacy width + spacing) --}}
    <div class="w-full md:w-[870px] flex justify-between items-center md:gap-[60px] mb-2">
        <h3 class="font-bold text-[24px] md:text-[30px]">
            {{ $pageTitle }}
        </h3>

        <button
            wire:click="toggleFilters"
            class="flex w-[130px] md:w-[150px] h-[35px] md:h-[40px]
                   border-[2px] border-black bg-[#6FAE8D] text-black
                   font-medium text-[14px] md:text-[16px]
                   items-center justify-center gap-[7px]"
        >
            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="2.3"
                 stroke="currentColor"
                 class="h-5 w-5 md:h-6 md:w-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
            </svg>
            <span>Show filter</span>
        </button>
    </div>

    {{-- Product Filter Modal (keep your logic) --}}
    @if($showFilters)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/50 backdrop-blur-sm transition-opacity">
            @livewire('components.product-filter', [
                'categories' => $categories,
                'animals' => $animals,
                'selectedCategories' => $selectedCategories,
                'selectedAnimals' => $selectedAnimals,
                'inStockOnly' => $inStockOnly,
                'minPrice' => $minPrice,
                'maxPrice' => $maxPrice
            ])
        </div>
    @endif

    {{-- Products Grid (exact legacy layout) --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-[20px] md:gap-[60px] w-full max-w-[870px]">
        @forelse($products as $product)
            <div class="w-full">
                <livewire:products.product-card :product="$product" :key="$product->id" />
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center py-12 text-center font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-12 h-12">
                    <path d="M320 576C178.6 576 64 461.4 64 320C64 178.6 178.6 64 320 64C461.4 64 576 178.6 576 320C576 461.4 461.4 576 320 576zM320 384C302.3 384 288 398.3 288 416C288 433.7 302.3 448 320 448C337.7 448 352 433.7 352 416C352 398.3 337.7 384 320 384zM320 192C301.8 192 287.3 207.5 288.6 225.7L296 329.7C296.9 342.3 307.4 352 319.9 352C332.5 352 342.9 342.3 343.8 329.7L351.2 225.7C352.5 207.5 338.1 192 319.8 192z"/>
                </svg>
                <h3>No products found.</h3>
            </div>
        @endforelse
    </div>

    {{-- Pagination (keep your current pagination logic) --}}
    <div class="w-full max-w-[870px] mt-6">
        {{ $products->links() }}
    </div>
</div>
