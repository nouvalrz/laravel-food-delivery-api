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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // name
            $table->string('name');
            // desc
            $table->text('description');
            // price indonesia currency
            $table->bigInteger('price');
            // stock
            $table->integer('stock');
            // is_available
            $table->boolean('is_available')->default(true);
            // is_favorite
            $table->boolean('is_favorite')->default(false);
            // image
            $table->string('image');
            // merchant_id
            $table->foreignId('merchant_id')->constrained('merchants')->onDelete('cascade');
            $table->timestamps();
            // soft delete
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
