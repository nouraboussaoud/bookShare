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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->index(); // Pour les utilisateurs non connectés
            $table->enum('role', ['user', 'assistant']); // Qui a envoyé le message
            $table->text('message'); // Contenu du message
            $table->json('context')->nullable(); // Contexte supplémentaire (livres mentionnés, etc.)
            $table->timestamps();
            
            // Index pour améliorer les performances
            $table->index(['user_id', 'created_at']);
            $table->index(['session_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
