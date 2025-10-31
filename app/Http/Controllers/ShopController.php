<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::all()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'description' => $product->description,
                'stock' => $product->stock,
                'is_available' => $product->is_available,
                'image' => $product->getFirstImage(),
            ];
        });

        return Inertia::render('Shop', [
            'products' => $products,
        ]);
    }
}
