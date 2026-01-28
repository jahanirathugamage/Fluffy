<?php

namespace App\Livewire\Admin\Products;

use App\Models\Animal;
use App\Models\Category;
use App\Models\Product;
use App\Models\Specification;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Product $product;

    public ?int $animal_id = null;
    public ?int $category_id = null;
    public string $name = '';
    public string $details = '';
    public string $benefits = '';
    public string $nutrition = '';

    public ?string $current_image_path = null;
    public $image; // new upload

    // specs array: include id for existing ones
    public array $specs = [];

    public function mount(Product $product): void
    {
        $this->product = $product->load('specifications');

        $this->animal_id = $this->product->animal_id;
        $this->category_id = $this->product->category_id;
        $this->name = $this->product->name;
        $this->details = $this->product->details;
        $this->benefits = $this->product->benefits;
        $this->nutrition = $this->product->nutrition;
        $this->current_image_path = $this->product->image_path;

        $this->specs = $this->product->specifications
            ->map(fn ($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'price' => $s->price,
                'stock' => $s->stock,
                'delete' => false,
            ])->toArray();

        if (count($this->specs) === 0) {
            $this->specs = [['id' => null, 'name' => '', 'price' => '', 'stock' => 1, 'delete' => false]];
        }
    }

    protected function rules(): array
    {
        return [
            'animal_id' => ['required', 'exists:animals,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:100'],
            'details' => ['required', 'string'],
            'benefits' => ['required', 'string'],
            'nutrition' => ['required', 'string'],

            'image' => ['nullable', 'image', 'max:2048'],

            'specs' => ['required', 'array', 'min:1'],
            'specs.*.name' => ['required', 'string', 'max:255'],
            'specs.*.price' => ['required', 'numeric', 'min:0'],
            'specs.*.stock' => ['required', 'integer', 'min:0'],
        ];
    }

    public function addSpec(): void
    {
        $this->specs[] = ['id' => null, 'name' => '', 'price' => '', 'stock' => 1, 'delete' => false];
    }

    public function removeSpec(int $index): void
    {
        if (count($this->specs) <= 1) {
            return;
        }

        // If existing spec: mark delete (so validation still passes until we filter)
        if (!empty($this->specs[$index]['id'])) {
            $this->specs[$index]['delete'] = true;
        } else {
            unset($this->specs[$index]);
            $this->specs = array_values($this->specs);
        }
    }

    public function save()
    {
        // Remove deleted ones before validation (but keep min:1 constraint true)
        $filteredSpecs = array_values(array_filter($this->specs, fn ($s) => empty($s['delete'])));

        if (count($filteredSpecs) < 1) {
            $this->addError('specs', 'At least one specification is required.');
            return;
        }

        // temporarily replace for validation
        $original = $this->specs;
        $this->specs = $filteredSpecs;

        $validated = $this->validate();

        // restore full list for later processing
        $this->specs = $original;

        DB::transaction(function () use ($validated, $filteredSpecs) {
            $imagePath = $this->current_image_path;

            if ($this->image) {
                $stored = $this->image->store('products', 'public');
                $imagePath = 'storage/' . $stored;
            }

            $this->product->update([
                'animal_id' => $validated['animal_id'],
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'details' => $validated['details'],
                'benefits' => $validated['benefits'],
                'nutrition' => $validated['nutrition'],
                'image_path' => $imagePath,
            ]);

            // Delete removed existing specs
            $toDeleteIds = collect($original)
                ->filter(fn ($s) => !empty($s['delete']) && !empty($s['id']))
                ->pluck('id')
                ->values()
                ->all();

            if (!empty($toDeleteIds)) {
                Specification::where('product_id', $this->product->id)
                    ->whereIn('id', $toDeleteIds)
                    ->delete();
            }

            // Upsert remaining specs
            foreach ($filteredSpecs as $s) {
                if (!empty($s['id'])) {
                    Specification::where('product_id', $this->product->id)
                        ->where('id', $s['id'])
                        ->update([
                            'name' => $s['name'],
                            'price' => $s['price'],
                            'stock' => $s['stock'],
                        ]);
                } else {
                    Specification::create([
                        'product_id' => $this->product->id,
                        'name' => $s['name'],
                        'price' => $s['price'],
                        'stock' => $s['stock'],
                    ]);
                }
            }
        });

        session()->flash('success', 'Product updated successfully.');
        return redirect()->route('admin.products.index');
    }

    public function render()
    {
        return view('livewire.admin.products.edit', [
            'animals' => Animal::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}
