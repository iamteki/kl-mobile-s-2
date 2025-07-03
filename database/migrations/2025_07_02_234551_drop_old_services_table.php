<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop the old services table
        Schema::dropIfExists('services');
    }

    public function down()
    {
        // Recreate the old services table if rolling back
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category');
            $table->text('description')->nullable();
            $table->decimal('starting_price', 10, 2);
            $table->string('price_unit')->default('event');
            $table->json('features')->nullable();
            $table->string('experience_level')->nullable();
            $table->json('languages')->nullable();
            $table->json('genres_specialties')->nullable();
            $table->integer('min_duration')->default(1);
            $table->integer('max_duration')->nullable();
            $table->string('image')->nullable();
            $table->string('badge')->nullable();
            $table->string('badge_class')->nullable();
            $table->boolean('equipment_included')->default(false);
            $table->json('additional_charges')->nullable();
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
};