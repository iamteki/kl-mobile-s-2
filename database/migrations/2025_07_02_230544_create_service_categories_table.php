<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Professional DJs, Photographers, etc.
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // fas fa-music, fas fa-camera, etc.
            $table->string('image')->nullable();
            $table->string('parent_category')->nullable(); // Entertainment, Media Production, etc.
            $table->integer('sort_order')->default(0);
            $table->boolean('show_on_homepage')->default(true);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            $table->index('slug');
            $table->index('status');
            $table->index('parent_category');
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_categories');
    }
};