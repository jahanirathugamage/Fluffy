<div class="bg-[#4FB5D0] text-white relative z-50">
    <!-- resources\views\livewire\fluffy\navbar.blade.php -->
    {{-- Desktop Navbar --}}
    <div class="hidden md:flex items-center justify-between px-[58px] py-3">
        {{-- Left Section: Hamburger & Logo --}}
        <div class="flex items-center gap-6">
            {{-- Hamburger Icon --}}
            <button wire:click="$dispatch('openHamburgerMenu')" class="text-white hover:opacity-80 transition-opacity">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-8 h-8 fill-white">
                    <path d="M96 160C96 142.3 110.3 128 128 128L512 128C529.7 128 544 142.3 544 160C544 177.7 529.7 192 512 192L128 192C110.3 192 96 177.7 96 160zM96 320C96 302.3 110.3 288 128 288L512 288C529.7 288 544 302.3 544 320C544 337.7 529.7 352 512 352L128 352C110.3 352 96 337.7 96 320zM544 480C544 497.7 529.7 512 512 512L128 512C110.3 512 96 497.7 96 480C96 462.3 110.3 448 128 448L512 448C529.7 448 544 462.3 544 480z"/>
                </svg>
            </button>

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('assets/images/fluffy-logo.png') }}" alt="Fluffy" class="h-10">
            </a>
        </div>

        {{-- Right Section: Search, Cart, Profile --}}
        <div class="flex items-center gap-6">
            {{-- Search Section (DESKTOP): icon stays, input slides out from icon --}}
            <div class="relative flex items-center">
                <form wire:submit.prevent="search" class="flex items-center">
                    <div
                        class="overflow-hidden transition-[width,opacity,transform] duration-300 ease-out origin-right
                               {{ $searchExpanded ? 'w-72 opacity-100 translate-x-0' : 'w-0 opacity-0 translate-x-2' }}"
                    >
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="searchQuery"
                            placeholder="Search"
                            class="w-72 px-4 py-2 border-2 border-black text-gray-800 focus:outline-none"
                            @if($searchExpanded) autofocus @endif
                        >

                        @if(count($searchResults) > 0)
                            <div class="absolute top-full left-0 w-72 bg-white border-2 border-t-0 border-black shadow-lg z-50">
                                @foreach($searchResults as $result)
                                    <a href="{{ route('products.show', $result) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ $result->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <button
                        type="button"
                        wire:click="toggleSearch"
                        class="ml-2 w-8 h-8 flex items-center justify-center hover:opacity-80 transition-opacity"
                        aria-label="Toggle search"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-8 h-8 fill-white">
                            <path d="M480 272C480 317.9 465.1 360.3 440 394.7L566.6 521.4C579.1 533.9 579.1 554.2 566.6 566.7C554.1 579.2 533.8 579.2 521.3 566.7L394.7 440C360.3 465.1 317.9 480 272 480C157.1 480 64 386.9 64 272C64 157.1 157.1 64 272 64C386.9 64 480 157.1 480 272zM272 416C351.5 416 416 351.5 416 272C416 192.5 351.5 128 272 128C192.5 128 128 192.5 128 272C128 351.5 192.5 416 272 416z"/>
                        </svg>
                    </button>

                    <button type="submit" class="hidden" aria-hidden="true" tabindex="-1"></button>
                </form>
            </div>

            {{-- Cart Icon with Badge --}}
            <button wire:click="$dispatch('openCart')" class="w-8 h-8 relative">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-8 h-8 fill-white">
                    <path d="M24 48C10.7 48 0 58.7 0 72C0 85.3 10.7 96 24 96L69.3 96C73.2 96 76.5 98.8 77.2 102.6L129.3 388.9C135.5 423.1 165.3 448 200.1 448L456 448C469.3 448 480 437.3 480 424C480 410.7 469.3 400 456 400L200.1 400C188.5 400 178.6 391.7 176.5 380.3L171.4 352L475 352C505.8 352 532.2 330.1 537.9 299.8L568.9 133.9C572.6 114.2 557.5 96 537.4 96L124.7 96L124.3 94C119.5 67.4 96.3 48 69.2 48L24 48zM208 576C234.5 576 256 554.5 256 528C256 501.5 234.5 480 208 480C181.5 480 160 501.5 160 528C160 554.5 181.5 576 208 576zM432 576C458.5 576 480 554.5 480 528C480 501.5 458.5 480 432 480C405.5 480 384 501.5 384 528C384 554.5 405.5 576 432 576z"/>
                </svg>
                @if($cartCount > 0)
                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-600 rounded-full text-[10px] text-white flex items-center justify-center font-bold">
                        {{ $cartCount }}
                    </span>
                @endif
            </button>

            {{-- Profile Button --}}
            <a href="{{ route('profile.show') }}" class="w-[90px] h-[32px] border-2 border-black bg-white text-black font-[Montserrat] text-[16px] flex items-center justify-center hover:font-bold">
                Profile
            </a>
        </div>
    </div>

    {{-- Mobile Navbar --}}
    <div class="md:hidden flex items-center justify-between px-6 py-3">
        {{-- Left Section: Hamburger/X & Logo --}}
        <div class="flex items-center gap-4">
            @if($hamburgerOpen)
                {{-- Close (X) --}}
                <button
                    type="button"
                    wire:click="$dispatch('closeHamburgerMenu')"
                    class="text-white hover:opacity-80 transition-opacity"
                    aria-label="Close menu"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-8 h-8 fill-white">
                        <path d="M183.1 137.4C170.6 124.9 150.3 124.9 137.8 137.4C125.3 149.9 125.3 170.2 137.8 182.7L275.2 320L137.9 457.4C125.4 469.9 125.4 490.2 137.9 502.7C150.4 515.2 170.7 515.2 183.2 502.7L320.5 365.3L457.9 502.6C470.4 515.1 490.7 515.1 503.2 502.6C515.7 490.1 515.7 469.8 503.2 457.3L365.8 320L503.1 182.6C515.6 170.1 515.6 149.8 503.1 137.3C490.6 124.8 470.3 124.8 457.8 137.3L320.5 274.7L183.1 137.4z"/>
                    </svg>
                </button>
            @else
                {{-- Hamburger --}}
                <button
                    type="button"
                    wire:click="$dispatch('openHamburgerMenu')"
                    class="text-white hover:opacity-80 transition-opacity"
                    aria-label="Open menu"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-8 h-8 fill-white">
                        <path d="M96 160C96 142.3 110.3 128 128 128L512 128C529.7 128 544 142.3 544 160C544 177.7 529.7 192 512 192L128 192C110.3 192 96 177.7 96 160zM96 320C96 302.3 110.3 288 128 288L512 288C529.7 288 544 302.3 544 320C544 337.7 529.7 352 512 352L128 352C110.3 352 96 337.7 96 320zM544 480C544 497.7 529.7 512 512 512L128 512C110.3 512 96 497.7 96 480C96 462.3 110.3 448 128 448L512 448C529.7 448 544 462.3 544 480z"/>
                    </svg>
                </button>
            @endif

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('assets/images/fluffy-logo.png') }}" alt="Fluffy" class="h-8">
            </a>
        </div>

        {{-- Right Section: Search & Cart --}}
        <div class="flex items-center gap-4">
            <button wire:click="toggleSearch" class="w-8 h-8" aria-label="Open search">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-8 h-8 fill-white">
                    <path d="M480 272C480 317.9 465.1 360.3 440 394.7L566.6 521.4C579.1 533.9 579.1 554.2 566.6 566.7C554.1 579.2 533.8 579.2 521.3 566.7L394.7 440C360.3 465.1 317.9 480 272 480C157.1 480 64 386.9 64 272C64 157.1 157.1 64 272 64C386.9 64 480 157.1 480 272zM272 416C351.5 416 416 351.5 416 272C416 192.5 351.5 128 272 128C192.5 128 128 192.5 128 272C128 351.5 192.5 416 272 416z"/>
                </svg>
            </button>

            <button wire:click="$dispatch('openCart')" class="w-8 h-8 relative">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-8 h-8 fill-white">
                    <path d="M24 48C10.7 48 0 58.7 0 72C0 85.3 10.7 96 24 96L69.3 96C73.2 96 76.5 98.8 77.2 102.6L129.3 388.9C135.5 423.1 165.3 448 200.1 448L456 448C469.3 448 480 437.3 480 424C480 410.7 469.3 400 456 400L200.1 400C188.5 400 178.6 391.7 176.5 380.3L171.4 352L475 352C505.8 352 532.2 330.1 537.9 299.8L568.9 133.9C572.6 114.2 557.5 96 537.4 96L124.7 96L124.3 94C119.5 67.4 96.3 48 69.2 48L24 48zM208 576C234.5 576 256 554.5 256 528C256 501.5 234.5 480 208 480C181.5 480 160 501.5 160 528C160 554.5 181.5 576 208 576zM432 576C458.5 576 480 554.5 480 528C480 501.5 458.5 480 432 480C405.5 480 384 501.5 384 528C384 554.5 405.5 576 432 576z"/>
                </svg>
                @if($cartCount > 0)
                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-600 rounded-full text-[10px] text-white flex items-center justify-center font-bold">
                        {{ $cartCount }}
                    </span>
                @endif
            </button>
        </div>
    </div>

    {{-- Mobile Search Overlay + Panel --}}
    @if($searchExpanded)
        <div class="fixed inset-0 z-[9999] md:hidden">
            <button
                type="button"
                wire:click="toggleSearch"
                class="absolute inset-0 bg-black/60"
                aria-label="Close search overlay"
            ></button>

            <div class="absolute top-0 left-0 right-0 bg-white shadow-lg border-b-2 border-black animate-slide-down">
                <div class="px-4 py-4">
                    <form wire:submit.prevent="search" class="flex items-center gap-2">
                        <div class="flex-1 relative">
                             <input
                                type="text"
                                wire:model.live.debounce.300ms="searchQuery"
                                placeholder="Search"
                                class="w-full px-4 py-2 border-2 border-black text-gray-800 focus:outline-none"
                                autofocus
                            >
                            @if(count($searchResults) > 0)
                                <div class="absolute top-full left-0 w-full bg-white border-2 border-t-0 border-black shadow-lg z-50">
                                    @foreach($searchResults as $result)
                                        <a href="{{ route('products.show', $result) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            {{ $result->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        
                        <button type="submit" class="bg-black text-white px-4 py-2 border-2 border-black hover:bg-gray-800 transition-colors" aria-label="Search">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-5 h-5 fill-white">
                                <path d="M480 272C480 317.9 465.1 360.3 440 394.7L566.6 521.4C579.1 533.9 579.1 554.2 566.6 566.7C554.1 579.2 533.8 579.2 521.3 566.7L394.7 440C360.3 465.1 317.9 480 272 480C157.1 480 64 386.9 64 272C64 157.1 157.1 64 272 64C386.9 64 480 157.1 480 272zM272 416C351.5 416 416 351.5 416 272C416 192.5 351.5 128 272 128C192.5 128 128 192.5 128 272C128 351.5 192.5 416 272 416z"/>
                            </svg>
                        </button>
                        <button type="button" wire:click="toggleSearch" class="text-gray-600 hover:text-gray-800 transition-colors" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-6 h-6 fill-black">
                                <path d="M183.1 137.4C170.6 124.9 150.3 124.9 137.8 137.4C125.3 149.9 125.3 170.2 137.8 182.7L275.2 320L137.9 457.4C125.4 469.9 125.4 490.2 137.9 502.7C150.4 515.2 170.7 515.2 183.2 502.7L320.5 365.3L457.9 502.6C470.4 515.1 490.7 515.1 503.2 502.6C515.7 490.1 515.7 469.8 503.2 457.3L365.8 320L503.1 182.6C515.6 170.1 515.6 149.8 503.1 137.3C490.6 124.8 470.3 124.8 457.8 137.3L320.5 274.7L183.1 137.4z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <style>
        @keyframes slide-down {
            from { transform: translateY(-100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .animate-slide-down {
            animation: slide-down 0.25s ease-out;
        }
    </style>
</div>
