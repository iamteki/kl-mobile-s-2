<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('booking_items', function (Blueprint $table) {
            // Update item_type enum to include service_provider
            $table->enum('item_type', ['product', 'service', 'service_provider', 'package'])
                ->default('product')
                ->change();
            
            // Add service provider specific fields
            $table->foreignId('service_provider_id')->nullable()->after('item_id');
            $table->foreignId('service_provider_pricing_id')->nullable()->after('service_provider_id');
            $table->time('start_time')->nullable()->after('rental_days');
            $table->time('end_time')->nullable()->after('start_time');
            
            // Add indexes
            $table->index('service_provider_id');
            $table->index('service_provider_pricing_id');
        });
    }

    public function down()
    {
        Schema::table('booking_items', function (Blueprint $table) {
            $table->dropIndex(['service_provider_id']);
            $table->dropIndex(['service_provider_pricing_id']);
            
            $table->dropColumn('service_provider_id');
            $table->dropColumn('service_provider_pricing_id');
            $table->dropColumn('start_time');
            $table->dropColumn('end_time');
            
            // Revert item_type enum
            $table->enum('item_type', ['product', 'service', 'package'])
                ->default('product')
                ->change();
        });
    }
};