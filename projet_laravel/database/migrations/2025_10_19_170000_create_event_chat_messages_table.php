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
        Schema::create('event_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_event_id')->constrained('group_events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->boolean('is_moderated')->default(false);
            $table->string('moderation_reason')->nullable();
            $table->enum('moderation_status', ['approved', 'flagged', 'rejected'])->default('approved');
            $table->timestamps();

            $table->index(['group_event_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_chat_messages');
    }
};
