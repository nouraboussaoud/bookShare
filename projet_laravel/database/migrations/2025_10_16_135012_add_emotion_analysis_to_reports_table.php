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
        Schema::table('reports', function (Blueprint $table) {
            // Ajout des colonnes pour l'analyse d'émotions
            $table->string('emotion_type')->nullable()->after('status')->comment('Type d\'émotion détectée (colère, tristesse, peur, etc.)');
            $table->decimal('emotion_score', 5, 2)->nullable()->after('emotion_type')->comment('Score de confiance de l\'émotion (0-100)');
            $table->enum('priority_level', ['normale', 'moyenne', 'haute', 'critique'])->default('normale')->after('emotion_score')->comment('Niveau de priorité basé sur l\'émotion');
            
            // Index pour optimiser les requêtes par priorité et émotion
            $table->index(['priority_level', 'created_at']);
            $table->index(['emotion_type', 'emotion_score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Suppression des index
            $table->dropIndex(['priority_level', 'created_at']);
            $table->dropIndex(['emotion_type', 'emotion_score']);
            
            // Suppression des colonnes
            $table->dropColumn(['emotion_type', 'emotion_score', 'priority_level']);
        });
    }
};
