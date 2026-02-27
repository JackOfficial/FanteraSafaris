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
        $table->integer('day_number'); 
        $table->string('title'); 
        $table->text('activities');
        $table->string('accommodation')->nullable();
        $table->string('meals')->nullable(); 
        $table->timestamps();

        // Add this for high-performance sorting
        $table->index(['safari_package_id', 'day_number']);
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
