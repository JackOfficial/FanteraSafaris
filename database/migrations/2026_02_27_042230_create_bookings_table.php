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
        $table->foreignId('safari_package_id')->constrained();
        $table->string('customer_name');
        $table->string('customer_email');
        $table->date('travel_date');
        $table->integer('adults')->default(1);
        $table->integer('children')->default(0);
        $table->decimal('total_price', 12, 2);
        $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
        $table->text('special_requests')->nullable();
        $table->timestamps();
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
