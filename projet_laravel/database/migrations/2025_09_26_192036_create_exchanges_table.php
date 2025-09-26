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
        Schema::create('exchanges', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['RESERVATION', 'ECHANGE']);
            $table->enum('status', ['EN_ATTENTE', 'APPROUVE', 'REFUSE', 'EN_COURS', 'TERMINE']);
            $table->date('dateDebut');
            $table->date('dateFin');
            $table->unsignedBigInteger('userInitiateurId');
            $table->unsignedBigInteger('userRecepteurId');
            $table->unsignedBigInteger('bookDemandeId');
            $table->unsignedBigInteger('bookOffertId')->nullable();
            $table->timestamps();

            $table->foreign('userInitiateurId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('userRecepteurId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('bookDemandeId')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('bookOffertId')->references('id')->on('books')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchanges');
    }
};
