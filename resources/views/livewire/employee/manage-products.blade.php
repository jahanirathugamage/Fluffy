<div class="bg-white font-['Montserrat'] pt-6">
    {{-- Header --}}
    <div class="flex w-full items-center justify-center">
        <h2 class="font-bold text-[24px] md:text-[30px]">Manage Products</h2>
    </div>

    {{-- Success Message --}}
    @if (session()->has('success'))
        <div class="mx-[20px] sm:mx-[40px] mt-4 p-4 bg-green-100 border-2 border-green-500 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Product Table Header & Add Button --}}
    <div class="flex flex-col items-center justify-left mx-[20px] sm:mx-[40px] gap-[10px] mt-8">
        <div class="w-full md:w-[1100px] flex items-center justify-between gap-3 mb-2">
            {{-- Products Title --}}
            <h3 class="font-bold text-[20px] md:text-[28px]">Products</h3>
            
            {{-- Action Buttons --}}
            <div class="flex items-center gap-3">
                {{-- Filter Button (Desktop with text) --}}
                <button type="button" class="hidden md:flex items-center gap-2 px-4 h-[44px] border-2 border-black bg-white hover:bg-gray-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-5 h-5">
                        <path d="M3.9 54.9C10.5 40.9 24.5 32 40 32H472c15.5 0 29.5 8.9 36.1 22.9s4.6 30.5-5.2 42.5L320 320.9V448c0 12.1-6.8 23.2-17.7 28.6s-23.8 4.3-33.5-3l-64-48c-8.1-6-12.8-15.5-12.8-25.6V320.9L9 97.3C-.7 85.4-2.8 68.8 3.9 54.9z"/>
                    </svg>
                    <span class="font-medium text-[15px]">Filter</span>
                </button>

                {{-- Filter Icon Only (Mobile) --}}
                <button type="button" class="md:hidden w-[44px] h-[44px] border-2 border-black bg-white flex items-center justify-center hover:bg-gray-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-5 h-5">
                        <path d="M3.9 54.9C10.5 40.9 24.5 32 40 32H472c15.5 0 29.5 8.9 36.1 22.9s4.6 30.5-5.2 42.5L320 320.9V448c0 12.1-6.8 23.2-17.7 28.6s-23.8 4.3-33.5-3l-64-48c-8.1-6-12.8-15.5-12.8-25.6V320.9L9 97.3C-.7 85.4-2.8 68.8 3.9 54.9z"/>
                    </svg>
                </button>

                {{-- Add Product Button --}}
                <button wire:click="openAddModal" class="flex w-[150px] h-[44px] border-[2px] border-black bg-white text-black font-medium text-[15px] font-[Montserrat] items-center justify-center gap-2 hover:bg-[#6FAE8D] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 md:w-6 md:h-6">
                        <path d="M352 128C352 110.3 337.7 96 320 96C302.3 96 288 110.3 288 128L288 288L128 288C110.3 288 96 302.3 96 320C96 337.7 110.3 352 128 352L288 352L288 512C288 529.7 302.3 544 320 544C337.7 544 352 529.7 352 512L352 352L512 352C529.7 352 544 337.7 544 320C544 302.3 529.7 288 512 288L352 288L352 128z"/>
                    </svg>
                    Add Product
                </button>
            </div>
        </div>

        {{-- Desktop Table --}}
        <div class="overflow-auto hidden md:block w-full md:w-[1100px]">
            <table class="w-full table-auto border-2 border-gray-300">
                <thead class="border-b-2 border-gray-300">
                    <tr>
                        <th class="p-3 text-sm font-medium tracking-wide text-left">No.</th>
                        <th class="p-3 text-sm font-medium tracking-wide text-left">Product</th>
                        <th class="p-3 text-sm font-medium tracking-wide text-left">Product Name</th>
                        <th class="p-3 text-sm font-medium tracking-wide text-left">Price</th>
                        <th class="p-3 text-sm font-medium tracking-wide text-left">Stock</th>
                        <th class="p-3 text-sm font-medium tracking-wide text-left">Specification</th>
                        <th class="p-3 text-sm font-medium tracking-wide text-left">Category</th>
                        <th class="p-3 text-sm font-medium tracking-wide text-left">Type</th>
                        <th class="p-3 text-sm font-medium tracking-wide text-left">Action</th>
                        <th class="p-3 text-sm font-medium tracking-wide text-left">Delete</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @forelse ($specifications as $spec)
                        <tr>
                            <td class="p-3 text-center text-sm whitespace-nowrap">{{ $spec->id }}</td>
                            <td class="p-3 whitespace-nowrap">
                                @if($spec->product && $spec->product->image_path)
                                    <img src="{{ asset($spec->product->image_path) }}" 
                                         alt="{{ $spec->product->name }}" 
                                         class="w-12 h-12 object-contain bg-gray-50"
                                         onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-12 h-12 bg-gray-200 flex items-center justify-center text-[10px] text-gray-500\'>No Image</div>';">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 flex items-center justify-center text-[10px] text-gray-500">
                                        No Image
                                    </div>
                                @endif
                            </td>
                            <td class="p-3 text-sm whitespace-nowrap">{{ $spec->product->name }}</td>
                            <td class="p-3 text-sm whitespace-nowrap">LKR {{ number_format($spec->price, 2) }}</td>
                            <td class="p-3 text-sm whitespace-nowrap">{{ $spec->stock }}</td>
                            <td class="p-3 text-sm whitespace-nowrap">{{ $spec->name }}</td>
                            <td class="p-3 text-sm whitespace-nowrap">{{ $spec->product->category->name ?? 'N/A' }}</td>
                            <td class="p-3 text-sm whitespace-nowrap">{{ $spec->product->animal->name ?? 'N/A' }}</td>
                            <td class="p-3 text-center whitespace-nowrap">
                                <button wire:click="openEditModal({{ $spec->id }})" type="button" class="hover:opacity-70 transition-opacity">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-6 h-6 mx-auto">
                                        <path d="M320 208C289.1 208 264 182.9 264 152C264 121.1 289.1 96 320 96C350.9 96 376 121.1 376 152C376 182.9 350.9 208 320 208zM320 432C350.9 432 376 457.1 376 488C376 518.9 350.9 544 320 544C289.1 544 264 518.9 264 488C264 457.1 289.1 432 320 432zM376 320C376 350.9 350.9 376 320 376C289.1 376 264 350.9 264 320C264 289.1 289.1 264 320 264C350.9 264 376 289.1 376 320z"/>
                                    </svg>
                                </button>
                            </td>
                            <td class="p-3 text-center whitespace-nowrap">
                                <button wire:click="deleteProduct({{ $spec->id }})" 
                                        wire:confirm="Are you sure you want to delete this product?"
                                        type="button" class="hover:opacity-70 transition-opacity">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-6 h-6">
                                        <path d="M232.7 69.9L224 96L128 96C110.3 96 96 110.3 96 128C96 145.7 110.3 160 128 160L512 160C529.7 160 544 145.7 544 128C544 110.3 529.7 96 512 96L416 96L407.3 69.9C402.9 56.8 390.7 48 376.9 48L263.1 48C249.3 48 237.1 56.8 232.7 69.9zM512 208L128 208L149.1 531.1C150.7 556.4 171.7 576 197 576L443 576C468.3 576 489.3 556.4 490.9 531.1L512 208z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="p-6 text-center text-gray-500">No products found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="grid grid-cols-1 gap-4 md:hidden w-full">
            @forelse ($specifications as $spec)
                <div class="flex flex-col border-b-2 border-gray-300 pb-4">
                    <div class="flex items-start">
                        <div class="p-1 border-2 border-black">
                            @if($spec->product && $spec->product->image_path)
                                <img src="{{ asset($spec->product->image_path) }}" 
                                     alt="{{ $spec->product->name }}" 
                                     class="w-24 h-24 object-contain bg-gray-50"
                                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-24 h-24 bg-gray-200 flex items-center justify-center text-xs text-gray-500\'>No Image</div>';">
                            @else
                                <div class="w-24 h-24 bg-gray-200 flex items-center justify-center text-xs text-gray-500">
                                    No Image
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 grid grid-cols-[auto_1fr] gap-x-2 gap-y-1 ml-2">
                            <p class="font-medium text-left">No.</p><p>{{ $spec->id }}</p>
                            <p class="font-medium text-left">Name</p><p>{{ $spec->product->name }}</p>
                            <p class="font-medium text-left">Price</p><p>LKR {{ number_format($spec->price, 2) }}</p>
                            <p class="font-medium text-left">Stock</p><p>{{ $spec->stock }}</p>
                            <p class="font-medium text-left">Set</p><p>{{ $spec->product->category->name ?? 'N/A' }}</p>
                            <p class="font-medium text-left">Type</p><p>{{ $spec->product->animal->name ?? 'N/A' }}</p>
                        </div>
                        <div class="flex flex-col gap-[20px]">
                            <button wire:click="openEditModal({{ $spec->id }})" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-6 h-6">
                                    <path d="M320 208C289.1 208 264 182.9 264 152C264 121.1 289.1 96 320 96C350.9 96 376 121.1 376 152C376 182.9 350.9 208 320 208zM320 432C350.9 432 376 457.1 376 488C376 518.9 350.9 544 320 544C289.1 544 264 518.9 264 488C264 457.1 289.1 432 320 432zM376 320C376 350.9 350.9 376 320 376C289.1 376 264 350.9 264 320C264 289.1 289.1 264 320 264C350.9 264 376 289.1 376 320z"/>
                                </svg>
                            </button>
                            <button wire:click="deleteProduct({{ $spec->id }})" 
                                    wire:confirm="Are you sure you want to delete this product?"
                                    type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-6 h-6">
                                    <path d="M232.7 69.9L224 96L128 96C110.3 96 96 110.3 96 128C96 145.7 110.3 160 128 160L512 160C529.7 160 544 145.7 544 128C544 110.3 529.7 96 512 96L416 96L407.3 69.9C402.9 56.8 390.7 48 376.9 48L263.1 48C249.3 48 237.1 56.8 232.7 69.9zM512 208L128 208L149.1 531.1C150.7 556.4 171.7 576 197 576L443 576C468.3 576 489.3 556.4 490.9 531.1L512 208z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-6">No products found</p>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8 mb-8 w-full md:w-[1100px]">
            {{ $specifications->links() }}
        </div>
    </div>

    {{-- Add Product Modal --}}
    @if ($showAddModal)
        <div wire:key="add-product-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4">
            <div class="bg-white border-2 border-black w-full max-w-5xl shadow-2xl">
                @include('livewire.employee.partials.product-form', ['isEdit' => false])
            </div>
        </div>
    @endif

    {{-- Edit Product Modal --}}
    @if ($showEditModal)
        <div wire:key="edit-product-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4">
            <div class="bg-white border-2 border-black w-full max-w-5xl shadow-2xl">
                @include('livewire.employee.partials.product-form', ['isEdit' => true])
            </div>
        </div>
    @endif

    @script
    <script>
        Livewire.on('product-saved', () => {
            console.log('Product saved event received, reloading...');
            window.location.reload();
        });
    </script>
    @endscript
</div>
