<?php
// app\Livewire\Admin\Products\Index.php
namespace App\Livewire\Admin\Products;

use App\Models\Product;
use App\Models\Animal;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public string $search = '';
    public ?int $animalId = null;
    public ?int $categoryId = null;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingAnimalId() { $this->resetPage(); }
    public function updatingCategoryId() { $this->resetPage(); }

    public function delete(int $id): void
    {
        Product::query()->findOrFail($id)->delete();
        session()->flash('success', 'Product deleted.');
    }

    public function render()
    {
        $query = Product::query()
            ->with(['animal', 'category'])
            ->when($this->search !== '', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->animalId, fn ($q) => $q->where('animal_id', $this->animalId))
            ->when($this->categoryId, fn ($q) => $q->where('category_id', $this->categoryId))
            ->orderBy('id', 'desc');

        return view('livewire.admin.products.index', [
            'products' => $query->paginate(10),
            'animals' => Animal::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}
