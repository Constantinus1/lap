<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key constraints temporarily
        DB::statement('PRAGMA foreign_keys = OFF');

        // Rename the old table
        Schema::rename('order_items', 'order_items_old');

        // Create new table with correct foreign key
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });

        // Copy data from old table to new table
        DB::statement('INSERT INTO order_items (id, order_id, product_id, quantity, price, created_at, updated_at)
                       SELECT id, order_id, product_id, quantity, price, created_at, updated_at
                       FROM order_items_old');

        // Drop the old table
        Schema::dropIfExists('order_items_old');

        // Re-enable foreign key constraints
        DB::statement('PRAGMA foreign_keys = ON');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not implementing down as this is a structural fix
    }
};
