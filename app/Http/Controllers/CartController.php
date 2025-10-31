<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    public function index()
    {
        return Inertia::render('Cart', [
            'cartItems' => $this->cartService->getCartItems(),
            'total' => $this->cartService->getCartTotal(),
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
        ]);

        try {
            $this->cartService->addItem($request->product_id, $request->quantity ?? 1);
            return redirect()->back()->with('success', 'Product added to cart');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $this->cartService->updateQuantity($id, $request->quantity);
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function remove($id)
    {
        $this->cartService->removeItem($id);

        return redirect()->back();
    }
}

