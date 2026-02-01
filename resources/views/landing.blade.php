<x-app-layout>
    <!-- resources\views\landing.blade.php -->
    <div class="font-['Montserrat']">


        {{-- ✅ Hero Carousel (Livewire) --}}
        <livewire:landing.hero-carousel />

        {{-- ✅ Explore Section (Livewire) --}}
        <livewire:landing.category-carousel />

        {{-- ✅ Top Picks Section (Livewire, DB-driven) --}}
        <livewire:landing.top-picks-carousel />

        {{-- ✅ Premium --}}
        <div class="flex flex-col items-center justify-center gap-4 font-['Montserrat'] m-8 md:m-20 w-[90%] md:w-auto">
            <img src="{{ asset('assets/images/cookie.png') }}" alt="cookie">
            <div class="flex flex-col gap-2 text-center">
                <h2 class="font-bold text-[24px] md:text-[30px]">Join Fluffy Premium</h2>
                <h3 class="font-bold text-[18px] md:text-[24px]">More Treats, Perks and Joy!</h3>
            </div>
            <div class="flex flex-col gap-4 text-center max-w-[90%] md:max-w-[600px] mx-auto">
                <p class="font-regular text-[16px] md:text-[20px]">
                    Spoil your pet with exclusive perks! Starting from just
                    <span class="font-bold">LKR 499/month</span>, you'll unlock special benefits.
                </p>
                <p class="font-regular text-[16px] md:text-[20px]">
                    And for a limited time, enjoy a
                    <span class="font-bold">discounted membership rates</span> as part of our launch promotion.
                </p>
            </div>
            <a href="{{ route('landing') }}">
                <button class="text-white text-[16px] md:text-[20px] bg-[#4FB5D0] font-bold w-[150px] h-[45px] border-[3px] border-black hover:bg-black mt-4">
                    JOIN NOW
                </button>
            </a>
        </div>

        {{-- ✅ About --}}
        <div class="flex items-center justify-center">
            <div class="w-[90%] md:w-[700px] flex flex-col items-center justify-center gap-4 font-['Montserrat'] m-8 md:m-20 border-4 border-black">
                <div class="flex flex-col items-center justify-center mx-4 md:mx-10 gap-10">
                    <h2 class="font-bold text-[24px] md:text-[30px] mt-10">About</h2>
                    <img src="{{ asset('assets/images/bag.png') }}" alt="bag">
                    <div class="flex flex-col gap-4 text-center max-w-[90%] md:max-w-[600px] mx-auto mb-10">
                        <p class="font-regular text-[16px] md:text-[20px]">
                            At <span class="font-bold">Fluffy</span> we believe pets make life brighter! So their goodies should be just as joyful! From
                            <span class="font-bold">eco-friendly</span> toys to tasty treats, we handpick the best for wagging tails, happy purrs, and even the occasional rabbit and hamster high-five.
                        </p>
                        <p class="font-regular text-[16px] md:text-[20px]">
                            Also we would like to credit the artist
                            <a href="https://www.instagram.com/bumpy2025/" class="text-black font-bold hover:underline">@bumpy2025</a>
                            for the cute artwork!
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ✅ Personalized Goodies --}}
        <div class="flex flex-col items-center justify-center gap-4 font-['Montserrat'] m-8 md:m-20 w-[90%] md:w-auto">
            <img src="{{ asset('assets/images/box.png') }}" alt="box">
            <h2 class="font-bold text-[24px] md:text-[30px] text-center">Personalized Goodies</h2>
            <div class="flex flex-col gap-4 text-center max-w-[90%] md:max-w-[600px] mx-auto">
                <p class="font-regular text-[16px] md:text-[20px]">
                    Spoil your furry friend with our adorable gift boxes, packed with tasty treats, fun toys, and eco-friendly essentials. Perfect for birthdays, "gotcha" days, or just!
                </p>
                <p class="font-regular text-[16px] md:text-[20px]">
                    Each box is a bundle of tail wags, happy purrs and planet-friendly love.
                </p>
            </div>
            <a href="{{ route('products.index', ['category' => 'seasonal']) }}">
                <button class="text-white text-[16px] md:text-[20px] bg-[#69A985] font-bold w-[150px] h-[45px] border-[3px] border-black hover:bg-black mt-4">
                    GET IT
                </button>
            </a>
        </div>

        {{-- ✅ Contact (kept same look) --}}
        <div class="flex items-center justify-center">
            <div class="w-[400px] md:w-[700px] flex flex-col items-center justify-center gap-4 bg-[#4FB5D0] font-['Montserrat'] m-20 border-4 border-black">
                <div class="flex flex-col items-center justify-center mx-10 gap-10">
                    <h2 class="font-bold text-[24px] md:text-[30px] mt-10">Contact Us</h2>
                    <form action="#" method="post" class="flex flex-col gap-6 w-full max-w-[330px] items-center justify-center">
                        @csrf
                        <input type="text" placeholder="Name" class="w-[250px] md:w-[500px] h-[40px] border-[2px] border-black px-3" required>
                        <input type="email" placeholder="Email" class="w-[250px] md:w-[500px] h-[40px] border-[2px] border-black px-3" required>
                        <input type="number" placeholder="Mobile Number" class="w-[250px] md:w-[500px] h-[40px] border-[2px] border-black px-3" required>
                        <textarea class="w-[250px] md:w-[500px] border-[2px] border-black px-3" rows="5" placeholder="Message" required></textarea>
                        <button type="submit" class="w-[180px] h-[45px] bg-white font-regular text-[16px] md:text-[20px] text-black border-[2px] border-black hover:font-bold mb-10">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ✅ Footer (Livewire) --}}
        <livewire:fluffy.footer />
    </div>
</x-app-layout>
