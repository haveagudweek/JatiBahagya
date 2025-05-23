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
        Schema::table('order_items', function (Blueprint $table) {
            // Add variant_id column
            $table->unsignedBigInteger('variant_id')->nullable()->after('product_id');
            
            // Add options column for variant attributes
            $table->json('options')->nullable()->after('price_per_item');
            
            // Add index for variant_id
            $table->index('variant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('variant_id');
            $table->dropColumn('options');
        });
    }
};
