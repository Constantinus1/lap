<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(private CartService $cartService) {}

    public function createOrderFromCart(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $cartItems = $this->cartService->getCartItems();
            $total = $this->cartService->getCartTotal();

            $order = Order::create([
                'user_id' => Auth::id(),
                'address_id' => $data['address_id'] ?? null,
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'order_amount' => $total,
                'order_date' => now(),
                'payment_method' => $data['payment_method'],
                'shipping_address' => $data['shipping_address'],
                'shipping_city' => $data['shipping_city'],
                'shipping_state' => $data['shipping_state'],
                'shipping_country' => $data['shipping_country'],
                'shipping_pin_code' => $data['shipping_pin_code'],
            ]);

            foreach ($cartItems as $item) {
                // Lock product row to prevent race conditions
                $product = Product::where('id', $item['product_id'])
                    ->lockForUpdate()
                    ->first();

                // Check if product is available
                if (!$product || !$product->is_available) {
                    throw new \Exception("Product {$item['product_name']} is no longer available");
                }

                // Check stock (another order might have just bought the last items)
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Not enough stock for {$product->name}. Only {$product->stock} available");
                }

                // Decrement stock
                $product->decrement('stock', $item['quantity']);

                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['product_price'],
                ]);
            }

            $this->cartService->clearCart();
            session(['last_order_id' => $order->id]);

            return $order;
        });
    }

    public function prepareUserOrderData(array $request): array
    {
        $user = Auth::user();
        $data = [
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'payment_method' => $request['payment_method'],
            'address_id' => null,
        ];

        if (!empty($request['new_address'])) {
            return array_merge($data, $this->extractShippingData($request));
        }

        $address = Address::findOrFail($request['address_id']);
        return array_merge($data, [
            'address_id' => $address->id,
            'shipping_address' => $address->address ?? '',
            'shipping_city' => $address->city,
            'shipping_state' => $address->state,
            'shipping_country' => $address->country,
            'shipping_pin_code' => $address->pin_code,
        ]);
    }

    public function prepareGuestOrderData(array $request): array
    {
        return array_merge(
            $this->extractShippingData($request),
            [
                'customer_name' => $request['customer_name'],
                'customer_email' => $request['customer_email'],
                'payment_method' => $request['payment_method'],
                'address_id' => null,
            ]
        );
    }

    public function canAccessInvoice(Order $order): bool
    {
        return Auth::check()
            ? $order->user_id === Auth::id()
            : session('last_order_id') === $order->id;
    }

    private function extractShippingData(array $data): array
    {
        return [
            'shipping_address' => $data['shipping_address'],
            'shipping_city' => $data['shipping_city'],
            'shipping_state' => $data['shipping_state'],
            'shipping_country' => $data['shipping_country'],
            'shipping_pin_code' => $data['shipping_pin_code'],
        ];
    }
}
