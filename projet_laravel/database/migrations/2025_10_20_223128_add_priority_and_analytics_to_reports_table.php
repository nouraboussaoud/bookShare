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
            // Système de priorité
            $table->integer('priority_score')->default(5)->after('priority_level')->comment('Score de priorité 1-10');
            
            // Champs pour l'analytique et le suivi
            $table->unsignedBigInteger('moderator_id')->nullable()->after('exchange_id');
            $table->timestamp('reviewed_at')->nullable()->after('moderator_id');
            $table->timestamp('resolved_at')->nullable()->after('reviewed_at');
            $table->text('admin_notes')->nullable()->after('resolved_at');
            $table->string('action_taken')->nullable()->after('admin_notes')->comment('Action prise par le modérateur');
            
            // Métadonnées pour l'auto-priorisation
            $table->integer('similar_reports_count')->default(0)->after('action_taken');
            $table->boolean('is_recurring_offender')->default(false)->after('similar_reports_count');
            
            // Foreign key pour le modérateur
            $table->foreign('moderator_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['moderator_id']);
            $table->dropColumn([
                'priority_score',
                'moderator_id',
                'reviewed_at',
                'resolved_at',
                'admin_notes',
                'action_taken',
                'similar_reports_count',
                'is_recurring_offender',
            ]);
        });
    }
};
