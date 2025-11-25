<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('doctor_amount', 5, 2)->nullable()->default(123.45);
            $table->decimal('rate', 5, 2)->nullable()->default(123.45);
            $table->decimal('total', 5, 2)->nullable()->default(123.45);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            //
            $table->decimal('doctor_amount');
            $table->decimal('rate');
            $table->decimal('total');
        });
    }
};
