<x-guest-layout>
    <x-authentication-card>
        <!-- Mobile Only: Logo + Heading at top -->
        <div class="sm:hidden mb-8 flex flex-col items-center">
            <img
                src="{{ asset('assets/images/fluffy-blue.png') }}"
                alt="Fluffy"
                class="h-16 w-auto mb-5"
            />
            <p class="text-lg font-medium text-gray-900 text-center">
                Log in to Fluffy!
            </p>
        </div>

        <!-- Mobile Only: Illustration below heading -->
        <div class="sm:hidden flex justify-center items-center mb-8">
            <img
                src="{{ asset('assets/images/suitcase.png') }}"
                alt="Cat and Dog with Shopping Cart"
                class="w-64 h-auto"
            />
        </div>

        <!-- Desktop: 2 columns (form left, illustration right) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 sm:gap-12 items-center">
            <!-- Left Column -->
            <div class="order-2 sm:order-1 sm:pr-2 flex flex-col justify-center">
                <!-- Logo + Heading (Desktop Only) -->
                <div class="hidden sm:flex mb-10 flex-col items-center">
                    <img
                        src="{{ asset('assets/images/fluffy-blue.png') }}"
                        alt="Fluffy"
                        class="h-20 w-auto mb-6"
                    />
                    <p class="text-xl font-medium text-gray-900 text-center">
                        Log in to Fluffy!
                    </p>
                </div>

                <x-validation-errors class="mb-4" />

                @session('status')
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ $value }}
                    </div>
                @endsession

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-5">
                        <input
                            id="email"
                            class="block w-full px-4 py-3 border-2 border-black rounded-none focus:outline-none focus:ring-0 focus:border-black"
                            type="email"
                            name="email"
                            placeholder="Email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                        />
                    </div>

                    <div class="mb-4">
                        <input
                            id="password"
                            class="block w-full px-4 py-3 border-2 border-black rounded-none focus:outline-none focus:ring-0 focus:border-black"
                            type="password"
                            name="password"
                            placeholder="Password"
                            required
                            autocomplete="current-password"
                        />
                    </div>

                    @if (Route::has('password.request'))
                        <div class="text-center mb-5">
                            <a class="underline text-sm text-gray-700 hover:text-gray-900" href="{{ route('password.request') }}">
                                Forgot Password?
                            </a>
                        </div>
                    @endif

                    <button
                        type="submit"
                        class="w-full bg-[#5BC0DE] hover:bg-[#4AA9C5] text-white font-semibold py-3 px-4 border-2 border-black transition duration-150 ease-in-out"
                    >
                        Login
                    </button>
                </form>

                <div class="text-center mt-7 sm:mt-8">
                    <p class="text-sm text-gray-700 mb-3">Don't have an account yet?</p>

                    @if (Route::has('register'))
                        <a
                            href="{{ route('register') }}"
                            class="block w-full bg-white hover:bg-gray-50 text-gray-900 font-semibold py-3 px-4 border-2 border-black mb-3 transition duration-150 ease-in-out"
                        >
                            Create an Account
                        </a>
                    @endif

                    <a
                        href="{{ route('google.login') }}"
                        class="w-full inline-flex items-center justify-center gap-3 px-4 py-3 bg-white hover:bg-gray-50 border-2 border-black font-semibold text-gray-900 transition duration-150 ease-in-out"
                    >
                        <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        <span>Sign in with Google</span>
                    </a>
                </div>
            </div>

            <!-- Right Column: Illustration (Desktop Only) -->
            <div class="hidden sm:flex justify-center items-center sm:pl-2">
                <img
                    src="{{ asset('assets/images/suitcase.png') }}"
                    alt="Cat and Dog with Shopping Cart"
                    class="w-full max-w-md"
                />
            </div>
        </div>
    </x-authentication-card>
</x-guest-layout>
