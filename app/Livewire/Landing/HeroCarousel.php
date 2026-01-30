<?php

namespace App\Livewire\Landing;

use Livewire\Component;

class HeroCarousel extends Component
{
    public int $current = 0;

    public array $slides = [
        [
            'title1' => 'make your pet',
            'title2' => 'HAPPY HERE',
            'img' => 'assets/images/airplane.png',
            'btnText' => 'START',
            'btnColor' => 'bg-[#69A985]',
        ],
        [
            'title1' => 'make your pet',
            'title2' => 'SMILE HERE',
            'img' => 'assets/images/pencil.png',
            'btnText' => 'START',
            'btnColor' => 'bg-[#69A985]',
        ],
    ];

    public function next(): void
    {
        $this->current = ($this->current + 1) % count($this->slides);
    }

    public function prev(): void
    {
        $this->current = ($this->current - 1 + count($this->slides)) % count($this->slides);
    }

    public function goTo(int $index): void
    {
        $this->current = $index;
    }

    public function render()
    {
        return view('livewire.landing.hero-carousel');
    }
}
