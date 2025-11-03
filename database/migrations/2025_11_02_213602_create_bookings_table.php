<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients');
            $table->foreignId('doctor_id')->constrained('doctors');
            $table->date('booking_date');
            $table->time('booking_time');
            $table->enum('status', ['Pending', 'Confirmed', 'Completed', 'Cancelled', 'Rescheduled'])->default('Pending');;
            $table->enum('payment_method', ['PayPal','Stripe', 'Cash'])->default('Cash');
            $table->enum('payment_status', ['Pending','Processing','Paid','Failed','Cancelled','Refunded'])->default('Pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
