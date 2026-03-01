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
        Schema::create('safari_category_safari_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('safari_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('safari_package_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safari_category_safari_packages');
    }
};
