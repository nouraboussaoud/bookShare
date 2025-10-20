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
        Schema::create('reservation_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id'); // Lié à la réservation
            $table->decimal('montant', 10, 2);
            $table->enum('type_paiement', ['caution', 'location', 'penalite', 'remboursement'])->default('location');
            $table->enum('statut_paiement', ['en_attente', 'complete', 'echoue', 'rembourse', 'annule'])->default('en_attente');
            $table->string('methode_paiement')->nullable(); // carte, espèces, virement, etc.
            $table->string('reference_transaction')->nullable(); // Référence externe (PayPal, Stripe, etc.)
            $table->date('date_paiement')->nullable();
            $table->date('date_remboursement')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_payments');
    }
};
