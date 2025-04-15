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
        // Categories Table
        Schema::create('link_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Links Table
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('url');
            $table->text('description')->nullable();
            $table->string('favicon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Link-Category Relationship (Many-to-Many)
        Schema::create('link_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('link_id')->constrained()->onDelete('cascade');
            $table->foreignId('link_category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_category');
        Schema::dropIfExists('links');
        Schema::dropIfExists('link_categories');
    }
};
