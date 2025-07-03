<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('service_provider_pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_provider_id')->constrained()->onDelete('cascade');
            $table->string('tier_name'); // Basic, Standard, Premium
            $table->decimal('price', 10, 2);
            $table->string('duration'); // 4 hours, 8 hours, Full day
            $table->json('included_features')->nullable();
            $table->json('additional_costs')->nullable(); // Travel, overtime, etc.
            $table->integer('sort_order')->default(0);
            $table->boolean('is_popular')->default(false);
            $table->timestamps();
            
            $table->index('service_provider_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_provider_pricing');
    }
};