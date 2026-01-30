<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-['Montserrat']">
    <h1 class="text-3xl font-bold mb-8">My Favorites</h1>

    @if($favorites->isEmpty())
        <div class="text-center py-20 bg-gray-50 border border-gray-200 rounded">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <p class="text-lg text-gray-500">You haven't added any favorites yet.</p>
            <a href="{{ route('products.index') }}" class="inline-block mt-4 text-[#4FB5D0] font-bold hover:underline">
                Browse Products
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($favorites as $fav)
                <div class="border-[3px] border-black p-4 bg-white flex flex-col justify-between h-full hover:shadow-lg transition-shadow">
                    
                    {{-- Product Image --}}
                    <div class="h-48 flex items-center justify-center mb-4">
                        <img src="{{ asset($fav->product->image_path) }}" 
                             alt="{{ $fav->product->name }}" 
                             class="max-h-full max-w-full object-contain">
                    </div>

                    {{-- Details --}}
                    <div class="text-center mb-4">
                        <h3 class="font-bold text-lg leading-tight mb-1">{{ $fav->product->name }}</h3>
                        @if($fav->specification)
                            <p class="text-sm text-gray-500 mb-1">{{ $fav->specification->name }}</p>
                            <p class="font-bold text-lg">LKR {{ number_format($fav->specification->price, 2) }}</p>
                        @elseif($fav->product->specifications->isNotEmpty())
                            <p class="font-bold text-lg">From LKR {{ number_format($fav->product->specifications->min('price'), 2) }}</p>
                        @else
                            <p class="text-gray-500">Unavailable</p>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col gap-2">
                         <button wire:click="moveToCart({{ $fav->id }})" 
                                 class="w-full py-2 bg-[#4FB5D0] text-white font-bold border-2 border-black hover:bg-black transition-colors uppercase text-sm">
                            Move to Cart
                        </button>
                        <button wire:click="remove({{ $fav->id }})" 
                                class="w-full py-2 bg-white text-gray-500 font-medium border-2 border-transparent hover:border-red-500 hover:text-red-500 transition-colors uppercase text-sm">
                            Remove
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
