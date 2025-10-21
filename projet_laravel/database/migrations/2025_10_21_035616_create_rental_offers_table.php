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
        Schema::create('rental_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('prix_par_jour', 10, 2);
            $table->string('localisation');
            $table->integer('duree_min_jours')->default(1);
            $table->integer('duree_max_jours')->default(30);
            $table->text('conditions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Index pour améliorer les performances
            $table->index(['book_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_offers');
    }
};
