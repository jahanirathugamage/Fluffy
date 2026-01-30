<?php

namespace App\Livewire\Components;

use Livewire\Component;

class ProductFilter extends Component
{
    // Filter properties
    public $selectedCategories = [];
    public $selectedAnimals = [];
    public $inStockOnly = false;
    public $minPrice = '';
    public $maxPrice = '';

    // Expansion states for collapsible sections
    public $categoryExpanded = false;
    public $animalExpanded = false;
    public $priceExpanded = false;

    // Props from parent
    public $categories = [];
    public $animals = [];

    protected $rules = [
        'minPrice' => 'nullable|numeric|min:0',
        'maxPrice' => 'nullable|numeric|min:0',
    ];

    public function mount($categories, $animals, $selectedCategories = [], $selectedAnimals = [], $inStockOnly = false, $minPrice = '', $maxPrice = '')
    {
        $this->categories = $categories;
        $this->animals = $animals;
        $this->selectedCategories = $selectedCategories;
        $this->selectedAnimals = $selectedAnimals;
        $this->inStockOnly = $inStockOnly;
        $this->minPrice = $minPrice === 0 ? '' : $minPrice; // Display empty if 0 for cleaner UI per request
        $this->maxPrice = $maxPrice;
    }

    public function toggleCategory()
    {
        $this->categoryExpanded = !$this->categoryExpanded;
    }

    public function toggleAnimal()
    {
        $this->animalExpanded = !$this->animalExpanded;
    }

    public function togglePrice()
    {
        $this->priceExpanded = !$this->priceExpanded;
    }

    public function applyFilters()
    {
        $this->validate();

        // Price validation - ensure min < max if both provided
        if ($this->minPrice !== '' && $this->maxPrice !== '' && (float)$this->minPrice > (float)$this->maxPrice) {
            $this->addError('maxPrice', 'Maximum price must be greater than minimum price.');
            return;
        }

        // Emit event to parent with filter data
        $this->dispatch('filtersApplied', [
            'categories' => $this->selectedCategories,
            'animals' => $this->selectedAnimals,
            'inStockOnly' => $this->inStockOnly,
            'minPrice' => $this->minPrice === '' ? 0 : (float)$this->minPrice,
            'maxPrice' => $this->maxPrice === '' ? null : (float)$this->maxPrice,
        ]);
    }

    public function render()
    {
        return view('livewire.components.product-filter');
    }
}
