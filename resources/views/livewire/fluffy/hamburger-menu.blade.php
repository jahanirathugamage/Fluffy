<div>
    {{-- Backdrop Overlay --}}
    @if($isOpen)
        <div 
            wire:click="close"
            class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity duration-300"
        ></div>
    @endif

    {{-- Slide-in Menu Drawer --}}
    <div 
        class="fixed inset-y-0 left-0 w-72 bg-[#4FB5D0] text-white z-50 transform transition-transform duration-200 ease-in-out {{ $isOpen ? 'translate-x-0' : '-translate-x-full' }} shadow-lg"
    >
        <div class="p-4 flex flex-col h-full">
            {{-- Header with Logo and Close Button --}}
            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('home') }}">
                    <span class="text-white font-bold text-2xl font-[Montserrat]">fluffy</span>
                </a>
                <button wire:click="close">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-8 h-8 fill-white">
                        <path d="M183.1 137.4C170.6 124.9 150.3 124.9 137.8 137.4C125.3 149.9 125.3 170.2 137.8 182.7L275.2 320L137.9 457.4C125.4 469.9 125.4 490.2 137.9 502.7C150.4 515.2 170.7 515.2 183.2 502.7L320.5 365.3L457.9 502.6C470.4 515.1 490.7 515.1 503.2 502.6C515.7 490.1 515.7 469.8 503.2 457.3L365.8 320L503.1 182.6C515.6 170.1 515.6 149.8 503.1 137.3C490.6 124.8 470.3 124.8 457.8 137.3L320.5 274.7L183.1 137.4z"/>
                    </svg>
                </button>
            </div>

            {{-- Navigation Content (scrollable) --}}
            <div class="flex-1 overflow-y-auto">
                {{-- LEVEL 1: Main Menu --}}
                @if($currentLevel === 'main')
                    <ul class="space-y-4">
                        <li class="flex items-center justify-between">
                            <a href="{{ route('home') }}" wire:click="close" class="block text-white font-[Montserrat] text-[16px] font-medium hover:font-bold">
                                Home
                            </a>
                        </li>
                        <li class="flex items-center justify-between">
                            <a href="{{ route('products.index') }}" wire:click="close" class="block text-white font-[Montserrat] text-[16px] font-medium hover:font-bold">
                                Shop All
                            </a>
                        </li>
                        <li class="flex items-center justify-between">
                            <button wire:click="navigateTo('cats', 'cats')" class="block text-white font-[Montserrat] text-[16px] font-medium hover:font-bold text-left">
                                Cats
                            </button>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-white">
                                <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                            </svg>
                        </li>
                        <li class="flex items-center justify-between">
                            <button wire:click="navigateTo('dogs', 'dogs')" class="block text-white font-[Montserrat] text-[16px] font-medium hover:font-bold text-left">
                                Dogs
                            </button>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-white">
                                <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                            </svg>
                        </li>
                        <li class="flex items-center justify-between">
                            <button wire:click="navigateTo('otherPets')" class="block text-white font-[Montserrat] text-[16px] font-medium hover:font-bold text-left">
                                Other Pets
                            </button>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-white">
                                <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                            </svg>
                        </li>
                        <li class="flex items-center justify-between">
                            <a href="{{ route('products.index', ['sustainable' => 1]) }}" wire:click="close" class="block text-white font-[Montserrat] text-[16px] font-medium hover:font-bold">
                                Sustainable Products
                            </a>
                        </li>
                        <li class="flex items-center justify-between">
                            <a href="{{ route('orders.index') }}" wire:click="close" class="block text-white font-[Montserrat] text-[16px] font-medium hover:font-bold">
                                Orders
                            </a>
                        </li>
                    </ul>
                @endif

                {{-- LEVEL 2: Other Pets --}}
                @if($currentLevel === 'otherPets')
                    {{-- Back Button --}}
                    <div class="mb-6">
                        <button wire:click="goBack" class="flex items-center gap-2 text-white font-[Montserrat] text-[16px] font-medium hover:font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-white">
                                <path d="M168.9 342.6C156.4 330.1 156.4 309.8 168.9 297.3L360.9 105.3C373.4 92.8 393.7 92.8 406.2 105.3C418.7 117.8 418.7 138.1 406.2 150.6L236.8 320L406.1 489.4C418.6 501.9 418.6 522.2 406.1 534.7C393.6 547.2 373.3 547.2 360.8 534.7L168.8 342.7z"/>
                            </svg>
                            Other Pets
                        </button>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex items-center justify-between">
                            <button wire:click="navigateTo('rabbit', 'rabbit')" class="block text-white font-[Montserrat] text-[16px] font-medium hover:font-bold text-left">
                                Rabbit
                            </button>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-white">
                                <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                            </svg>
                        </li>
                        <li class="flex items-center justify-between">
                            <button wire:click="navigateTo('hamster', 'hamster')" class="block text-white font-[Montserrat] text-[16px] font-medium hover:font-bold text-left">
                                Hamster
                            </button>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-white">
                                <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                            </svg>
                        </li>
                    </ul>
                @endif

                {{-- LEVEL 3: Animal Categories (Cats, Dogs, Rabbit, Hamster) --}}
                @if(in_array($currentLevel, ['cats', 'dogs', 'rabbit', 'hamster']))
                    {{-- Back Button with Animal Name --}}
                    <div class="mb-6">
                        <button wire:click="goBack" class="flex items-center gap-2 text-white font-[Montserrat] text-[16px] font-medium hover:font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-white">
                                <path d="M168.9 342.6C156.4 330.1 156.4 309.8 168.9 297.3L360.9 105.3C373.4 92.8 393.7 92.8 406.2 105.3C418.7 117.8 418.7 138.1 406.2 150.6L236.8 320L406.1 489.4C418.6 501.9 418.6 522.2 406.1 534.7C393.6 547.2 373.3 547.2 360.8 534.7L168.8 342.7z"/>
                            </svg>
                            {{ ucfirst($animalContext) }}
                        </button>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex items-center justify-between">
                            <button wire:click="goToProducts('{{ $animalContext }}')" class="block text-white font-[Montserrat] text-[16px] font-medium hover:font-bold text-left">
                                Shop All
                            </button>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-white">
                                <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                            </svg>
                        </li>
                        <li class="flex items-center justify-between">
                            <button wire:click="goToProducts('{{ $animalContext }}', 'accessories')" class="block text-white font-[Montserrat] text-[16px] font-medium hover:font-bold text-left">
                                Accessories
                            </button>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-white">
                                <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                            </svg>
                        </li>
                        <li class="flex items-center justify-between">
                            <button wire:click="goToProducts('{{ $animalContext }}', 'food')" class="block text-white font-[Montserrat] text-[16px] font-medium hover:font-bold text-left">
                                Food
                            </button>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-white">
                                <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                            </svg>
                        </li>
                        <li class="flex items-center justify-between">
                            <button wire:click="goToProducts('{{ $animalContext }}', 'grooming')" class="block text-white font-[Montserrat] text-[16px] font-medium hover:font-bold text-left">
                                Grooming
                            </button>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-white">
                                <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                            </svg>
                        </li>
                        <li class="flex items-center justify-between">
                            <button wire:click="goToProducts('{{ $animalContext }}', 'sustainable')" class="block text-white font-[Montserrat] text-[16px] font-medium hover:font-bold text-left">
                                Sustainable
                            </button>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-white">
                                <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                            </svg>
                        </li>
                        <li class="flex items-center justify-between">
                            <button wire:click="goToProducts('{{ $animalContext }}', 'toys')" class="block text-white font-[Montserrat] text-[16px] font-medium hover:font-bold text-left">
                                Toys
                            </button>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-white">
                                <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                            </svg>
                        </li>
                    </ul>
                @endif
            </div>

            {{-- Bottom Profile and Logout Buttons (Mobile) --}}
            <div class="mt-8 md:hidden w-full pt-8 border-t border-white/30">
                <div class="bg-white p-4 flex flex-col items-center justify-center gap-3 -mx-4 -mb-4">
                    <a href="{{ route('profile') }}" 
                        wire:click="close"
                        class="w-[90px] h-[35px] border-2 border-black bg-white text-black font-[Montserrat] text-[16px] flex items-center justify-center hover:font-bold">
                        Profile
                    </a>
                    @auth
                        <button 
                            wire:click="logout"
                            class="w-[90px] h-[35px] border-2 border-black bg-white text-black font-[Montserrat] text-[16px] flex items-center justify-center hover:font-bold">
                            Logout
                        </button>
                    @else
                        <a 
                            href="{{ route('login') }}"
                            wire:click="close"
                            class="w-[90px] h-[35px] border-2 border-black bg-white text-black font-[Montserrat] text-[16px] flex items-center justify-center hover:font-bold">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
