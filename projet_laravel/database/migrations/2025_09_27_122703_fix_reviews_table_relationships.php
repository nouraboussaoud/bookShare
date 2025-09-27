<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clear existing reviews since they don't have books
        DB::table('reviews')->truncate();
        
        Schema::table('reviews', function (Blueprint $table) {
            // Check if book_id column doesn't exist before adding it
            if (!Schema::hasColumn('reviews', 'book_id')) {
                $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Drop book_id foreign key
            $table->dropForeign(['book_id']);
            $table->dropColumn('book_id');
            
            // Restore user_id foreign key
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        });
    }
};
