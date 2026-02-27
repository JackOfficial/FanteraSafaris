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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            
            // The Author: Link to the admin who created the post
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Content Fields
            $table->string('title');
            $table->string('slug')->unique(); // For SEO: fanterasafaris.com/blog/murchison-falls-trip
            $table->text('content');
            
            // Media & Metadata
            $table->string('image')->nullable(); // Path to the safari photo
            $table->boolean('is_published')->default(false);
            $table->integer('views')->default(0); // Track popular posts
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};