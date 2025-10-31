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
        // For SQLite, we need to recreate the table to make columns nullable
        // This is the standard approach without doctrine/dbal

        // Disable foreign key constraints temporarily
        DB::statement('PRAGMA foreign_keys = OFF');

        // Step 1: Rename the old table
        Schema::rename('orders', 'orders_old');

        // Step 2: Create new table with nullable foreign keys
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->decimal('order_amount', 10, 2);
            $table->timestamp('order_date');
            $table->string('shipping_address')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('shipping_pin_code')->nullable();
            $table->timestamps();
        });

        // Step 3: Copy data from old table to new table
        // Only copy columns that exist in orders_old (customer_name and shipping fields may not exist yet)
        $columns = DB::select("PRAGMA table_info(orders_old)");
        $columnNames = array_map(fn($col) => $col->name, $columns);

        if (in_array('customer_name', $columnNames)) {
            // New schema with all fields
            DB::statement('INSERT INTO orders (id, user_id, address_id, customer_name, customer_email, order_amount, order_date, shipping_address, shipping_city, shipping_state, shipping_country, shipping_pin_code, created_at, updated_at)
                           SELECT id, user_id, address_id, customer_name, customer_email, order_amount, order_date, shipping_address, shipping_city, shipping_state, shipping_country, shipping_pin_code, created_at, updated_at
                           FROM orders_old');
        } else {
            // Old schema with only basic fields
            DB::statement('INSERT INTO orders (id, user_id, address_id, order_amount, order_date, created_at, updated_at)
                           SELECT id, user_id, address_id, order_amount, order_date, created_at, updated_at
                           FROM orders_old');
        }

        // Step 4: Drop the old table
        Schema::dropIfExists('orders_old');

        // Re-enable foreign key constraints
        DB::statement('PRAGMA foreign_keys = ON');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the process
        Schema::rename('orders', 'orders_new');

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('address_id')->constrained()->cascadeOnDelete();
            $table->decimal('order_amount', 10, 2);
            $table->timestamp('order_date');
            $table->timestamps();
        });

        DB::statement('INSERT INTO orders (id, user_id, address_id, order_amount, order_date, created_at, updated_at)
                       SELECT id, user_id, address_id, order_amount, order_date, created_at, updated_at
                       FROM orders_new WHERE user_id IS NOT NULL AND address_id IS NOT NULL');

        Schema::dropIfExists('orders_new');
    }
};
