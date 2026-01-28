<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Product Image -->
                        <div>
                            <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="rounded-lg shadow-lg w-full">
                        </div>

                        <!-- Product Details -->
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
                            
                            <div class="flex items-center gap-4 mb-6">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $product->animal->name }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    {{ $product->category->name }}
                                </span>
                            </div>

                            <div class="prose max-w-none mb-6">
                                <p class="text-gray-700">{{ $product->details }}</p>
                            </div>

                            <!-- Specifications -->
                            <div class="mt-8">
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">Available Specifications</h3>
                                <div class="space-y-3">
                                    @forelse($product->specifications as $spec)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-500 transition-colors">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <h4 class="font-medium text-gray-900">{{ $spec->name }}</h4>
                                                    <p class="text-sm text-gray-600 mt-1">Stock: {{ $spec->stock }} units</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-2xl font-bold text-gray-900">₹{{ number_format($spec->price, 2) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-gray-500">No specifications available for this product.</p>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Back Button -->
                            <div class="mt-8">
                                <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                                    ← Back to Products
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
