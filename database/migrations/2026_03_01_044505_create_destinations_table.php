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
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
        $table->string('name');           // e.g., Bwindi Impenetrable Forest
        $table->string('slug')->unique(); // e.g., bwindi-impenetrable-forest
        $table->string('country');        // e.g., Uganda
        $table->text('description')->nullable();
        $table->string('image')->nullable();
        $table->boolean('is_featured')->default(false);
        $table->timestamps();
        });

        Schema::table('safari_packages', function (Blueprint $table) {
        $table->foreignId('destination_id')->nullable()->after('safari_category_id')->constrained()->onDelete('cascade');
    });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
