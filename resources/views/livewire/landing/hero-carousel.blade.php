<div
    wire:poll.5s="next"
    class="w-full font-['Montserrat'] bg-[#4FB5D0] text-white overflow-hidden relative"
>
    {{-- Slides Container --}}
    <div
        class="flex transition-transform duration-700 ease-in-out"
        style="transform: translateX(-{{ $current * 100 }}%);"
    >
        @foreach($slides as $index => $slide)
            <div class="flex-shrink-0 w-full flex flex-col md:flex-row justify-between items-center md:items-start px-6 md:px-40 pt-12 md:pt-20 pb-14 md:pb-10 gap-4 md:gap-12">
                <div class="flex flex-col items-center justify-center md:items-start text-center md:text-left">
                    <h1 class="text-[20px] md:text-[40px] font-medium">{{ $slide['title1'] }}</h1>
                    <h1 class="text-[30px] md:text-[60px] font-bold">{{ $slide['title2'] }}</h1>

                    <a href="{{ route('products.index', ['animal' => 'cat']) }}">
                        <button class="{{ $slide['btnColor'] }} text-white text-[20px] md:text-[24px] font-bold w-[130px] h-[45px] border-[3px] border-white mt-6">
                            {{ $slide['btnText'] }}
                        </button>
                    </a>
                </div>

                <img src="{{ asset($slide['img']) }}" alt="hero image" class="w-[242px] md:w-[350px] object-contain">
            </div>
        @endforeach
    </div>

    {{-- Navigation Buttons --}}
    <button wire:click="prev" class="absolute left-4 top-1/2 -translate-y-1/2 text-white text-3xl font-bold z-10">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-10 h-10">
            <path fill="#ffffff" d="M169.4 297.4C156.9 309.9 156.9 330.2 169.4 342.7L361.4 534.7C373.9 547.2 394.2 547.2 406.7 534.7C419.2 522.2 419.2 501.9 406.7 489.4L237.3 320L406.6 150.6C419.1 138.1 419.1 117.8 406.6 105.3C394.1 92.8 373.8 92.8 361.3 105.3L169.3 297.3z"/>
        </svg>
    </button>

    <button wire:click="next" class="absolute right-4 top-1/2 -translate-y-1/2 text-white text-3xl font-bold z-10">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-10 h-10">
            <path fill="#ffffff" d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
        </svg>
    </button>

    {{-- Pagination Dots --}}
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2">
        @foreach($slides as $index => $slide)
            <button
                wire:click="goTo({{ $index }})"
                class="w-3 h-3 rounded-full transition-colors border-2 border-black {{ $current === $index ? 'bg-white' : 'bg-gray-400' }}"
            ></button>
        @endforeach
    </div>
</div>
