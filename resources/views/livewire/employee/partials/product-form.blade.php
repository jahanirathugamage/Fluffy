<div class="relative bg-white max-h-[600px] overflow-y-auto">
    {{-- Close Button --}}
    @if ($isEdit)
        <button type="button" wire:click="closeEditModal" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center hover:bg-gray-100 z-10">
    @else
        <button type="button" wire:click="closeAddModal" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center hover:bg-gray-100 z-10">
    @endif
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6">
            <path d="M18 6L6 18M6 6l12 12"/>
        </svg>
    </button>

    <form class="p-6">
        {{-- Title --}}
        <h2 class="text-2xl font-bold mb-6">{{ $isEdit ? 'Edit Product' : 'Add new product' }}</h2>

        {{-- 3 Column Layout --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            {{-- Left Column: Image Upload --}}
            <div class="flex flex-col gap-2">
                <label class="font-semibold text-sm">Product Image</label>
                
                {{-- Image Preview Box --}}
                <div class="border-2 border-gray-300 w-full aspect-square flex items-center justify-center bg-gray-50 relative overflow-hidden">
                    @if ($productImage)
                        <img src="{{ $productImage->temporaryUrl() }}" 
                             alt="Preview" 
                             class="w-full h-full object-contain">
                    @elseif ($isEdit && $existingImage)
                        <img src="{{ asset($existingImage) }}" 
                             alt="Current" 
                             class="w-full h-full object-contain">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-16 h-16 text-gray-400">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                    @endif
                </div>

                {{-- Upload Button --}}
                <label for="imageUpload" class="bg-black text-white py-2 px-4 text-center cursor-pointer hover:bg-gray-800 transition-colors flex items-center justify-center gap-2">
                    <span class="text-xl">+</span>
                    <span>Upload</span>
                </label>
                <input type="file" 
                       id="imageUpload" 
                       wire:model="productImage" 
                       accept="image/*" 
                       class="hidden">
                
                <p class="text-xs text-gray-500 text-center">Max File Size: 5MB<br>Aspect ratio should be 1:1</p>
                @error('productImage') 
                    <p class="text-xs text-red-600">{{ $message }}</p> 
                @enderror
            </div>

            {{-- Middle Column --}}
            <div class="flex flex-col gap-4">
                {{-- Product Name --}}
                <div class="flex flex-col gap-1">
                    <label class="font-semibold text-sm">Product Name</label>
                    <input type="text" 
                           wire:model="name" 
                           placeholder="Product Name"
                           class="border-2 border-gray-300 px-3 py-2 focus:outline-none focus:border-gray-400">
                    @error('name') 
                        <p class="text-xs text-red-600">{{ $message }}</p> 
                    @enderror
                </div>

                {{-- Animal Dropdown --}}
                <div class="flex flex-col gap-1">
                    <label class="font-semibold text-sm">Animal</label>
                    <select wire:model="animal_id" 
                            class="border-2 border-gray-300 px-3 py-2 focus:outline-none focus:border-gray-400 bg-white">
                        <option value="">Please Select</option>
                        @foreach($animals as $animal)
                            <option value="{{ $animal->id }}">{{ $animal->name }}</option>
                        @endforeach
                    </select>
                    @error('animal_id') 
                        <p class="text-xs text-red-600">{{ $message }}</p> 
                    @enderror
                </div>

                {{-- Price --}}
                <div class="flex flex-col gap-1">
                    <label class="font-semibold text-sm">Price</label>
                    <div class="border-2 border-gray-300 flex items-center">
                        <span class="px-3 py-2 bg-gray-100 border-r-2 border-gray-300">LKR</span>
                        <input type="text" 
                               wire:model="price" 
                               placeholder="0.00"
                               class="flex-1 px-3 py-2 focus:outline-none">
                    </div>
                    @error('price') 
                        <p class="text-xs text-red-600">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            {{-- Right Column --}}
            <div class="flex flex-col gap-4">
                {{-- Specification --}}
                <div class="flex flex-col gap-1">
                    <label class="font-semibold text-sm">Specification</label>
                    <input type="text" 
                           wire:model="specName" 
                           placeholder="Specification Ex. 10kg"
                           class="border-2 border-gray-300 px-3 py-2 focus:outline-none focus:border-gray-400">
                    @error('specName') 
                        <p class="text-xs text-red-600">{{ $message }}</p> 
                    @enderror
                </div>

                {{-- Category Dropdown --}}
                <div class="flex flex-col gap-1">
                    <label class="font-semibold text-sm">Category</label>
                    <select wire:model="category_id" 
                            class="border-2 border-gray-300 px-3 py-2 focus:outline-none focus:border-gray-400 bg-white">
                        <option value="">Please Select</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') 
                        <p class="text-xs text-red-600">{{ $message }}</p> 
                    @enderror
                </div>

                {{-- Stock --}}
                <div class="flex flex-col gap-1">
                    <label class="font-semibold text-sm">Stock</label>
                    <input type="number" 
                           wire:model="stock" 
                           placeholder="Product Stock"
                           min="0"
                           step="1"
                           class="border-2 border-gray-300 px-3 py-2 focus:outline-none focus:border-gray-400">
                    @error('stock') 
                        <p class="text-xs text-red-600">{{ $message }}</p> 
                    @enderror
                </div>
            </div>
        </div>

        {{-- Product Description (Full Width, Below Grid) --}}
        <div class="flex flex-col gap-1 mb-6">
            <label class="font-semibold text-sm">Product Description</label>
            <textarea wire:model="details" 
                      rows="4" 
                      placeholder="Write description ..."
                      class="border-2 border-gray-300 px-3 py-2 focus:outline-none focus:border-gray-400 resize-none"></textarea>
            @error('details') 
                <p class="text-xs text-red-600">{{ $message }}</p> 
            @enderror
        </div>

        {{-- Product Benefits (Full Width, Below) --}}
        <div class="flex flex-col gap-1 mb-6">
            <label class="font-semibold text-sm">Product Benefits</label>
            <textarea wire:model="benefits" 
                      rows="4" 
                      placeholder="Write benefits ..."
                      class="border-2 border-gray-300 px-3 py-2 focus:outline-none focus:border-gray-400 resize-none"></textarea>
            @error('benefits') 
                <p class="text-xs text-red-600">{{ $message }}</p> 
            @enderror
        </div>

        {{-- Product Nutrition (Full Width, Below) --}}
        <div class="flex flex-col gap-1 mb-6">
            <label class="font-semibold text-sm">Product Nutrition</label>
            <textarea wire:model="nutrition" 
                      rows="4" 
                      placeholder="Write nutrition details ..."
                      class="border-2 border-gray-300 px-3 py-2 focus:outline-none focus:border-gray-400 resize-none"></textarea>
            @error('nutrition') 
                <p class="text-xs text-red-600">{{ $message }}</p> 
            @enderror
        </div>

        {{-- Action Buttons --}}
        <div class="grid grid-cols-2 gap-4">
            <button type="button" 
                    wire:click="submitForm"
                    wire:loading.attr="disabled"
                    class="bg-cyan-500 text-white py-3 px-6 font-medium hover:bg-cyan-600 transition-colors disabled:opacity-50">
                <span wire:loading.remove>{{ $isEdit ? 'Update' : 'Save' }}</span>
                <span wire:loading>Processing...</span>
            </button>
            @if ($isEdit)
                <button type="button" wire:click="closeEditModal" class="border-2 border-black bg-white text-black py-3 px-6 font-medium hover:bg-gray-100 transition-colors">
            @else
                <button type="button" wire:click="closeAddModal" class="border-2 border-black bg-white text-black py-3 px-6 font-medium hover:bg-gray-100 transition-colors">
            @endif
                Cancel
            </button>
        </div>
    </form>
</div>
