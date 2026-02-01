<footer class="bg-[#69A985] text-white font-[Montserrat] px-8 py-10">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col items-center">
            <a href="{{ route('landing') }}">
                <img src="{{ asset('assets/images/fluffy-logo.png') }}" alt="Fluffy Logo" class="h-16">
            </a>

            <div class="flex space-x-6 my-6">
                <a href="#" class="hover:opacity-80">
                    <img src="{{ asset('assets/images/instagram.png') }}" alt="Instagram" class="h-8">
                </a>
                <a href="#" class="hover:opacity-80">
                    <img src="{{ asset('assets/images/twitter-icon.png') }}" alt="Twitter" class="h-8">
                </a>
                <a href="#" class="hover:opacity-80">
                    <img src="{{ asset('assets/images/tiktok-icon.png') }}" alt="TikTok" class="h-8">
                </a>
            </div>
        </div>

        <div class="flex flex-col md:flex-row md:justify-center text-sm text-left md:gap-40 mt-6">
            <div class="flex flex-row justify-center gap-8 sm:gap-16 md:gap-40 mb-8 md:mb-0">
                <div>
                    <h3 class="font-bold mb-3 text-[18px]">SHOP FOR</h3>
                    <ul class="space-y-2 font-medium">
                        <li class="hover:font-bold"><a href="{{ route('products.index', ['animal' => 'cat']) }}">Cats</a></li>
                        <li class="hover:font-bold"><a href="{{ route('products.index', ['animal' => 'dog']) }}">Dogs</a></li>
                        <li class="hover:font-bold"><a href="{{ route('products.index', ['animal' => 'rabbit']) }}">Rabbits</a></li>
                        <li class="hover:font-bold"><a href="{{ route('products.index', ['animal' => 'hamster']) }}">Hamsters</a></li>
                        <li class="hover:font-bold"><a href="{{ route('products.index', ['category' => 'seasonal']) }}">Seasonal boxes</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold mb-3 text-[18px]">INFO</h3>
                    <ul class="space-y-2 font-medium">
                        <li class="hover:font-bold"><a href="{{ route('landing') }}">About</a></li>
                        <li class="hover:font-bold"><a href="{{ route('landing') }}">Reviews</a></li>
                        <li class="hover:font-bold"><a href="{{ route('landing') }}">Inquires</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold mb-3 text-[18px]">HELP</h3>
                    <ul class="space-y-2 font-medium">
                        <li class="hover:font-bold"><a href="{{ route('landing') }}">Contact</a></li>
                        <li class="hover:font-bold"><a href="{{ route('landing') }}">FAQ</a></li>
                        <li class="hover:font-bold"><a href="{{ route('landing') }}">Shipping</a></li>
                        <li class="hover:font-bold"><a href="{{ route('profile.show') }}">Account</a></li>
                    </ul>
                </div>
            </div>

            <div class="text-center sm:text-left">
                <h3 class="font-bold mb-3 text-[18px]">CONTACT</h3>
                <ul class="space-y-3 font-medium">
                    <li class="flex items-center justify-center sm:justify-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 fill-white" viewBox="0 0 24 24">
                            <path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1-.24c1.1.36 2.28.55 3.59.55a1 1 0 0 1 1 1v3.5a1 1 0 0 1-1 1C10.07 22 2 13.93 2 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.31.19 2.49.55 3.59.09.34.03.72-.24 1l-2.19 2.2z"/>
                        </svg>
                        +94 259 814 103
                    </li>
                    <li class="flex items-center justify-center sm:justify-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 fill-white" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.89 2 1.99 2H20c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                        info@fluffy.lk
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="w-full h-[30px] text-center text-[14px] text-white font-medium py-2 mt-6">
        © 2026 Fluffy. All rights reserved
    </div>
</footer>
