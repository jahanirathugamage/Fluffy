<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Specification;
use App\Models\Category;
use App\Models\Animal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ManageProducts extends Component
{
    use WithFileUploads, WithPagination;

    // Modal states
    public $showAddModal = false;
    public $showEditModal = false;

    // Search
    public $search = '';

    // Form fields - Product
    public $productId;
    public $specificationId;
    public $name;
    public $category_id;
    public $animal_id;
    public $details;
    public $benefits;
    public $nutrition;
    public $productImage;
    public $existingImage;

    // Form fields - Specification
    public $specName;
    public $price;
    public $stock;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        $imageRule = $this->showEditModal && !$this->productImage ? 'nullable' : 'required';
        
        return [
            'name' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'animal_id' => 'required|exists:animals,id',
            'details' => 'required|string',
            'benefits' => 'required|string',
            'nutrition' => 'required|string',
            'productImage' => $imageRule . '|image',
            'specName' => 'required|string|max:255',
            'price' => ['required', 'numeric', 'min:0.01', 'regex:/^\d+(\.\d{1,2})?$/'],
            'stock' => 'required|integer|min:0',
        ];
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->showAddModal = true;
    }

    public function closeAddModal()
    {
        $this->showAddModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function openEditModal($specificationId)
    {
        $this->resetForm();
        
        $specification = Specification::with('product.animal', 'product.category')->findOrFail($specificationId);
        $product = $specification->product;
        
        $this->productId = $product->id;
        $this->specificationId = $specification->id;
        $this->name = $product->name;
        $this->category_id = $product->category_id;
        $this->animal_id = $product->animal_id;
        $this->details = $product->details;
        $this->benefits = $product->benefits;
        $this->nutrition = $product->nutrition;
        $this->existingImage = $product->image_path;
        
        $this->specName = $specification->name;
        $this->price = $specification->price;
        $this->stock = $specification->stock;
        
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->reset('showEditModal');
        $this->resetForm();
        $this->resetValidation();
    }

    public function submitForm()
    {
        if ($this->showEditModal) {
            return $this->updateProduct();
        } else {
            return $this->saveProduct();
        }
    }

    public function saveProduct()
    {
        $this->validate();

        $imageName = $this->handleImageUpload();

        // Call stored procedure with transaction
        // DB::beginTransaction();
        try {
            DB::statement('CALL sp_create_product(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @p_product_id, @p_specification_id)', [
                $this->name,
                $this->category_id,
                $this->animal_id,
                $this->details,
                $this->benefits,
                $this->nutrition,
                $imageName,
                $this->specName,
                $this->price,
                $this->stock
            ]);
            
            // DB::commit();
            session()->flash('success', 'Product created successfully');
            $this->dispatch('product-saved');
        } catch (\Exception $e) {
            // DB::rollBack();
            // Delete uploaded image on failure
            if ($imageName) {
                $this->deleteOldImage($imageName);
            }
            session()->flash('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    public function updateProduct()
    {
        $this->validate();

        $imageName = $this->productImage 
            ? $this->handleImageUpload() 
            : $this->existingImage;

        // DB::beginTransaction(); - Removed to avoid "No active transaction" error as SP handles it
        try {
            // Delete old image if new one uploaded
            if ($this->productImage && $this->existingImage) {
                $this->deleteOldImage($this->existingImage);
            }
            
            DB::statement('CALL sp_update_product(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $this->productId,
                $this->specificationId,
                $this->name,
                $this->category_id,
                $this->animal_id,
                $this->details,
                $this->benefits,
                $this->nutrition,
                $imageName,
                $this->specName,
                $this->price,
                $this->stock
            ]);
            
            // DB::commit();
            
            session()->flash('success', 'Product updated successfully');
            Log::info('Product Updated, dispatching product-saved event - Product ID: ' . $this->productId);
            $this->dispatch('product-saved');
        } catch (\Exception $e) {
            // DB::rollBack();
            Log::error('Update Product Failed: ' . $e->getMessage());
            session()->flash('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function deleteProduct($specificationId)
    {
        DB::beginTransaction();
        try {
            // Call stored procedure
            DB::statement('CALL sp_delete_product(?, @image_path, @animal_name)', [$specificationId]);
            
            // Get output parameters
            $result = DB::select('SELECT @image_path AS image_path, @animal_name AS animal_name')[0];
            
            // Delete image file if needed
            if ($result->image_path) {
                $this->deleteOldImage($result->image_path);
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }

    private function handleImageUpload()
    {
        $animal = Animal::findOrFail($this->animal_id);
        $animalName = $animal->name;
        
        // Get file extension
        $extension = $this->productImage->getClientOriginalExtension();
        
        // Create safe filename (no special characters)
        $safeFileName = time() . '_' . uniqid() . '.' . $extension;
        
        // Define the target directory path using forward slashes
        $targetDir = 'assets/images/' . $animalName;
        
        // Create the full directory in public folder if it doesn't exist
        $publicPath = public_path($targetDir);
        if (!is_dir($publicPath)) {
            mkdir($publicPath, 0755, true);
        }
        
        // Get the real path of the temporary uploaded file
        $tempFilePath = $this->productImage->getRealPath();
        
        // Define destination path
        $destinationPath = $publicPath . DIRECTORY_SEPARATOR . $safeFileName;
        
        // Copy file from temp to destination
        copy($tempFilePath, $destinationPath);
        
        // Return the relative path for database storage (web-friendly with forward slashes)
        return $targetDir . '/' . $safeFileName;
    }

    private function deleteOldImage($imagePath, $animalName = null)
    {
        // Accept full path or construct from filename and animal name
        $fullPath = public_path($imagePath);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    private function resetForm()
    {
        $this->reset([
            'productId', 'specificationId', 'name', 'category_id', 
            'animal_id', 'details', 'benefits', 'nutrition', 
            'productImage', 'existingImage', 'specName', 'price', 'stock'
        ]);
    }

    public function render()
    {
        // Get all specifications with their products, with search
        $specifications = Specification::with(['product.animal', 'product.category'])
            ->when($this->search, function($query) {
                $query->whereHas('product', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhereHas('category', function($catQ) {
                          $catQ->where('name', 'like', '%' . $this->search . '%');
                      });
                })
                ->orWhere('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        $categories = Category::all();
        $animals = Animal::all();

        return view('livewire.employee.manage-products', [
            'specifications' => $specifications,
            'categories' => $categories,
            'animals' => $animals,
        ])->layout('layouts.app');
    }
}
