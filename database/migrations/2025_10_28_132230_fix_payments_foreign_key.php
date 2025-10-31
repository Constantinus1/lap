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
        Schema::rename('payments', 'payments_old');

        // Create new table with correct foreign key
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->enum('method', ['invoice', 'credit_card']);
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });

        // Copy data from old table to new table
        DB::statement('INSERT INTO payments (id, order_id, method, amount, created_at, updated_at)
                       SELECT id, order_id, method, amount, created_at, updated_at
                       FROM payments_old');

        // Drop the old table
        Schema::dropIfExists('payments_old');

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
