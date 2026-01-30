<?php

namespace App\Livewire\Landing;

use Livewire\Component;

class CategoryCarousel extends Component
{
    public int $current = 0;
    public bool $isMobile = false; // Track mobile state

    public array $cards = [
        ['name' => 'Sustainable',  'img' => 'assets/images/trees.png'],
        ['name' => 'Food',         'img' => 'assets/images/cart.png'],
        ['name' => 'Accessories',  'img' => 'assets/images/clothes.png'],
        ['name' => 'Grooming',     'img' => 'assets/images/fan.png'],
        ['name' => 'Toys',         'img' => 'assets/images/tuk.png'],
    ];

    public function mount()
    {
        // Default to mobile for server-side rendering
        // Will be updated by Alpine.js on client side
        $this->isMobile = true;
    }

    public function getPerPageProperty(): int
    {
        // Show 1 item on mobile, 3 on desktop
        return $this->isMobile ? 1 : 3;
    }

    public function totalPages(): int
    {
        return (int) ceil(count($this->cards) / $this->perPage);
    }

    public function next(): void
    {
        $this->current = ($this->current + 1) % $this->totalPages();
    }

    public function prev(): void
    {
        $this->current = ($this->current - 1 + $this->totalPages()) % $this->totalPages();
    }

    public function goTo(int $index): void
    {
        $this->current = $index;
    }

    public function visibleCards(): array
    {
        $start = $this->current * $this->perPage;
        return array_slice($this->cards, $start, $this->perPage);
    }

    public function render()
    {
        return view('livewire.landing.category-carousel');
    }
}
