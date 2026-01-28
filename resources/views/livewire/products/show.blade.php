<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 grid grid-cols-1 md:grid-cols-2 gap-8">
        <img src="{{ asset($product->image_path) }}" class="rounded shadow">

        <div>
            <h1 class="text-3xl font-bold">{{ $product->name }}</h1>
            <p class="text-gray-600">
                {{ $product->animal->name }} · {{ $product->category->name }}
            </p>

            <p class="mt-4">{{ $product->details }}</p>

            <div class="mt-6">
                <h3 class="font-semibold mb-2">Specifications</h3>
                <ul class="space-y-2">
                    @foreach($product->specifications as $spec)
                        <li class="border p-3 rounded">
                            {{ $spec->name }} —
                            Rs. {{ number_format($spec->price, 2) }}
                            ({{ $spec->stock }} in stock)
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
