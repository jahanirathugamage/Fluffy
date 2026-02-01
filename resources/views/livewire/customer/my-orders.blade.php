<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
        {{-- Removed Search/Cart icons as requested --}}
    </div>

    {{-- Tabs --}}
    <div x-data="{ activeTab: @entangle('status') }" class="mb-8">
        <div class="flex space-x-4 border-b border-gray-200 overflow-x-auto pb-1"> {{-- Added overflow-x-auto for mobile safety --}}
            @foreach(['all', 'processing', 'shipped', 'delivered'] as $tab)
                <button 
                    @click="activeTab = '{{ $tab }}'; $wire.setStatus('{{ $tab }}')"
                    :class="{ 'border-black text-black': activeTab === '{{ $tab }}', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== '{{ $tab }}' }"
                    class="py-4 px-1 border-b-2 font-medium text-sm capitalize transition-colors duration-200 whitespace-nowrap"
                >
                    {{ $tab === 'all' ? 'All Orders' : $tab }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Order List --}}
    <div class="space-y-6">
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('message') }}
            </div>
        @endif

        @forelse ($orders as $order)
            <div class="bg-white border border-black p-6 flex flex-col md:flex-row justify-between items-center shadow-sm">
                <div class="flex items-center space-x-6 w-full md:w-auto">
                    {{-- Product Thumbnail --}}
                    @if($order->items->first() && $order->items->first()->product)
                         <img src="{{ $order->items->first()->product->image_path ? asset($order->items->first()->product->image_path) : asset('assets/images/placeholder.png') }}" 
                              class="w-20 h-24 object-contain rounded-md" alt="Product Image">
                    @endif
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Order#: {{ $order->id }}</h3>
                        <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                        @if($order->status === 'processing')
                             {{-- No extra text here for keeping consistency --}}
                        @elseif($order->status === 'shipped')
                             <p class="text-sm text-gray-700 mt-1">Estimated Delivery on {{ $order->delivery_expected_at ? $order->delivery_expected_at->format('d M Y') : 'N/A' }}</p>
                        @elseif($order->status === 'delivered')
                             <p class="text-sm text-gray-700 mt-1">Delivered on {{ $order->updated_at->format('d M Y') }}</p>
                        @endif
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-4 md:mt-0 flex flex-col space-y-2 w-full md:w-48">
                    <a href="{{ route('my-orders.show', $order) }}" class="w-full text-center bg-black text-white px-4 py-2 rounded-none text-sm font-medium hover:bg-gray-800 transition">
                        View Details
                    </a>
                    
                    @if($order->delivery_status === 'shipped' && now()->greaterThanOrEqualTo($order->delivery_expected_at))
                        <button wire:click="confirmOrder({{ $order->id }})" class="w-full text-center bg-emerald-500 text-white px-4 py-2 rounded-none text-sm font-medium hover:bg-emerald-600 transition">
                            Order Received
                        </button>
                    @elseif($order->delivery_status === 'shipped')
                        <span class="w-full text-center bg-[#4FB5D0] text-white px-4 py-2 rounded-none text-sm font-medium">
                            Shipped
                        </span>
                    @elseif($order->delivery_status === 'processing')
                        <span class="w-full text-center bg-gray-400 text-white px-4 py-2 rounded-none text-sm font-medium">
                            Processing
                        </span>
                     @elseif($order->delivery_status === 'delivered')
                        <span class="w-full text-center bg-[#6FAE8D] text-white px-4 py-2 rounded-none text-sm font-medium">
                            Order Received
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <p class="text-gray-500">You have no orders yet.</p>
                <a href="{{ route('products.index') }}" class="text-blue-500 hover:underline mt-2 inline-block">Start Shopping</a>
            </div>
        @endforelse
    </div>
</div>
