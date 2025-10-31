<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 20 products
        $products = Product::factory(20)->create();

        // Create 5 users with addresses
        $users = User::factory(5)->create()->each(function ($user) {
            // Create 1-2 addresses per user
            Address::factory(rand(1, 2))->create([
                'user_id' => $user->id,
            ]);
        });

        // Create 10 orders with order items and payments
        for ($i = 0; $i < 10; $i++) {
            $user = $users->random();
            $address = $user->addresses()->inRandomOrder()->first();

            // Create order with random payment method
            $order = Order::factory()->create([
                'user_id' => $user->id,
                'address_id' => $address->id,
                'payment_method' => ['invoice', 'credit_card'][rand(0, 1)],
            ]);

            // Add 1-4 random products to the order
            $orderProducts = $products->random(rand(1, 4));
            $totalAmount = 0;

            foreach ($orderProducts as $product) {
                $quantity = rand(1, 3);
                $price = $product->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);

                $totalAmount += $price * $quantity;
            }

            // Update order amount
            $order->update(['order_amount' => $totalAmount]);
        }
    }
}
