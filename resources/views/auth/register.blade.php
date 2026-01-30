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
                Create Account
            </p>
        </div>

        <!-- Mobile Only: Illustration below heading -->
        <div class="sm:hidden flex justify-center items-center mb-8">
            <img
                src="{{ asset('assets/images/pizza.png') }}"
                alt="Cat with Pizza"
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
                        Create Account
                    </p>
                </div>

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name Field -->
                    <div class="mb-5">
                        <input
                            id="name"
                            class="block w-full px-4 py-3 border-2 border-black rounded-none focus:outline-none focus:ring-0 focus:border-black"
                            type="text"
                            name="name"
                            placeholder="Name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            autocomplete="name"
                        />
                    </div>

                    <!-- Email Field -->
                    <div class="mb-5">
                        <input
                            id="email"
                            class="block w-full px-4 py-3 border-2 border-black rounded-none focus:outline-none focus:ring-0 focus:border-black"
                            type="email"
                            name="email"
                            placeholder="Email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="username"
                        />
                    </div>

                    <!-- Password Field -->
                    <div class="mb-5">
                        <input
                            id="password"
                            class="block w-full px-4 py-3 border-2 border-black rounded-none focus:outline-none focus:ring-0 focus:border-black"
                            type="password"
                            name="password"
                            placeholder="Password"
                            required
                            autocomplete="new-password"
                        />
                    </div>

                    <!-- Terms and Privacy Policy Checkbox -->
                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="mb-5">
                            <label for="terms" class="flex items-start">
                                <input
                                    type="checkbox"
                                    name="terms"
                                    id="terms"
                                    class="mt-1 h-4 w-4 border-2 border-black rounded-none focus:ring-0 focus:ring-offset-0"
                                    required
                                />
                                <span class="ml-2 text-sm text-gray-700">
                                    I agree to the <a href="{{ route('terms.show') }}" target="_blank" class="underline hover:text-gray-900">Terms</a> and <a href="{{ route('policy.show') }}" target="_blank" class="underline hover:text-gray-900">Privacy Policy</a>.
                                </span>
                            </label>
                        </div>
                    @endif

                    <!-- Sign Up Button -->
                    <button
                        type="submit"
                        class="w-full bg-[#5BC0DE] hover:bg-[#4AA9C5] text-white font-semibold py-3 px-4 border-2 border-black transition duration-150 ease-in-out"
                    >
                        Sign Up
                    </button>
                </form>

                <!-- OR Divider -->
                <div class="relative flex items-center justify-center my-6">
                    <div class="border-t border-gray-300 w-full"></div>
                    <span class="bg-white px-4 text-sm text-gray-500 absolute">OR</span>
                </div>

                <!-- Google Sign-Up Button -->
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
                    <span>Sign up with Google</span>
                </a>

                <!-- Already have an account link -->
                <div class="text-center mt-6">
                    <p class="text-sm text-gray-700">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="font-semibold underline hover:text-gray-900">Login</a>
                    </p>
                </div>
            </div>

            <!-- Right Column: Illustration (Desktop Only) -->
            <div class="hidden sm:flex justify-center items-center sm:pl-2">
                <img
                    src="{{ asset('assets/images/pizza.png') }}"
                    alt="Cat with Pizza"
                    class="w-full max-w-md"
                />
            </div>
        </div>
    </x-authentication-card>
</x-guest-layout>
