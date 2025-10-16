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
        if (!Schema::hasTable('locations')) {
            Schema::create('locations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('book_id');
                $table->unsignedBigInteger('proprietaire_id');
                $table->unsignedBigInteger('locataire_id')->nullable();
                $table->date('date_location');
                $table->integer('duree_jours');
                $table->date('date_fin_prevue')->nullable();
                $table->date('date_retour_effective')->nullable();
                $table->string('localisation')->nullable();
                $table->decimal('prix', 8, 2);
                $table->enum('statut', ['en_attente', 'confirmee', 'en_cours', 'terminee', 'annulee'])->default('en_attente');
                $table->text('notes')->nullable();
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
                $table->foreign('proprietaire_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('locataire_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
