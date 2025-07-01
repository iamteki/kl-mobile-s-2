<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            
            // Item Type (product, service, or package)
            $table->enum('item_type', ['product', 'service', 'package']);
            
            // Polymorphic relation
            $table->unsignedBigInteger('item_id');
            $table->string('item_name'); // Denormalized for historical record
            $table->string('item_sku')->nullable();
            
            // If product with variation
            $table->foreignId('product_variation_id')->nullable()->constrained('product_variations');
            $table->string('variation_name')->nullable();
            
            // Quantity and Pricing
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->integer('rental_days');
            
            // Additional Options
            $table->json('selected_addons')->nullable();
            $table->decimal('addons_price', 10, 2)->default(0);
            
            // Status
            $table->enum('status', ['pending', 'confirmed', 'delivered', 'returned', 'damaged', 'lost'])->default('pending');
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            $table->index('booking_id');
            $table->index(['item_type', 'item_id']);
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_items');
    }
};