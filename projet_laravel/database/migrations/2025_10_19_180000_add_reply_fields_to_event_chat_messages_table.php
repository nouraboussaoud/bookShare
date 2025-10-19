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
        Schema::table('event_chat_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('reply_to_message_id')->nullable()->after('moderation_status');
            $table->string('reply_to_user')->nullable()->after('reply_to_message_id');
            $table->text('reply_to_content')->nullable()->after('reply_to_user');
            
            $table->index('reply_to_message_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_chat_messages', function (Blueprint $table) {
            $table->dropIndex(['reply_to_message_id']);
            $table->dropColumn(['reply_to_message_id', 'reply_to_user', 'reply_to_content']);
        });
    }
};
