<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::findOrFail($id);

        return Inertia::render('ProductDetail', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'description' => $product->description,
                'stock' => $product->stock,
                'is_available' => $product->is_available,
                'images' => $product->getImages(),
            ],
        ]);
    }
}
