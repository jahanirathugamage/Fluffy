<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\AuditLogger;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        $product->load(['animal', 'category', 'specifications']);

        AuditLogger::log([
            'action' => 'PRODUCT_VIEW',
            'entity' => 'product',
            'entity_id' => $product->id,
            'performed_by' => auth()->check() ? auth()->user()->email : 'guest',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return view('products.show', compact('product'));
    }
}
