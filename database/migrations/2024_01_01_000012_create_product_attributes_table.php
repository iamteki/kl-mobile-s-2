<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('attribute_key');
            $table->text('attribute_value');
            $table->string('attribute_type')->default('text'); // text, number, boolean, date, select
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['product_id', 'attribute_key']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_attributes');
    }
};