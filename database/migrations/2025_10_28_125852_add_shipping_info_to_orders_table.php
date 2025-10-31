<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Guest customer info
            $table->string('customer_name')->nullable()->after('address_id');
            $table->string('customer_email')->nullable()->after('customer_name');

            // Shipping address stored directly in order
            $table->string('shipping_address')->nullable()->after('customer_email');
            $table->string('shipping_city')->nullable()->after('shipping_address');
            $table->string('shipping_state')->nullable()->after('shipping_city');
            $table->string('shipping_country')->nullable()->after('shipping_state');
            $table->string('shipping_pin_code')->nullable()->after('shipping_country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name',
                'customer_email',
                'shipping_address',
                'shipping_city',
                'shipping_state',
                'shipping_country',
                'shipping_pin_code'
            ]);
        });
    }
};
