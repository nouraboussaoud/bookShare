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
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'rating')) {
                $table->integer('rating')->unsigned()->comment('Rating from 1 to 5');
            }
            if (!Schema::hasColumn('reviews', 'comment')) {
                $table->text('comment')->nullable();
            }
            if (!Schema::hasColumn('reviews', 'status')) {
                $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING');
            }
            if (!Schema::hasColumn('reviews', 'admin_reply')) {
                $table->text('admin_reply')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['rating', 'comment', 'status', 'admin_reply']);
        });
    }
};
