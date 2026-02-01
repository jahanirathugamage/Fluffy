<?php

namespace App\Livewire\Landing;

use Livewire\Component;
use App\Models\Product;

class TopPicksCarousel extends Component
{
    public int $current = 0;
    public int $perPage = 4;

    public function getProductsProperty()
    {
        return Product::inRandomOrder()->take(8)->get();
    }

    public function totalPages(): int
    {
        return (int) ceil($this->products->count() / $this->perPage);
    }

    public function next(): void
    {
        $this->current = ($this->current + 1) % max(1, $this->totalPages());
    }

    public function prev(): void
    {
        $this->current = ($this->current - 1 + max(1, $this->totalPages())) % max(1, $this->totalPages());
    }

    public function goTo(int $index): void
    {
        $this->current = $index;
    }

    public function visibleProducts()
    {
        $start = $this->current * $this->perPage;
        return $this->products->slice($start, $this->perPage)->values();
    }

    public function render()
    {
        return view('livewire.landing.top-picks-carousel');
    }
}
