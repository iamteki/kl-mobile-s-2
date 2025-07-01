<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('company')->nullable();
            $table->string('company_registration')->nullable();
            $table->string('tax_id')->nullable();
            $table->enum('customer_type', ['individual', 'corporate'])->default('individual');
            $table->integer('total_bookings')->default(0);
            $table->decimal('total_spent', 12, 2)->default(0);
            $table->date('last_booking_date')->nullable();
            $table->json('preferences')->nullable(); // Store customer preferences
            $table->boolean('newsletter_subscribed')->default(false);
            $table->boolean('sms_notifications')->default(false);
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('customer_type');
            $table->index('company');
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};