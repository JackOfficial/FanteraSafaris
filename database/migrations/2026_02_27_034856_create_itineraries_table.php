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
        Schema::create('itineraries', function (Blueprint $table) {
            $table->id();
        $table->foreignId('safari_package_id')->constrained()->onDelete('cascade');
        $table->integer('day_number'); // e.g., Day 1, Day 2
        $table->string('title'); // e.g., Arrival in Entebbe
        $table->text('activities');
        $table->string('accommodation')->nullable();
        $table->string('meals')->nullable(); // e.g., B, L, D
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itineraries');
    }
};
