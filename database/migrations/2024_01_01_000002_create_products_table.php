<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->string('brand')->nullable();
            $table->string('subcategory')->nullable();
            $table->text('short_description')->nullable();
            $table->text('detailed_description')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->string('price_unit')->default('day');
            $table->integer('min_quantity')->default(1);
            $table->integer('max_quantity')->default(10);
            $table->integer('available_quantity')->default(0);
            $table->integer('sort_order')->default(0);
            $table->boolean('featured')->default(false);
            $table->json('included_items')->nullable();
            $table->json('addons')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            $table->index('slug');
            $table->index('sku');
            $table->index('category_id');
            $table->index('status');
            $table->index('featured');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};