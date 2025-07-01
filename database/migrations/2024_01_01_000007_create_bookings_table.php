<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->foreignId('customer_id')->constrained();
            
            // Event Details
            $table->date('event_date');
            $table->string('event_type'); // Wedding, Birthday, Corporate, etc.
            $table->text('venue');
            $table->integer('number_of_pax');
            $table->time('installation_time');
            $table->time('event_start_time');
            $table->time('dismantle_time');
            
            // Rental Period
            $table->date('rental_start_date');
            $table->date('rental_end_date');
            $table->integer('rental_days');
            
            // Pricing
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('coupon_code')->nullable();
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->decimal('total', 12, 2);
            
            // Payment
            $table->enum('payment_status', ['pending', 'paid', 'partial', 'refunded', 'failed'])->default('pending');
            $table->enum('payment_method', ['stripe', 'bank_transfer', 'cash'])->nullable();
            $table->string('stripe_payment_intent_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            // Booking Status
            $table->enum('booking_status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'])->default('pending');
            
            // Delivery Details
            $table->text('delivery_address')->nullable();
            $table->text('delivery_instructions')->nullable();
            $table->enum('delivery_status', ['pending', 'scheduled', 'delivered', 'picked_up'])->default('pending');
            
            // Additional Information
            $table->text('special_requests')->nullable();
            $table->text('internal_notes')->nullable();
            $table->boolean('insurance_opted')->default(false);
            $table->decimal('insurance_amount', 10, 2)->default(0);
            
            // Contact Details (denormalized for historical record)
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('customer_company')->nullable();
            
            $table->timestamps();
            
            $table->index('booking_number');
            $table->index('customer_id');
            $table->index('event_date');
            $table->index('booking_status');
            $table->index('payment_status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};