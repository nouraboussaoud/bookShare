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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['CONFLIT_ECHANGE', 'COMPORTEMENT']);
            $table->text('description');
            $table->unsignedBigInteger('reportedUserId');
            $table->unsignedBigInteger('reporterId');
            $table->unsignedBigInteger('exchangeId')->nullable();
            $table->enum('status', ['EN_ATTENTE', 'TRAITE', 'CLOTURE']);
            $table->timestamps();

            $table->foreign('reportedUserId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reporterId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('exchangeId')->references('id')->on('exchanges')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
