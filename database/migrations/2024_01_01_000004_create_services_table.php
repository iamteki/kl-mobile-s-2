<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category'); // Entertainment, Technical Crew, Media Production, Event Staff
            $table->text('description')->nullable();
            $table->decimal('starting_price', 10, 2);
            $table->string('price_unit')->default('event'); // event, day, hour
            $table->json('features')->nullable();
            $table->string('experience_level')->nullable(); // Entry, Professional, Premium
            $table->json('languages')->nullable();
            $table->json('genres_specialties')->nullable();
            $table->integer('min_duration')->default(1); // in hours
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

    public function down()
    {
        Schema::dropIfExists('services');
    }
};