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
        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign(['locataire_id']);
            $table->dropColumn('locataire_id');
            $table->foreignId('locataire_id')->nullable()->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign(['locataire_id']);
            $table->dropColumn('locataire_id');
            $table->foreignId('locataire_id')->constrained('users')->onDelete('cascade');
        });
    }
};
