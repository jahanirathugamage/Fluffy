<div>
    {{-- Filter Modal --}}
    @if($show ?? true)
    {{-- Backdrop --}}
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
         wire:click.self="$dispatch('closeFilters')">
        
        {{-- Modal Container - Removed rounded-lg, added border-4 (close to stroke of 3), max-h-[90vh] --}}
        <div class="bg-white shadow-xl w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto border-[3px] border-black">
            
            {{-- Header --}}
            {{-- Removed border-b --}}
            <div class="flex items-center justify-between p-6 sticky top-0 bg-white z-10">
                <h2 class="text-xl font-bold font-['Montserrat']">Filters</h2>
                <button wire:click="$dispatch('closeFilters')" 
                        class="text-black hover:text-gray-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Filter Content --}}
            <div class="px-6 pb-6 space-y-4 font-['Montserrat']">
                
                {{-- Category Filter --}}
                <div class="pb-4">
                    <button wire:click="toggleCategory" 
                            class="w-full flex items-center justify-between py-2 text-left font-bold">
                        <span>Category</span>
                        {{-- Icon --}}
                        <svg class="w-5 h-5 transform transition-transform {{ $categoryExpanded ? 'rotate-180' : '' }}" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    @if($categoryExpanded)
                    <div class="mt-3 space-y-2 pl-1">
                        @foreach($categories as $category)
                        <label class="flex items-center space-x-3 cursor-pointer">
                            {{-- Checkbox: text-black, focus:ring-black, removed rounded --}}
                            <input type="checkbox" 
                                   wire:model="selectedCategories" 
                                   value="{{ $category->id }}"
                                   class="w-5 h-5 text-black border-2 border-gray-400 focus:ring-black">
                            <span class="text-gray-700 font-medium">{{ $category->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Animal Filter --}}
                <div class="pb-4">
                    <button wire:click="toggleAnimal" 
                            class="w-full flex items-center justify-between py-2 text-left font-bold">
                        <span>Animal</span>
                        <svg class="w-5 h-5 transform transition-transform {{ $animalExpanded ? 'rotate-180' : '' }}" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    @if($animalExpanded)
                    <div class="mt-3 space-y-2 pl-1">
                        @foreach($animals as $animal)
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" 
                                   wire:model="selectedAnimals" 
                                   value="{{ $animal->id }}"
                                   class="w-5 h-5 text-black border-2 border-gray-400 focus:ring-black">
                            <span class="text-gray-700 font-medium">{{ $animal->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- In Stock Only Toggle --}}
                <div class="pb-4">
                    <div class="flex items-center justify-between py-2">
                        <span class="font-bold">In Stock Only</span>
                        {{-- Toggle: rounded-full for pill shape, border-2 border-black --}}
                        <button type="button" 
                                wire:click="$toggle('inStockOnly')"
                                class="relative inline-flex h-6 w-11 items-center rounded-full border-2 border-black transition-colors focus:outline-none {{ $inStockOnly ? 'bg-black' : 'bg-white' }}">
                            {{-- Handle: rounded-full, dynamic color (black when off, white when on) --}}
                            <span class="inline-block h-4 w-4 transform rounded-full transition-transform {{ $inStockOnly ? 'translate-x-6 bg-white' : 'translate-x-[2px] bg-black' }}"></span>
                        </button>
                    </div>
                </div>

                {{-- Price Range Filter --}}
                <div class="pb-4">
                    <button wire:click="togglePrice" 
                            class="w-full flex items-center justify-between py-2 text-left font-bold">
                        <span>Price</span>
                        <svg class="w-5 h-5 transform transition-transform {{ $priceExpanded ? 'rotate-180' : '' }}" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    @if($priceExpanded)
                    <div class="mt-3 space-y-3">
                        <div class="flex items-center space-x-3">
                            <div class="flex-1">
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">LKR</span>
                                    {{-- Removed rounded, placeholder --}}
                                    <input type="number" 
                                           wire:model="minPrice"
                                           min="0"
                                           step="0.01"
                                           class="w-full pl-12 pr-3 py-2 border-2 border-gray-400 focus:ring-2 focus:ring-black focus:border-black font-medium">
                                </div>
                            </div>
                            <span class="text-gray-500 font-bold">to</span>
                            <div class="flex-1">
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">LKR</span>
                                    {{-- Removed rounded, placeholder --}}
                                    <input type="number" 
                                           wire:model="maxPrice"
                                           min="0"
                                           step="0.01"
                                           class="w-full pl-12 pr-3 py-2 border-2 border-gray-400 focus:ring-2 focus:ring-black focus:border-black font-medium">
                                </div>
                            </div>
                        </div>
                        @error('minPrice') 
                            <span class="text-red-600 text-sm font-medium">{{ $message }}</span> 
                        @enderror
                        @error('maxPrice') 
                            <span class="text-red-600 text-sm font-medium">{{ $message }}</span> 
                        @enderror
                    </div>
                    @endif
                </div>

            </div>

            {{-- Apply Button --}}
            {{-- Removed border-t to remove separator line --}}
            <div class="p-6 sticky bottom-0 bg-white">
                {{-- Removed rounded-lg, uppercase replaced with normal case (Apply) --}}
                <button wire:click="applyFilters" 
                        class="w-full bg-black text-white py-3 px-4 font-bold hover:bg-gray-800 transition-colors tracking-wide">
                    Apply
                </button>
            </div>

        </div>
    </div>
    @endif
</div>
