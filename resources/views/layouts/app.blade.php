<!DOCTYPE html>
<!-- resources\views\layouts\app.blade.php -->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="bg-white">
            @auth
                @php
                    // Employees are authorized in routes via permission:view-dashboard.
                    // Some employee accounts may not have the 'employee' role, so rely on permission too.
                    $isEmployee = auth()->user()->hasRole('employee') || auth()->user()->can('view-dashboard');
                @endphp

                @if($isEmployee)
                    {{-- Employee Navigation --}}
                    @livewire('employee.navbar')
                    @livewire('employee.hamburger-menu')
                @else
                    {{-- Customer Navigation --}}
                    @livewire('fluffy.navbar')
                    @livewire('fluffy.hamburger-menu')
                    @livewire('cart.cart-drawer')
                @endif
            @endauth

            @guest
                {{-- Guest users (no nav here; your guest layout handles auth pages) --}}
            @endguest

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="p-0 m-0">
                {{ $slot }}
            </main>

            @auth
                @if(!auth()->user()->hasRole('employee') && !auth()->user()->can('view-dashboard'))
                    @livewire('fluffy.footer')
                @endif
            @endauth
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
