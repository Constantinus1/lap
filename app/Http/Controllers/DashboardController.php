<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Get top 5 most ordered products
        $mostOrdered = Product::select('products.id', 'products.name', 'products.image', 'products.price')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->selectRaw('SUM(order_items.quantity) as total_ordered')
            ->groupBy('products.id', 'products.name', 'products.image', 'products.price')
            ->orderByDesc('total_ordered')
            ->limit(5)
            ->get();

        // Get top 5 least ordered products
        $leastOrdered = Product::select('products.id', 'products.name', 'products.image', 'products.price')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as total_ordered')
            ->groupBy('products.id', 'products.name', 'products.image', 'products.price')
            ->orderBy('total_ordered')
            ->limit(5)
            ->get();

        // Get all orders from the last 4 weeks
        $recentOrders = Order::with(['items.product'])
            ->where('order_date', '>=', now()->subWeeks(4))
            ->orderByDesc('order_date')
            ->get()
            ->map(fn($order) => [
                'id' => $order->id,
                'customer_name' => $order->customer_name ?? $order->user?->name,
                'customer_email' => $order->customer_email ?? $order->user?->email,
                'order_amount' => $order->order_amount,
                'order_date' => $order->order_date->format('d.m.Y H:i'),
                'payment_method' => $order->payment_method,
                'items_count' => $order->items->sum('quantity'),
            ]);

        return Inertia::render('Dashboard', [
            'mostOrdered' => $mostOrdered,
            'leastOrdered' => $leastOrdered,
            'recentOrders' => $recentOrders,
        ]);
    }
}
