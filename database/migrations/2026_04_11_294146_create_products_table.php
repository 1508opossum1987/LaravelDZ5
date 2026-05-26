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
            $table->foreignId('category_id')->constrained();
            $table->foreignId('country_id')->constrained();
            $table->foreignId('brand_id')->constrained();

            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->string('img_path')->nullable();
            // цены
            $table->decimal('price', 10, 2); // обычная цена
            $table->decimal('discount_price', 10, 2)->nullable(); // цена со скидкой
            $table->decimal('price_from', 10, 2)->nullable(); // "от"
            $table->timestamps();
            $table->integer('stock_quantity')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
