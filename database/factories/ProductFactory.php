<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = [
            ['name' => 'Laptop Dell XPS 15', 'price' => 1499.99, 'description' => 'High-performance laptop with Intel i7 processor, 16GB RAM, and 512GB SSD'],
            ['name' => 'iPhone 14 Pro', 'price' => 1099.99, 'description' => 'Latest iPhone with A16 Bionic chip, 128GB storage, and Pro camera system'],
            ['name' => 'Samsung Galaxy S23', 'price' => 899.99, 'description' => 'Flagship Android phone with Snapdragon 8 Gen 2 and amazing camera'],
            ['name' => 'Sony WH-1000XM5 Headphones', 'price' => 399.99, 'description' => 'Premium noise-cancelling wireless headphones'],
            ['name' => 'iPad Air', 'price' => 599.99, 'description' => 'Powerful tablet with M1 chip and 10.9-inch Liquid Retina display'],
            ['name' => 'MacBook Pro 14"', 'price' => 1999.99, 'description' => 'Professional laptop with M2 Pro chip, 16GB RAM, and stunning display'],
            ['name' => 'Canon EOS R6', 'price' => 2499.99, 'description' => 'Full-frame mirrorless camera with 20MP sensor'],
            ['name' => 'Nintendo Switch OLED', 'price' => 349.99, 'description' => 'Gaming console with vibrant OLED screen'],
            ['name' => 'Apple Watch Series 8', 'price' => 399.99, 'description' => 'Advanced smartwatch with health monitoring features'],
            ['name' => 'Bose QuietComfort Earbuds', 'price' => 279.99, 'description' => 'Premium wireless earbuds with noise cancellation'],
            ['name' => 'LG OLED TV 55"', 'price' => 1299.99, 'description' => '4K OLED television with stunning picture quality'],
            ['name' => 'Kindle Paperwhite', 'price' => 139.99, 'description' => 'E-reader with adjustable warm light and waterproof design'],
            ['name' => 'DJI Mini 3 Pro', 'price' => 759.99, 'description' => 'Compact drone with 4K camera and 34-minute flight time'],
            ['name' => 'GoPro Hero 11', 'price' => 499.99, 'description' => 'Action camera with 5.3K video and HyperSmooth stabilization'],
            ['name' => 'Logitech MX Master 3S', 'price' => 99.99, 'description' => 'Ergonomic wireless mouse for productivity'],
            ['name' => 'Samsung T7 Portable SSD 1TB', 'price' => 129.99, 'description' => 'Fast external SSD with USB 3.2 Gen 2'],
            ['name' => 'Razer BlackWidow V3', 'price' => 139.99, 'description' => 'Mechanical gaming keyboard with RGB lighting'],
            ['name' => 'Fitbit Charge 5', 'price' => 149.99, 'description' => 'Advanced fitness tracker with built-in GPS'],
            ['name' => 'Ring Video Doorbell Pro', 'price' => 249.99, 'description' => 'Smart doorbell with 1080p HD video'],
            ['name' => 'Dyson V15 Detect', 'price' => 649.99, 'description' => 'Cordless vacuum with laser dust detection'],
            ['name' => 'Philips Hue Starter Kit', 'price' => 199.99, 'description' => 'Smart lighting system with color bulbs and bridge'],
        ];

        static $index = 0;
        $product = $products[$index % count($products)];
        $index++;

        return [
            'name' => $product['name'],
            'price' => $product['price'],
            'description' => $product['description'],
            'stock' => $this->faker->numberBetween(0, 100),
            'is_available' => $this->faker->boolean(90), // 90% chance of being available
        ];
    }
}
