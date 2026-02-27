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
        Schema::create('safari_packages', function (Blueprint $table) {
        $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('summary')->nullable(); // Short text for cards/previews
    $table->longText('description');     // For the main Trix/HTML content
    
    // Pricing & Duration
    $table->decimal('price', 12, 2);     // Increased to 12 to handle large groups/luxury rates
    $table->integer('duration_days')->default(1);
    $table->integer('max_people')->nullable(); // Good for booking logic later
    $table->foreignId('safari_category_id')->nullable()->constrained('safari_categories')->onDelete('set null');
    
    // Attributes
    $table->string('location'); 
    $table->string('difficulty')->default('moderate'); 
    $table->string('image')->nullable();
    
    // Visibility & SEO
    $table->boolean('is_featured')->default(false);
    $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
    $table->string('meta_title')->nullable();
    $table->string('meta_description')->nullable();
    
    $table->timestamps();
    $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safari_packages');
    }
};
