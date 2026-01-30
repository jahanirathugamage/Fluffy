<div 
    class="flex flex-col items-center justify-center gap-4 font-['Montserrat'] text-black bg-white m-10"
    x-data="{ 
        checkMobile() {
            let isMobile = window.innerWidth < 768;
            if (this.$wire.isMobile !== isMobile) {
                this.$wire.set('isMobile', isMobile);
                this.$wire.set('current', 0);
            }
        }
    }"
    x-init="checkMobile(); window.addEventListener('resize', () => checkMobile())"
>
    <h2 class="text-[24px] md:text-[30px] font-bold">Explore</h2>

    <div class="w-full overflow-hidden relative px-6 md:px-40 mt-6">
        <div class="flex gap-10">
            @foreach($this->visibleCards() as $card)
                <div class="flex-shrink-0 w-full md:w-1/3 flex flex-col gap-2 items-center justify-center">
                    <div class="w-[220px] h-[160px] md:w-[280px] md:h-[200px] flex items-center justify-center">
                        <img src="{{ asset($card['img']) }}" alt="{{ $card['name'] }}" class="max-h-full max-w-full object-contain">
                    </div>
                    <p class="text-[16px] md:text-[20px] font-medium">{{ $card['name'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Navigation Buttons --}}
        <button wire:click="prev" class="absolute left-4 md:left-0 top-1/2 -translate-y-1/2 text-black text-3xl font-bold z-10">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-10 h-10">
                <path fill="#000000" d="M169.4 297.4C156.9 309.9 156.9 330.2 169.4 342.7L361.4 534.7C373.9 547.2 394.2 547.2 406.7 534.7C419.2 522.2 419.2 501.9 406.7 489.4L237.3 320L406.6 150.6C419.1 138.1 419.1 117.8 406.6 105.3C394.1 92.8 373.8 92.8 361.3 105.3L169.3 297.3z"/>
            </svg>
        </button>

        <button wire:click="next" class="absolute right-4 md:right-0 top-1/2 -translate-y-1/2 text-black text-3xl font-bold z-10">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-10 h-10">
                <path fill="#000000" d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
            </svg>
        </button>

        {{-- Pagination Dots --}}
        <div class="flex gap-2 items-center justify-center mt-5">
            @for($i = 0; $i < $this->totalPages(); $i++)
                <button wire:click="goTo({{ $i }})"
                    class="w-3 h-3 rounded-full transition-colors border-2 border-black {{ $current === $i ? 'bg-white' : 'bg-gray-400' }}">
                </button>
            @endfor
        </div>
    </div>
</div>
