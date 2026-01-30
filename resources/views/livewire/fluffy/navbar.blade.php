<nav class="flex items-center justify-between bg-[#4FB5D0] px-[58px] py-3 font-['Montserrat']">
    {{-- Left --}}
    <div class="flex items-center gap-6">
        <button wire:click="openMobile" class="w-8 h-8">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-8 h-8 fill-white">
                <path d="M96 160C96 142.3 110.3 128 128 128L512 128C529.7 128 544 142.3 544 160C544 177.7 529.7 192 512 192L128 192C110.3 192 96 177.7 96 160zM96 320C96 302.3 110.3 288 128 288L512 288C529.7 288 544 302.3 544 320C544 337.7 529.7 352 512 352L128 352C110.3 352 96 337.7 96 320zM544 480C544 497.7 529.7 512 512 512L128 512C110.3 512 96 497.7 96 480C96 462.3 110.3 448 128 448L512 448C529.7 448 544 462.3 544 480z"/>
            </svg>
        </button>

        <a href="{{ route('landing') }}">
            <img src="{{ asset('assets/images/fluffy-logo.png') }}" alt="Fluffy Logo" class="h-10">
        </a>
    </div>

    {{-- Mobile Sidebar --}}
    <div class="fixed top-0 left-0 w-72 h-full bg-[#4FB5D0] transition-transform duration-200 z-50 shadow-lg
        {{ $mobileOpen ? 'translate-x-0' : '-translate-x-full' }}">
        <div class="p-4">
            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('landing') }}">
                    <img src="{{ asset('assets/images/fluffy-logo.png') }}" alt="Fluffy Logo" class="h-10">
                </a>
                <button wire:click="closeMobile">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-8 h-8 fill-white">
                        <path d="M183.1 137.4C170.6 124.9 150.3 124.9 137.8 137.4C125.3 149.9 125.3 170.2 137.8 182.7L275.2 320L137.9 457.4C125.4 469.9 125.4 490.2 137.9 502.7C150.4 515.2 170.7 515.2 183.2 502.7L320.5 365.3L457.9 502.6C470.4 515.1 490.7 515.1 503.2 502.6C515.7 490.1 515.7 469.8 503.2 457.3L365.8 320L503.1 182.6C515.6 170.1 515.6 149.8 503.1 137.3C490.6 124.8 470.3 124.8 457.8 137.3L320.5 274.7L183.1 137.4z"/>
                    </svg>
                </button>
            </div>

            @php
                $mainMenu = [
                    route('landing') => 'Home',
                    route('cats') => 'Cats',
                    route('dogs') => 'Dogs',
                    route('rabbits') => 'Rabbits',
                    route('hamsters') => 'Hamsters',
                    route('seasonal') => 'Seasonal Boxes',
                ];
            @endphp

            <ul class="space-y-4">
                @foreach($mainMenu as $link => $label)
                    <li class="flex items-center justify-between">
                        <a href="{{ $link }}" class="block text-white text-[16px] font-medium hover:font-bold">
                            {{ $label }}
                        </a>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-white">
                            <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                        </svg>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Logout button at bottom of sidebar --}}
        <div class="absolute bottom-0 left-0 w-full h-[119px] bg-white flex items-center justify-center">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-[90px] h-[35px] border-[2px] border-black bg-white text-black text-[16px] flex items-center justify-center hover:font-bold">
                    Logout
                </button>
            </form>
        </div>
    </div>

    {{-- Right --}}
    <div class="flex items-center gap-6">
        <div class="w-8 h-8">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-8 h-8 fill-white cursor-pointer">
                <path d="M480 272C480 317.9 465.1 360.3 440 394.7L566.6 521.4C579.1 533.9 579.1 554.2 566.6 566.7C554.1 579.2 533.8 579.2 521.3 566.7L394.7 440C360.3 465.1 317.9 480 272 480C157.1 480 64 386.9 64 272C64 157.1 157.1 64 272 64C386.9 64 480 157.1 480 272zM272 416C351.5 416 416 351.5 416 272C416 192.5 351.5 128 272 128C192.5 128 128 192.5 128 272C128 351.5 192.5 416 272 416z"/>
            </svg>
        </div>

        <button wire:click="openCart" class="w-8 h-8 relative">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-8 h-8 fill-white">
                <path d="M24 48C10.7 48 0 58.7 0 72C0 85.3 10.7 96 24 96L69.3 96C73.2 96 76.5 98.8 77.2 102.6L129.3 388.9C135.5 423.1 165.3 448 200.1 448L456 448C469.3 448 480 437.3 480 424C480 410.7 469.3 400 456 400L200.1 400C188.5 400 178.6 391.7 176.5 380.3L171.4 352L475 352C505.8 352 532.2 330.1 537.9 299.8L568.9 133.9C572.6 114.2 557.5 96 537.4 96L124.7 96L124.3 94C119.5 67.4 96.3 48 69.2 48L24 48z"/>
            </svg>
            <span class="absolute top-0 right-0 w-4 h-4 bg-red-600 rounded-full text-[10px] text-white flex items-center justify-center">
                0
            </span>
        </button>

        <a href="{{ route('profile.show') }}"
           class="hidden md:flex w-[90px] h-[32px] border-[2px] border-black bg-white text-black text-[16px] items-center justify-center hover:font-bold">
            Profile
        </a>
    </div>

    {{-- Cart Overlay --}}
    <div class="fixed inset-0 bg-black bg-opacity-50 z-40 {{ $cartOpen ? '' : 'hidden' }}" wire:click="closeCart"></div>

    {{-- Cart Sidebar --}}
    <div class="fixed top-0 right-0 w-80 md:w-96 h-full bg-white transition-transform duration-300 z-50 shadow-lg flex flex-col
        {{ $cartOpen ? 'translate-x-0' : 'translate-x-full' }}">
        <div class="p-6 flex flex-col h-full">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold">Your Cart</h2>
                <button wire:click="closeCart">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-6 h-6 fill-black">
                        <path d="M183.1 137.4C170.6 124.9 150.3 124.9 137.8 137.4C125.3 149.9 125.3 170.2 137.8 182.7L275.2 320L137.9 457.4C125.4 469.9 125.4 490.2 137.9 502.7C150.4 515.2 170.7 515.2 183.2 502.7L320.5 365.3L457.9 502.6C470.4 515.1 490.7 515.1 503.2 502.6C515.7 490.1 515.7 469.8 503.2 457.3L365.8 320L503.1 182.6C515.6 170.1 515.6 149.8 503.1 137.3C490.6 124.8 470.3 124.8 457.8 137.3L320.5 274.7L183.1 137.4z"/>
                    </svg>
                </button>
            </div>

            {{-- Placeholder: implement cart as its own Livewire component next --}}
            <div class="flex-1 overflow-y-auto space-y-6 pr-2">
                <p class="text-sm text-gray-600">Cart items will be rendered via Livewire (session/DB), not localStorage.</p>
            </div>

            <div class="mt-6 flex flex-col gap-4">
                <div class="flex justify-between">
                    <span class="font-bold">Estimated total</span>
                    <span class="font-medium">LKR 0.00</span>
                </div>
                <span class="font-regular text-[10px] text-center">Taxes, Discounts and Shipping calculated at checkout</span>
                <button class="w-full bg-[#69A985] text-white font-bold py-3 border-2 border-black hover:bg-black transition">
                    CHECKOUT
                </button>
            </div>
        </div>
    </div>
</nav>
