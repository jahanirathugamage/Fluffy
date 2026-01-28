<div class="max-w-4xl mx-auto py-10 px-4">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Add Product</h1>
        <a href="{{ route('admin.products.index') }}" class="text-sm underline">Back</a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="bg-white rounded-lg shadow p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Animal</label>
                <select wire:model="animal_id" class="w-full rounded-md border-gray-300">
                    <option value="">Select Animal</option>
                    @foreach($animals as $a)
                        <option value="{{ $a->id }}">{{ $a->name }}</option>
                    @endforeach
                </select>
                @error('animal_id') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Category</label>
                <select wire:model="category_id" class="w-full rounded-md border-gray-300">
                    <option value="">Select Category</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Product Name</label>
            <input type="text" wire:model.defer="name" class="w-full rounded-md border-gray-300" />
            @error('name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Details</label>
            <textarea wire:model.defer="details" class="w-full rounded-md border-gray-300" rows="3"></textarea>
            @error('details') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Benefits</label>
            <textarea wire:model.defer="benefits" class="w-full rounded-md border-gray-300" rows="3"></textarea>
            @error('benefits') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Nutrition</label>
            <textarea wire:model.defer="nutrition" class="w-full rounded-md border-gray-300" rows="3"></textarea>
            @error('nutrition') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Product Image (Upload)</label>
            <input type="file" wire:model="image" class="w-full" />
            @error('image') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror

            <div wire:loading wire:target="image" class="text-sm text-gray-500 mt-2">Uploading...</div>

            @if ($image)
                <div class="mt-3">
                    <div class="text-sm text-gray-600 mb-2">Preview</div>
                    <img src="{{ $image->temporaryUrl() }}" class="h-32 w-32 object-cover rounded border" />
                </div>
            @endif
        </div>

        <div class="border-t pt-6">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold">Specifications</h2>
                <button type="button" wire:click="addSpec"
                        class="px-3 py-1 rounded bg-gray-900 text-white text-sm">
                    + Add Spec
                </button>
            </div>

            @error('specs') <div class="text-sm text-red-600 mb-2">{{ $message }}</div> @enderror

            <div class="space-y-4">
                @foreach($specs as $i => $spec)
                    <div class="p-4 rounded border">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Name</label>
                                <input type="text" wire:model.defer="specs.{{ $i }}.name"
                                       class="w-full rounded-md border-gray-300" />
                                @error("specs.$i.name") <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Price</label>
                                <input type="number" step="0.01" wire:model.defer="specs.{{ $i }}.price"
                                       class="w-full rounded-md border-gray-300" />
                                @error("specs.$i.price") <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Stock</label>
                                <input type="number" wire:model.defer="specs.{{ $i }}.stock"
                                       class="w-full rounded-md border-gray-300" />
                                @error("specs.$i.stock") <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mt-3 flex justify-end">
                            <button type="button" wire:click="removeSpec({{ $i }})"
                                    class="px-3 py-1 rounded bg-red-600 text-white text-sm">
                                Remove
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded border">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 rounded bg-black text-white">
                Save Product
            </button>
        </div>
    </form>
</div>
