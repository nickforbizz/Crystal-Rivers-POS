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
            $table->unsignedBigInteger('fk_user');
            $table->foreign('fk_user')->references('id')->on('users');
            $table->unsignedBigInteger('fk_customer');
            $table->foreign('fk_customer')->references('id')->on('customers');
            $table->string('order_ID')->unique();
            $table->dateTime('order_date');
            $table->double('amount', 8, 2);
            $table->enum('status', ['Initiated', 'Processing', 'completed', 'cancelled', 'inactive']);
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
        Schema::dropIfExists('orders');
    }
};
