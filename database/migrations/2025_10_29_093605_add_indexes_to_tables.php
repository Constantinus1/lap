<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add indexes to improve query performance
        Schema::table('carts', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('created_at');
            $table->index(['user_id', 'created_at']); // Composite for order history
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->index('order_id');
            $table->index('product_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index('order_id');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->index('cart_id');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['user_id', 'created_at']);
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['product_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndex(['cart_id']);
            $table->dropIndex(['product_id']);
        });
    }
};
