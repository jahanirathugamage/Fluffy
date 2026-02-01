<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Manage Orders</h1>
    </div>

    {{-- Tabs --}}
    <div x-data="{ activeTab: @entangle('status') }" class="mb-8">
        <div class="flex space-x-4 border-b border-gray-200">
            @foreach(['all', 'processing', 'shipped', 'delivered'] as $tab)
                <button 
                    @click="activeTab = '{{ $tab }}'; $wire.setStatus('{{ $tab }}')"
                    :class="{ 'border-black text-black': activeTab === '{{ $tab }}', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== '{{ $tab }}' }"
                    class="py-4 px-1 border-b-2 font-medium text-sm capitalize transition-colors duration-200"
                >
                    {{ $tab === 'all' ? 'All Orders' : $tab }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Order List with Sliding Transition --}}
    <div class="space-y-6" 
         x-show="true"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-x-4"
         x-transition:enter-end="opacity-100 transform translate-x-0"
    >
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('message') }}
            </div>
        @endif

        @forelse ($orders as $order)
            <div class="bg-white border border-black p-6 flex flex-col md:flex-row justify-between items-center shadow-sm">
                <div class="flex items-center space-x-4 w-full md:w-auto">
                    {{-- Product Thumbnail (First item) --}}
                    @if($order->items->first() && $order->items->first()->product)
                          <img src="{{ $order->items->first()->product->image_path ? asset($order->items->first()->product->image_path) : asset('assets/images/placeholder.png') }}" 
                               class="w-16 h-16 object-cover" alt="Product Image">
                    @endif
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Order#: {{ $order->id }}</h3>
                        <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-4 md:mt-0 flex flex-col space-y-2 w-full md:w-48">
                    <a href="{{ route('employee.orders.show', $order) }}" class="nav-link w-full text-center bg-black text-white px-4 py-2 rounded-none text-sm font-medium hover:bg-gray-800 transition">
                        View Details
                    </a>
                    
                    @if($order->delivery_status === 'processing' || $order->delivery_status === 'pending')
                        <button wire:click="shipOrder({{ $order->id }})" class="w-full text-center border-2 border-black text-black px-4 py-2 rounded-none text-sm font-medium hover:bg-gray-100 transition">
                            Ship
                        </button>
                    @elseif($order->delivery_status === 'shipped')
                        <span class="w-full text-center bg-[#4FB5D0] text-white px-4 py-2 rounded-none text-sm font-medium">
                            Shipped
                        </span>
                    @elseif($order->delivery_status === 'delivered')
                        <span class="w-full text-center bg-[#6FAE8D] text-white px-4 py-2 rounded-none text-sm font-medium">
                            Delivered
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <p class="text-gray-500">No orders found.</p>
            </div>
        @endforelse
    </div>
</div>
