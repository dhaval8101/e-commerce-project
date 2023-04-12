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
            $table->foreignId('cart_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->integer('quantity');
            $table->decimal('price', 8, 2);
            $table->string('payment_method');
            $table->string('status');
            $table->string('phone');
            $table->string('address');
            $table->string('city');
            $table->string('pin_code');
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