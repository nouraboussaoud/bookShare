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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('icon', 50)->default('fas fa-book')->comment('FontAwesome icon class');
            $table->boolean('is_featured')->default(false)->comment('Featured category on homepage');
            $table->integer('sort_order')->default(0)->comment('Display order');
            $table->text('reading_tips')->nullable()->comment('Reading tips for this category');
            $table->json('popular_authors')->nullable()->comment('List of popular authors in this category');
            $table->boolean('is_active')->default(true)->comment('Category is active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'icon', 
                'is_featured', 
                'sort_order', 
                'reading_tips', 
                'popular_authors', 
                'is_active'
            ]);
        });
    }
};
