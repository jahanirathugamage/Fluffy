<?php

namespace App\Livewire\Admin\Products;

use App\Models\Animal;
use App\Models\Category;
use App\Models\Product;
use App\Models\Specification;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    // Product fields
    public ?int $animal_id = null;
    public ?int $category_id = null;
    public string $name = '';
    public string $details = '';
    public string $benefits = '';
    public string $nutrition = '';
    public $image; // Livewire temp upload

    // Specs (min 1)
    public array $specs = [
        ['name' => '', 'price' => '', 'stock' => 1],
    ];

    protected function rules(): array
    {
        return [
            'animal_id' => ['required', 'exists:animals,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:100'],
            'details' => ['required', 'string'],
            'benefits' => ['required', 'string'],
            'nutrition' => ['required', 'string'],

            'image' => ['nullable', 'image', 'max:2048'], // 2MB

            'specs' => ['required', 'array', 'min:1'],
            'specs.*.name' => ['required', 'string', 'max:255'],
            'specs.*.price' => ['required', 'numeric', 'min:0'],
            'specs.*.stock' => ['required', 'integer', 'min:0'],
        ];
    }

    public function addSpec(): void
    {
        $this->specs[] = ['name' => '', 'price' => '', 'stock' => 1];
    }

    public function removeSpec(int $index): void
    {
        // enforce min 1 spec
        if (count($this->specs) <= 1) {
            return;
        }
        unset($this->specs[$index]);
        $this->specs = array_values($this->specs);
    }

    public function save()
    {
        $validated = $this->validate();

        DB::transaction(function () use ($validated) {
            $imagePath = null;

            if ($this->image) {
                // stored in storage/app/public/products/...
                $stored = $this->image->store('products', 'public');
                $imagePath = 'storage/' . $stored; // public URL path
            }

            /** @var Product $product */
            $product = Product::create([
                'animal_id' => $validated['animal_id'],
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'details' => $validated['details'],
                'benefits' => $validated['benefits'],
                'nutrition' => $validated['nutrition'],
                'image_path' => $imagePath,
            ]);

            foreach ($validated['specs'] as $s) {
                Specification::create([
                    'product_id' => $product->id,
                    'name' => $s['name'],
                    'price' => $s['price'],
                    'stock' => $s['stock'],
                ]);
            }
        });

        session()->flash('success', 'Product created successfully.');
        return redirect()->route('admin.products.index');
    }

    public function render()
    {
        return view('livewire.admin.products.create', [
            'animals' => Animal::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}
