<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            // 1. Hapus constraint unik lama (jika ada)
            // $table->dropUnique(['user_id', 'product_id']);
            
            // 2. Tambahkan constraint unik baru
            $table->unique(['user_id', 'product_id', 'variant_id'], 'carts_user_product_variant_unique');
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            // 1. Hapus constraint unik baru
            // $table->dropUnique('carts_user_id_product_id_variant_id_unique');
            
            // 2. Kembalikan constraint unik lama
            // $table->unique(['user_id', 'product_id']);
        });
    }
};