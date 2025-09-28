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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->foreignId('proprietaire_id')->constrained('users')->onDelete('cascade'); // Propriétaire du livre
            $table->foreignId('locataire_id')->constrained('users')->onDelete('cascade'); // Personne qui loue
            $table->date('date_location'); // Date de début de location
            $table->integer('duree_jours'); // Durée en jours
            $table->date('date_fin_prevue'); // Date de fin calculée
            $table->date('date_retour_effective')->nullable(); // Date de retour réelle
            $table->string('localisation'); // Lieu de récupération/retour
            $table->decimal('prix', 8, 2); // Prix de la location
            $table->enum('statut', ['en_attente', 'confirmee', 'en_cours', 'terminee', 'annulee'])->default('en_attente');
            $table->text('notes')->nullable(); // Notes additionnelles
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
