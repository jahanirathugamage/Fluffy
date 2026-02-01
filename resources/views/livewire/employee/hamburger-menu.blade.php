<div>
    <!-- resources\views\livewire\employee\hamburger-menu.blade.php -->
    {{-- Backdrop Overlay --}}
    @if($isOpen)
        <div
            wire:click="close"
            class="fixed inset-0 bg-black bg-opacity-50 z-30 transition-opacity duration-300"
        ></div>
    @endif

    {{-- Slide-in Menu Drawer --}}
    <div
        class="fixed top-0 bottom-0 left-0 w-full md:w-[430px] bg-[#4FB5D0] text-white z-40 transform transition-transform duration-200 ease-in-out {{ $isOpen ? 'translate-x-0' : '-translate-x-full' }} shadow-lg"
    >
        <div class="flex flex-col h-full">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-6 md:px-8 md:py-8 relative">
                {{-- Left Group: Close (X) + Logo (Desktop only) --}}
                <div class="flex items-center gap-4 z-10">
                    <button type="button" wire:click="close" aria-label="Close menu" class="hover:opacity-80 transition-opacity">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-8 h-8 fill-white">
                            <path d="M183.1 137.4C170.6 124.9 150.3 124.9 137.8 137.4C125.3 149.9 125.3 170.2 137.8 182.7L275.2 320L137.9 457.4C125.4 469.9 125.4 490.2 137.9 502.7C150.4 515.2 170.7 515.2 183.2 502.7L320.5 365.3L457.9 502.6C470.4 515.1 490.7 515.1 503.2 502.6C515.7 490.1 515.7 469.8 503.2 457.3L365.8 320L503.1 182.6C515.6 170.1 515.6 149.8 503.1 137.3C490.6 124.8 470.3 124.8 457.8 137.3L320.5 274.7L183.1 137.4z"/>
                        </svg>
                    </button>

                    {{-- Desktop Logo: Visible next to X --}}
                    <div class="hidden md:block">
                        <img src="{{ asset('assets/images/fluffy-logo.png') }}" alt="Fluffy" class="h-8">
                    </div>
                </div>

                {{-- Mobile Logo: Absolute Center --}}
                <div class="md:hidden absolute inset-x-0 top-0 bottom-0 flex items-center justify-center pointer-events-none">
                    <img src="{{ asset('assets/images/fluffy-logo.png') }}" alt="Fluffy" class="h-8">
                </div>

                {{-- IMPORTANT: No Profile button in drawer header (desktop screenshot shows profile on top navbar) --}}
                <div class="hidden md:block"></div>
            </div>

            {{-- Content --}}
            <div class="flex-1 overflow-y-auto px-8 py-4">
                {{-- DESKTOP MENU (Image 1 order): Dashboard -> Manage Orders -> Manage Products --}}
                <div class="hidden md:block">
                    <ul class="space-y-6">
                        {{-- Dashboard --}}
                        <li class="flex items-center justify-between group cursor-pointer" wire:click="close">
                            <a href="{{ route('dashboard') }}" class="block text-white font-[Montserrat] text-[20px] font-medium">
                                Dashboard
                            </a>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-5 h-5 fill-white">
                                <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                            </svg>
                        </li>

                        {{-- Manage Orders --}}
                        @can('manage-orders')
                            <li class="flex items-center justify-between group cursor-pointer" wire:click="close">
                                <a href="{{ route('employee.orders') }}" class="block text-white font-[Montserrat] text-[20px] font-medium">
                                    Manage Orders
                                </a>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-5 h-5 fill-white">
                                    <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                                </svg>
                            </li>
                        @endcan

                        {{-- Manage Products --}}
                        @can('view-dashboard')
                            <li class="flex items-center justify-between group cursor-pointer" wire:click="close">
                                <a href="{{ route('employee.manage-products') }}" class="block text-white font-[Montserrat] text-[20px] font-medium">
                                    Manage Products
                                </a>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-5 h-5 fill-white">
                                    <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                                </svg>
                            </li>
                        @endcan
                    </ul>
                </div>

                {{-- MOBILE MENU (Image 2 order): Dashboard -> Manage Products -> Manage Orders --}}
                <div class="md:hidden">
                    <ul class="space-y-6">
                        {{-- Dashboard --}}
                        <li class="flex items-center justify-between group cursor-pointer" wire:click="close">
                            <a href="{{ route('dashboard') }}" class="block text-white font-[Montserrat] text-[20px] font-medium">
                                Dashboard
                            </a>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-5 h-5 fill-white">
                                <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                            </svg>
                        </li>

                        {{-- Manage Products --}}
                        @can('view-dashboard')
                            <li class="flex items-center justify-between group cursor-pointer" wire:click="close">
                                <a href="{{ route('employee.manage-products') }}" class="block text-white font-[Montserrat] text-[20px] font-medium">
                                    Manage Products
                                </a>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-5 h-5 fill-white">
                                    <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                                </svg>
                            </li>
                        @endcan

                        {{-- Manage Orders --}}
                        @can('manage-orders')
                            <li class="flex items-center justify-between group cursor-pointer" wire:click="close">
                                <a href="{{ route('employee.orders') }}" class="block text-white font-[Montserrat] text-[20px] font-medium">
                                    Manage Orders
                                </a>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-5 h-5 fill-white">
                                    <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                                </svg>
                            </li>
                        @endcan
                    </ul>
                </div>
            </div>

            {{-- Bottom white area --}}
            {{-- Desktop: Logout only (Image 1) --}}
            <div class="hidden md:flex bg-white w-full px-8 py-10 items-center justify-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="w-[120px] h-[40px] border-2 border-black bg-white text-black font-[Montserrat] text-[16px] flex items-center justify-center hover:font-bold"
                    >
                        Logout
                    </button>
                </form>
            </div>

            {{-- Mobile: Profile + Logout (Image 2) --}}
            <div class="md:hidden bg-white w-full px-8 py-10 flex flex-col gap-6 items-center justify-center">
                <a href="{{ route('profile.show') }}"
                   wire:click="close"
                   class="w-[160px] h-[48px] border-2 border-black bg-white text-black font-[Montserrat] text-[20px] flex items-center justify-center hover:font-bold">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="w-[160px] h-[48px] border-2 border-black bg-white text-black font-[Montserrat] text-[20px] flex items-center justify-center hover:font-bold"
                    >
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
