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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_user');
            $table->foreign('fk_user')->references('id')->on('users');
            $table->unsignedBigInteger('fk_product');
            $table->foreign('fk_product')->references('id')->on('products');
            $table->unsignedBigInteger('fk_order');
            $table->foreign('fk_order')->references('id')->on('orders');
            $table->integer('quantity');
            $table->double('unit_price', 8, 2);
            $table->double('amount', 8, 2);
            $table->string('active')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
