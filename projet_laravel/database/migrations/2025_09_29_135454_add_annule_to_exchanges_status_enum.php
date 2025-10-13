<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'ANNULE' to the status enum for exchanges table
        DB::statement("ALTER TABLE exchanges MODIFY COLUMN status ENUM('EN_ATTENTE', 'APPROUVE', 'REFUSE', 'EN_COURS', 'TERMINE', 'ANNULE') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'ANNULE' from the status enum for exchanges table
        DB::statement("ALTER TABLE exchanges MODIFY COLUMN status ENUM('EN_ATTENTE', 'APPROUVE', 'REFUSE', 'EN_COURS', 'TERMINE') NOT NULL");
    }
};
