<div>
    <!-- resources\views\livewire\employee\navbar.blade.php -->
<nav class="flex items-center justify-between bg-[#4FB5D0] px-[58px] py-3">
    {{-- Left: Menu Icon and Logo --}}
    <div class="flex items-center gap-6">
        {{-- Menu Icon --}}
        <button wire:click="toggleSidebar" class="w-8 h-8" aria-label="Menu">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-8 h-8 fill-white">
                <path d="M96 160C96 142.3 110.3 128 128 128L512 128C529.7 128 544 142.3 544 160C544 177.7 529.7 192 512 192L128 192C110.3 192 96 177.7 96 160zM96 320C96 302.3 110.3 288 128 288L512 288C529.7 288 544 302.3 544 320C544 337.7 529.7 352 512 352L128 352C110.3 352 96 337.7 96 320zM544 480C544 497.7 529.7 512 512 512L128 512C110.3 512 96 497.7 96 480C96 462.3 110.3 448 128 448L512 448C529.7 448 544 462.3 544 480z"/>
            </svg>
        </button>

        {{-- Fluffy Logo --}}
        <a href="{{ route('employee.manage-products') }}">
            <img src="{{ asset('assets/images/fluffy-logo.png') }}" alt="Fluffy Logo" class="h-10">
        </a>
    </div>

    {{-- Right: Profile Button --}}
    <div class="flex items-center gap-6">
        <a href="{{ route('profile.show') }}" class="hidden md:flex w-[90px] h-[32px] border-[2px] border-black bg-white text-black font-[Montserrat] text-[16px] items-center justify-center hover:font-bold">
            Profile
        </a>
    </div>
</nav>

{{-- Sidebar Menu --}}
<div class="fixed top-0 left-0 w-72 h-full bg-[#4FB5D0] z-50 shadow-lg transform transition-transform duration-300 ease-in-out {{ $showSidebar ? 'translate-x-0' : '-translate-x-full' }}">
    <div class="p-4">
            {{-- Sidebar Header: Logo + Close Icon --}}
            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('employee.manage-products') }}">
                    <img src="{{ asset('assets/images/fluffy-logo.png') }}" alt="Fluffy Logo" class="h-10">
                </a>
                <button wire:click="closeSidebar" aria-label="Close menu">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-8 h-8 fill-white">
                        <path d="M183.1 137.4C170.6 124.9 150.3 124.9 137.8 137.4C125.3 149.9 125.3 170.2 137.8 182.7L275.2 320L137.9 457.4C125.4 469.9 125.4 490.2 137.9 502.7C150.4 515.2 170.7 515.2 183.2 502.7L320.5 365.3L457.9 502.6C470.4 515.1 490.7 515.1 503.2 502.6C515.7 490.1 515.7 469.8 503.2 457.3L365.8 320L503.1 182.6C515.6 170.1 515.6 149.8 503.1 137.3C490.6 124.8 470.3 124.8 457.8 137.3L320.5 274.7L183.1 137.4z"/>
                    </svg>
                </button>
            </div>

            {{-- Main Menu --}}
            <ul class="space-y-4">
                <li class="flex items-center justify-between">
                    <a href="{{ route('dashboard') }}" 
                       class="block text-white font-[Montserrat] text-[16px] font-medium hover:font-bold">
                        Dashboard
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-white">
                        <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                    </svg>
                </li>

                <li class="flex items-center justify-between">
                    <span class="block text-white font-[Montserrat] text-[16px] font-medium opacity-50">
                        Orders
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-white opacity-50">
                        <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                    </svg>
                </li>

                <li class="flex items-center justify-between">
                    <a href="{{ route('employee.manage-products') }}" 
                       class="block text-white font-[Montserrat] text-[16px] font-medium hover:font-bold">
                        Manage Products
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-white">
                        <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                    </svg>
                </li>

                <li class="flex items-center justify-between">
                    <a href="{{ route('profile.show') }}" 
                       class="block text-white font-[Montserrat] text-[16px] font-medium hover:font-bold">
                        Profile
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-white">
                        <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                    </svg>
                </li>
            </ul>
        </div>

        {{-- Bottom Profile/Logout Button (Mobile) --}}
        <div class="absolute bottom-0 left-0 w-full h-[119px] bg-white flex items-center justify-center">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-[90px] h-[35px] border-[2px] border-black bg-white text-black font-[Montserrat] text-[16px] flex items-center justify-center hover:font-bold">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Backdrop --}}
@if ($showSidebar)
    <div wire:click="closeSidebar" class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity duration-300"></div>
@endif
</div>
