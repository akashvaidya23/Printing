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
        Schema::table('orders_products', function (Blueprint $table) {
            $table->text('size')->after('product_id')->nullable();
            $table->decimal('quantity',10,2)->after('product_id')->nullable();
            $table->decimal('total', 10, 2)->after('product_id')->nullable();
            $table->decimal('price', 10, 2)->after('product_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders_products', function (Blueprint $table) {
            //
        });
    }
};
