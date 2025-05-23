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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('user_address_id')->constrained('user_addresses')->onDelete('cascade');
            $table->string('order_code')->unique();
            $table->decimal('total_order', 12, 2); // Total harga barang sebelum biaya lain
            $table->decimal('total_shipping', 12, 2)->default(0); // Biaya pengiriman
            $table->decimal('total_fee', 12, 2)->default(0); // Biaya tambahan seperti pajak atau admin
            $table->decimal('amount', 12, 2); // Jumlah akhir yang harus dibayar (total_order + total_shipping + total_fee)
            $table->enum('status', ['pending', 'paid', 'process', 'shipped', 'completed', 'canceled'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
            $table->text('shipping_address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
