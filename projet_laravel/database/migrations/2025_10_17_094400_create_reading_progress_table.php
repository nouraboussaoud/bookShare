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
        Schema::create('reading_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->integer('current_page')->default(0);
            $table->integer('total_pages')->nullable();
            $table->enum('status', ['to_read', 'reading', 'completed', 'abandoned'])->default('to_read');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->integer('reading_time_minutes')->default(0)->comment('Total reading time in minutes');
            $table->text('notes')->nullable()->comment('Personal reading notes');
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index(['user_id', 'book_id']);
            $table->index('status');
            
            // Un utilisateur ne peut avoir qu'une seule progression par livre
            $table->unique(['user_id', 'book_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reading_progress');
    }
};
