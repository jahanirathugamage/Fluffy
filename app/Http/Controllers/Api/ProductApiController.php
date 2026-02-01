<?php
// app\Http\Controllers\Api\ProductApiController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['animal', 'category', 'specifications']);

        if ($request->animal_id) {
            $query->where('animal_id', $request->animal_id);
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        return response()->json($query->paginate(10));
    }

    public function show(Product $product)
    {
        return response()->json(
            $product->load(['animal', 'category', 'specifications'])
        );
    }
}
