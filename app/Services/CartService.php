<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function getCartItems(): Collection
    {
        return Auth::check()
            ? $this->getDatabaseCartItems()
            : $this->getSessionCartItems();
    }

    public function getCartCount(): int
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            return $cart ? $cart->items()->sum('quantity') : 0;
        }

        return array_sum(session('cart', []));
    }

    public function getCartTotal(): float
    {
        return $this->getCartItems()->sum('subtotal');
    }

    public function addItem(int $productId, int $quantity = 1): void
    {
        // Validate product exists and check stock
        $product = Product::findOrFail($productId);

        if (!$product->is_available) {
            throw new \Exception('Product is not available');
        }

        // Check if requested quantity exceeds available stock
        $currentItem = $this->getCartItems()->firstWhere('product_id', $productId);
        $currentQuantity = $currentItem ? $currentItem['quantity'] : 0;
        $totalQuantity = $currentQuantity + $quantity;

        if ($totalQuantity > $product->stock) {
            throw new \Exception("Not enough stock. Only {$product->stock} available");
        }

        Auth::check()
            ? $this->addToDatabaseCart($productId, $quantity)
            : $this->addToSessionCart($productId, $quantity);
    }

    public function updateQuantity(int $itemId, int $quantity): void
    {
        if (Auth::check()) {
            $cartItem = CartItem::whereHas('cart', function ($query) {
                $query->where('user_id', Auth::id());
            })->findOrFail($itemId);

            // Check stock before updating
            $product = Product::findOrFail($cartItem->product_id);
            if ($quantity > $product->stock) {
                throw new \Exception("Not enough stock. Only {$product->stock} available");
            }

            $cartItem->update(['quantity' => $quantity]);
        } else {
            $cart = session('cart', []);
            if (isset($cart[$itemId])) {
                // Check stock before updating
                $product = Product::findOrFail($itemId);
                if ($quantity > $product->stock) {
                    throw new \Exception("Not enough stock. Only {$product->stock} available");
                }

                $cart[$itemId] = $quantity;
                session(['cart' => $cart]);
            }
        }
    }

    public function removeItem(int $itemId): void
    {
        if (Auth::check()) {
            $cartItem = CartItem::whereHas('cart', function ($query) {
                $query->where('user_id', Auth::id());
            })->findOrFail($itemId);

            $cartItem->delete();
        } else {
            $cart = session('cart', []);
            unset($cart[$itemId]);
            session(['cart' => $cart]);
        }
    }

    public function clearCart(): void
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->first()?->items()->delete();
        } else {
            session()->forget('cart');
        }
    }

    public function isEmpty(): bool
    {
        return $this->getCartItems()->isEmpty();
    }

    private function getDatabaseCartItems(): Collection
    {
        $cart = Cart::with(['items.product'])->where('user_id', Auth::id())->first();

        return $cart ? $cart->items->map(fn($item) => [
            'id' => $item->id,
            'product_id' => $item->product_id,
            'product_name' => $item->product->name,
            'product_price' => $item->product->price,
            'product_image' => $item->product->image,
            'quantity' => $item->quantity,
            'subtotal' => $item->product->price * $item->quantity,
        ]) : collect([]);
    }

    private function getSessionCartItems(): Collection
    {
        $sessionCart = session('cart', []);
        $cartItems = collect([]);

        foreach ($sessionCart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $cartItems->push([
                    'id' => $productId,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $product->price,
                    'product_image' => $product->image,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity,
                ]);
            }
        }

        return $cartItems;
    }

    private function addToDatabaseCart(int $productId, int $quantity): void
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        $existingItem
            ? $existingItem->increment('quantity', $quantity)
            : CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
    }

    private function addToSessionCart(int $productId, int $quantity): void
    {
        $cart = session('cart', []);
        $cart[$productId] = ($cart[$productId] ?? 0) + $quantity;
        session(['cart' => $cart]);
    }
}
