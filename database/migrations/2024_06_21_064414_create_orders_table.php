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
            // buyer_id is a foreign key from buyers table
            $table->foreignId('buyer_id')->constrained('buyers')->onDelete('cascade');
            // driver_id is a foreign key from drivers table
            // merchant_id is a foreign key from merchants table
            $table->foreignId('merchant_id')->constrained('merchants')->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->onDelete('set null');
            // total price indonesia currency
            $table->bigInteger('total_price');
            // shipping cost
            $table->bigInteger('shipping_cost');
            // total bill
            $table->bigInteger('total_bill');
            // payment method
            $table->string('payment_method');
            //status
            $table->string('status')->default('pending');
            // shipping latitude decimal
            $table->decimal('shipping_latitude', 10, 8);
            // shipping longitude decimal
            $table->decimal('shipping_longitude', 10, 8);
            // shipping address detail
            $table->string('shipping_address_detail');
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
