<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            // Tambahkan kolom variant_id jika belum ada
            if (!Schema::hasColumn('carts', 'variant_id')) {
                $table->foreignId('variant_id')
                    ->nullable()
                    ->constrained('product_variants')
                    ->cascadeOnDelete();
            }

            // Tambahkan kolom price jika belum ada
            if (!Schema::hasColumn('carts', 'price')) {
                $table->decimal('price', 15, 2)->nullable();
            }

            // Tambahkan kolom options (disimpan dalam format JSON)
            if (!Schema::hasColumn('carts', 'options')) {
                $table->json('options')->nullable();
            }

            // Tambah unique index untuk mencegah duplikat
            // $table->unique(['user_id', 'product_id', 'variant_id'], 'cart_unique_user_product_variant');
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->dropColumn(['variant_id', 'price', 'options']);
            $table->dropUnique('cart_unique_user_product_variant');
        });
    }
};