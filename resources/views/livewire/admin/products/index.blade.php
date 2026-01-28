<div class="max-w-7xl mx-auto py-10 px-4">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Manage Products</h1>

        <a href="{{ route('admin.products.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-md
                    bg-indigo-600 text-white font-medium shadow
                    hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <span class="text-lg leading-none">+</span>
            <span>Add Product</span>
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-4 rounded-lg shadow mb-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text"
                   class="w-full rounded-md border-gray-300"
                   placeholder="Search product name..."
                   wire:model.live="search" />

            <select class="w-full rounded-md border-gray-300" wire:model.live="animalId">
                <option value="">All Animals</option>
                @foreach($animals as $animal)
                    <option value="{{ $animal->id }}">{{ $animal->name }}</option>
                @endforeach
            </select>

            <select class="w-full rounded-md border-gray-300" wire:model.live="categoryId">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-3">ID</th>
                    <th class="text-left px-4 py-3">Product</th>
                    <th class="text-left px-4 py-3">Animal</th>
                    <th class="text-left px-4 py-3">Category</th>
                    <th class="text-left px-4 py-3">Image</th>
                    <th class="text-right px-4 py-3">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($products as $p)
                    <tr>
                        <td class="px-4 py-3">{{ $p->id }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ $p->name }}</div>
                            <div class="text-sm text-gray-500 line-clamp-1">{{ $p->details }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $p->animal?->name }}</td>
                        <td class="px-4 py-3">{{ $p->category?->name }}</td>
                        <td class="px-4 py-3">
                            @if($p->image_path)
                                <img src="{{ asset($p->image_path) }}" class="h-10 w-10 object-cover rounded" />
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.products.edit', $p->id) }}"
                               class="px-3 py-1 rounded bg-gray-900 text-white text-sm">
                                Edit
                            </a>

                            <button
                                onclick="confirm('Delete this product?') || event.stopImmediatePropagation()"
                                wire:click="delete({{ $p->id }})"
                                class="px-3 py-1 rounded bg-red-600 text-white text-sm">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-gray-500">
                            No products found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $products->links() }}
        </div>
    </div>
</div>
