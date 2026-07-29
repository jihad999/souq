<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('orders')
        ->whereIn('payment_method', ['paypal', 'hyperpay'])
        ->update(['payment_method' => 'cod']);
        DB::statement("ALTER TABLE orders MODIFY payment_method ENUM('stripe', 'paytabs', 'cod') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY payment_method ENUM('paypal', 'stripe', 'paytabs', 'hyperpay', 'cod') NOT NULL");
    }
};
