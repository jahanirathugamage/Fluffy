<div>
    {{-- Filters --}}
    <div class="bg-white p-4 rounded-lg shadow mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">

        <input
            type="text"
            wire:model.debounce.300ms="search"
            placeholder="Search products..."
            class="border rounded px-3 py-2 w-full"
        />

        <select wire:model="animalId" class="border rounded px-3 py-2">
            <option value="">All Animals</option>
            @foreach($animals as $animal)
                <option value="{{ $animal->id }}">{{ $animal->name }}</option>
            @endforeach
        </select>

        <select wire:model="categoryId" class="border rounded px-3 py-2">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>

        <select wire:model="sort" class="border rounded px-3 py-2">
            <option value="name_asc">Name (A–Z)</option>
            <option value="price_asc">Price (Low → High)</option>
            <option value="price_desc">Price (High → Low)</option>
        </select>

    </div>

    {{-- Product Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $product)
            <div class="bg-white rounded-lg shadow p-4">
                <img
                    src="{{ asset($product->image_path) }}"
                    class="w-full h-48 object-cover rounded mb-3"
                />

                <h3 class="font-semibold text-lg">{{ $product->name }}</h3>
                <p class="text-sm text-gray-600">
                    {{ $product->animal->name }} · {{ $product->category->name }}
                </p>

                <p class="mt-2 font-bold">
                    From Rs. {{ number_format($product->specifications->min('price'), 2) }}
                </p>

                <a
                    href="{{ route('products.show', $product) }}"
                    class="inline-block mt-3 text-indigo-600 hover:underline"
                >
                    View details →
                </a>
            </div>
        @empty
            <p>No products found.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>
