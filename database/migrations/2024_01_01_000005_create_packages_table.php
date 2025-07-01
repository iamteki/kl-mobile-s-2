<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('category'); // Basic, Professional, Premium, Custom
            $table->decimal('price', 10, 2);
            $table->string('suitable_for')->nullable(); // 50-100 pax, 100-300 pax, etc.
            $table->json('features')->nullable(); // List of features
            $table->json('items')->nullable(); // List of included items with quantities
            $table->integer('service_duration')->default(8); // in hours
            $table->string('badge')->nullable(); // Most Popular, Best Value, etc.
            $table->string('image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('featured')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            $table->index('slug');
            $table->index('category');
            $table->index('status');
            $table->index('featured');
        });
    }

    public function down()
    {
        Schema::dropIfExists('packages');
    }
};