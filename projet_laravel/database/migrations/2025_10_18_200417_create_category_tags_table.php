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
        Schema::create('category_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#6c757d'); // Hex color
            $table->string('icon')->nullable(); // FontAwesome icon
            $table->enum('type', ['genre', 'theme', 'mood', 'pace', 'other'])->default('other');
            $table->integer('usage_count')->default(0); // Number of books with this tag
            $table->timestamps();
            
            // Index for faster queries
            $table->index(['category_id', 'name']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_tags');
    }
};
