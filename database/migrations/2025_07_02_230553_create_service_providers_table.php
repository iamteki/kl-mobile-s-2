<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('service_providers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_category_id')->constrained()->onDelete('cascade');
            $table->string('name'); // DJ Mike, John Photography, etc.
            $table->string('slug')->unique();
            $table->string('stage_name')->nullable(); // DJ Blazer, etc.
            $table->text('bio')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->string('price_unit')->default('event'); // event, day, hour
            $table->json('features')->nullable(); // What's included
            $table->string('experience_level')->nullable(); // Entry, Professional, Premium
            $table->integer('years_experience')->default(0);
            $table->json('languages')->nullable();
            $table->json('specialties')->nullable(); // Genres for DJs, Event types for photographers
            $table->json('equipment_owned')->nullable(); // Their own equipment
            $table->boolean('equipment_provided')->default(false); // If we provide equipment
            $table->integer('min_booking_hours')->default(1);
            $table->integer('max_booking_hours')->nullable();
            $table->json('availability')->nullable(); // Days/times available
            $table->json('portfolio_links')->nullable(); // YouTube, Instagram, etc.
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_reviews')->default(0);
            $table->integer('total_bookings')->default(0);
            $table->string('badge')->nullable(); // Most Popular, Top Rated, etc.
            $table->string('badge_class')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('featured')->default(false);
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->timestamps();
            
            $table->index('service_category_id');
            $table->index('slug');
            $table->index('status');
            $table->index('featured');
            $table->index('experience_level');
            $table->index('rating');
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_providers');
    }
};