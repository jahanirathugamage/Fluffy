<?php

namespace App\Livewire\Products;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Animal;
use App\Models\Category;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $animalId = '';
    public $categoryId = '';
    public $sort = 'name_asc';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::query()
            ->with(['animal', 'category', 'specifications']);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->animalId) {
            $query->where('animal_id', $this->animalId);
        }

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        match ($this->sort) {
            'price_asc' => $query->orderBy(
                Product::select('price')
                    ->join('specifications', 'products.id', '=', 'specifications.product_id')
                    ->whereColumn('products.id', 'specifications.product_id')
                    ->orderBy('price', 'asc')
                    ->limit(1)
            ),
            'price_desc' => $query->orderBy(
                Product::select('price')
                    ->join('specifications', 'products.id', '=', 'specifications.product_id')
                    ->whereColumn('products.id', 'specifications.product_id')
                    ->orderBy('price', 'desc')
                    ->limit(1)
            ),
            default => $query->orderBy('name'),
        };

        return view('livewire.products.index', [
            'products' => $query->paginate(9),
            'animals' => Animal::all(),
            'categories' => Category::all(),
        ])->layout('layouts.app');
    }
}
