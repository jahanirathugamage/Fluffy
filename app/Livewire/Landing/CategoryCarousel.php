<?php

namespace App\Livewire\Landing;

use Livewire\Component;

class CategoryCarousel extends Component
{
    public int $currentMobile = 0;
    public int $currentDesktop = 0;

    public array $cards = [
        ['name' => 'Sustainable', 'slug' => 'sustainable', 'img' => 'assets/images/trees.png'],
        ['name' => 'Food',        'slug' => 'food',        'img' => 'assets/images/cart.png'],
        ['name' => 'Accessories', 'slug' => 'accessories', 'img' => 'assets/images/clothes.png'],
        ['name' => 'Grooming',    'slug' => 'grooming',    'img' => 'assets/images/fan.png'],
        ['name' => 'Toys',        'slug' => 'toys',        'img' => 'assets/images/tuk.png'],
    ];

    // --- Mobile: 1 per page ---
    public function totalPagesMobile(): int
    {
        return (int) ceil(count($this->cards) / 1);
    }

    public function visibleCardsMobile(): array
    {
        $start = $this->currentMobile * 1;
        return array_slice($this->cards, $start, 1);
    }

    public function nextMobile(): void
    {
        $this->currentMobile = ($this->currentMobile + 1) % $this->totalPagesMobile();
    }

    public function prevMobile(): void
    {
        $this->currentMobile = ($this->currentMobile - 1 + $this->totalPagesMobile()) % $this->totalPagesMobile();
    }

    public function goToMobile(int $index): void
    {
        $this->currentMobile = $index;
    }

    // --- Desktop: 3 per page ---
    public function totalPagesDesktop(): int
    {
        return (int) ceil(count($this->cards) / 3);
    }

    public function visibleCardsDesktop(): array
    {
        $start = $this->currentDesktop * 3;
        return array_slice($this->cards, $start, 3);
    }

    public function nextDesktop(): void
    {
        $this->currentDesktop = ($this->currentDesktop + 1) % $this->totalPagesDesktop();
    }

    public function prevDesktop(): void
    {
        $this->currentDesktop = ($this->currentDesktop - 1 + $this->totalPagesDesktop()) % $this->totalPagesDesktop();
    }

    public function goToDesktop(int $index): void
    {
        $this->currentDesktop = $index;
    }

    // --- Redirect to products page filtered by category ---
    public function goToCategory(string $slug)
    {
        return redirect()->route('products.index', ['category' => $slug]);
    }

    public function render()
    {
        return view('livewire.landing.category-carousel');
    }
}
