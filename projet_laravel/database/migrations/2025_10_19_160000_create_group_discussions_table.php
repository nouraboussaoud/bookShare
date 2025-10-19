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
        Schema::create('group_discussions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reading_group_id')->constrained('reading_groups')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->timestamps();

            $table->index(['reading_group_id', 'created_at']);
        });

        Schema::create('group_discussion_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_discussion_id')->constrained('group_discussions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('content');
            $table->timestamps();

            $table->index('group_discussion_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_discussion_replies');
        Schema::dropIfExists('group_discussions');
    }
};
