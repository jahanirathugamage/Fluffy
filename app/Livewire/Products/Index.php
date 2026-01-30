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

    // Filters (Array state needs explicit URL handling usually, or generic array support)
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

    #[Url]
    public $sustainable = false;

    // Legacy query params support (e.g. /products?animal=cat)
    // We map these to the filter arrays in mount
    public function mount()
    {
        // specific query params from nav links
        if (request()->has('animal')) {
            // Use LIKE for simple case-insensitive match if collation allows, or just strtolower logic
            // Assuming DB names are "Cat", "Dog"
            $animalName = request('animal');
            $animal = Animal::where('name', 'LIKE', $animalName)->first();
            
            if ($animal && !in_array($animal->id, $this->selectedAnimals)) {
                $this->selectedAnimals[] = $animal->id;
            }
        }

        if (request()->has('category')) {
             // e.g. seasonal
             $categoryName = request('category');
             $category = Category::where('name', 'LIKE', $categoryName)->first();
             
             if ($category && !in_array($category->id, $this->selectedCategories)) {
                 $this->selectedCategories[] = $category->id;
             }
        }

        if (request()->has('sustainable')) {
            $this->sustainable = true;
        }
    }

    public $showFilters = false;

    #[On('filtersApplied')]
    public function applyFilters($filters)
    {
        $this->selectedCategories = $filters['categories'] ?? [];
        $this->selectedAnimals = $filters['animals'] ?? [];
        $this->inStockOnly = $filters['inStockOnly'] ?? false;
        $this->minPrice = $filters['minPrice'];
        $this->maxPrice = $filters['maxPrice'];
        
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

    public function render()
    {
        $query = Product::query()
            ->with(['animal', 'category', 'specifications']);

        // Search
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        // Filters
        if (!empty($this->selectedAnimals)) {
             // Handle array of IDs
             // If string comes from URL, livewire casts to array if property is array?
             // Safest is to ensure array.
             $animals = is_array($this->selectedAnimals) ? $this->selectedAnimals : [$this->selectedAnimals];
             $query->whereIn('animal_id', $animals);
        }

        if (!empty($this->selectedCategories)) {
             $cats = is_array($this->selectedCategories) ? $this->selectedCategories : [$this->selectedCategories];
             $query->whereIn('category_id', $cats);
        }

        if ($this->inStockOnly) {
            $query->whereHas('specifications', function($q) {
                $q->where('stock', '>', 0);
            });
        }

        if ($this->minPrice !== '' && $this->minPrice > 0) {
            $query->whereHas('specifications', function($q) {
                $q->where('price', '>=', $this->minPrice);
            });
        }

        if ($this->maxPrice !== '' && $this->maxPrice > 0) {
            $query->whereHas('specifications', function($q) {
                $q->where('price', '<=', $this->maxPrice);
            });
        }

        // Sustainable Filter
        if ($this->sustainable) {
            $query->where('is_sustainable', true);
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

        // Dynamic Title
        $pageTitle = 'All Products';
        if (!empty($this->selectedAnimals)) {
             $animals = Animal::whereIn('id', is_array($this->selectedAnimals) ? $this->selectedAnimals : [$this->selectedAnimals])->pluck('name');
             if ($animals->isNotEmpty()) {
                 $pageTitle = $animals->join(', ');
             }
        } elseif (!empty($this->selectedCategories)) {
             $cats = Category::whereIn('id', is_array($this->selectedCategories) ? $this->selectedCategories : [$this->selectedCategories])->pluck('name');
             if ($cats->isNotEmpty()) {
                 $pageTitle = $cats->join(', ');
             }
        }

        return view('livewire.products.index', [
            'products' => $query->paginate(9),
            'animals' => Animal::all(),
            'categories' => Category::all(),
            'pageTitle' => $pageTitle,
        ])->layout('layouts.app');
    }
}
