<?php

namespace App\Livewire\Products;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use App\Models\Product;
use App\Models\Animal;
use App\Models\Category;

class Index extends Component
{
    use WithPagination;
    


    protected $paginationTheme = 'tailwind';

    #[Url]
    public $search = '';

    #[Url]
    public $sort = 'name_asc';

    #[Url]
    public $selectedCategories = [];

    #[Url]
    public $selectedAnimals = [];

    #[Url]
    public $inStockOnly = false;

    #[Url]
    public $minPrice = '';

    #[Url]
    public $maxPrice = '';

    public $showFilters = false;

    public function mount()
    {
        // If employee, redirect to dashboard
        if (auth()->check() && (auth()->user()->hasRole('employee') || auth()->user()->can('view-dashboard'))) {
            return redirect()->route('employee.manage-products');
        }

        $animalParam = request()->query('animal');      // cat, dog, rabbit, hamster
        $categoryParam = request()->query('category');  // accessories, grooming, sustainable, etc.

        // SET animal filter
        if (!empty($animalParam)) {
            $animal = Animal::whereRaw('LOWER(name) = ?', [strtolower($animalParam)])->first();
            if ($animal) {
                $this->selectedAnimals = [$animal->id];
            }
        }

        // SET category filter
        if (!empty($categoryParam)) {
            $category = Category::whereRaw('LOWER(name) = ?', [strtolower($categoryParam)])->first();
            if ($category) {
                $this->selectedCategories = [$category->id];
            }
        }
    }

    #[On('filtersApplied')]
    public function applyFilters($filters)
    {
        $this->selectedCategories = $filters['categories'] ?? [];
        $this->selectedAnimals = $filters['animals'] ?? [];
        $this->inStockOnly = $filters['inStockOnly'] ?? false;
        $this->minPrice = $filters['minPrice'] ?? '';
        $this->maxPrice = $filters['maxPrice'] ?? '';

        $this->showFilters = false;
        $this->resetPage();
    }

    #[On('closeFilters')]
    public function closeFilters()
    {
        $this->showFilters = false;
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    private function normalizeToArray($value): array
    {
        if (is_array($value)) return $value;
        if ($value === null || $value === '') return [];
        return [$value];
    }

    public function render()
    {
        $query = Product::query()
            ->with(['animal', 'category', 'specifications']);

        // Search
        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        // Filters: animals
        $animalIds = $this->normalizeToArray($this->selectedAnimals);
        if (!empty($animalIds)) {
            $query->whereIn('animal_id', $animalIds);
        }

        // Filters: categories
        $categoryIds = $this->normalizeToArray($this->selectedCategories);
        if (!empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }

        // In stock only
        if ($this->inStockOnly) {
            $query->whereHas('specifications', function ($q) {
                $q->where('stock', '>', 0);
            });
        }

        // Min price
        if ($this->minPrice !== '' && (float)$this->minPrice > 0) {
            $min = (float)$this->minPrice;
            $query->whereHas('specifications', function ($q) use ($min) {
                $q->where('price', '>=', $min);
            });
        }

        // Max price
        if ($this->maxPrice !== '' && (float)$this->maxPrice > 0) {
            $max = (float)$this->maxPrice;
            $query->whereHas('specifications', function ($q) use ($max) {
                $q->where('price', '<=', $max);
            });
        }

        // Sorting
        match ($this->sort) {
            'price_asc' => $query->select('products.*')
                ->join('specifications', 'products.id', '=', 'specifications.product_id')
                ->groupBy('products.id')
                ->orderByRaw('MIN(specifications.price) ASC'),

            'price_desc' => $query->select('products.*')
                ->join('specifications', 'products.id', '=', 'specifications.product_id')
                ->groupBy('products.id')
                ->orderByRaw('MAX(specifications.price) DESC'),

            default => $query->orderBy('name'),
        };

        // Page title
        $pageTitle = 'All Products';

        $animalNames = collect();
        if (!empty($animalIds)) {
            $animalNames = Animal::whereIn('id', $animalIds)->pluck('name');
        }

        $categoryNames = collect();
        if (!empty($categoryIds)) {
            $categoryNames = Category::whereIn('id', $categoryIds)->pluck('name');
        }

        if ($animalNames->isNotEmpty() && $categoryNames->isNotEmpty()) {
            $pageTitle = $animalNames->first() . ' ' . $categoryNames->first();
        } elseif ($animalNames->isNotEmpty()) {
            $pageTitle = $animalNames->first() . ' Products';
        } elseif ($categoryNames->isNotEmpty()) {
            $pageTitle = $categoryNames->first();
        }

        return view('livewire.products.index', [
            'products' => $query->paginate(9),
            'animals' => Animal::all(),
            'categories' => Category::all(),
            'pageTitle' => $pageTitle,
        ])->layout('layouts.app');
    }
}
