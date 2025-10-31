<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private OrderService $orderService
    ) {}

    public function index()
    {
        if ($this->cartService->isEmpty()) {
            return redirect()->route('shop')->with('error', 'Your cart is empty');
        }

        $addresses = auth()->check()
            ? Address::where('user_id', auth()->id())->get()
            : collect([]);

        return Inertia::render('Checkout', [
            'cartItems' => $this->cartService->getCartItems(),
            'total' => $this->cartService->getCartTotal(),
            'addresses' => $addresses,
            'isGuest' => !auth()->check(),
        ]);
    }

    public function placeOrder(Request $request)
    {
        $this->validateOrder($request);

        if ($this->cartService->isEmpty()) {
            return redirect()->route('shop')->with('error', 'Your cart is empty');
        }

        $orderData = auth()->check()
            ? $this->orderService->prepareUserOrderData($request->all())
            : $this->orderService->prepareGuestOrderData($request->all());

        try {
            $order = $this->orderService->createOrderFromCart($orderData);
            return redirect()->route('order.invoice', $order->id);
        } catch (\Exception $e) {
            return redirect()->route('checkout')->with('error', $e->getMessage());
        }
    }

    private function validateOrder(Request $request): void
    {
        $rules = [
            'payment_method' => 'required|in:invoice,credit_card',
            'shipping_address' => 'required_without:address_id|string',
            'shipping_city' => 'required_without:address_id|string',
            'shipping_state' => 'required_without:address_id|string',
            'shipping_country' => 'required_without:address_id|string',
            'shipping_pin_code' => 'required_without:address_id|string',
        ];

        if (auth()->check()) {
            $rules['address_id'] = 'nullable|exists:addresses,id';
            $rules['new_address'] = 'nullable|boolean';
        } else {
            $rules['customer_name'] = 'required|string';
            $rules['customer_email'] = 'required|email';
        }

        $request->validate($rules);
    }

    public function showInvoice($orderId)
    {
        $order = Order::with(['user', 'address', 'items.product'])->findOrFail($orderId);

        if (!$this->orderService->canAccessInvoice($order)) {
            abort(403);
        }

        return Inertia::render('Invoice', [
            'order' => [
                'id' => $order->id,
                'order_date' => $order->order_date->format('d.m.Y'),
                'order_amount' => $order->order_amount,
                'user_name' => $order->customer_name ?? $order->user->name,
                'user_email' => $order->customer_email ?? $order->user->email,
                'address' => [
                    'address' => $order->shipping_address,
                    'city' => $order->shipping_city,
                    'state' => $order->shipping_state,
                    'country' => $order->shipping_country,
                    'pin_code' => $order->shipping_pin_code,
                ],
                'items' => $order->items->map(fn($item) => [
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->price * $item->quantity,
                ]),
                'payment_method' => $order->payment_method,
            ],
        ]);
    }
}
