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
        Schema::create('helpdesk_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('helpdesk_conversation_id')->constrained('helpdesk_conversations')->cascadeOnDelete();
            $table->enum('sender_role', ['user', 'helpdesk-agent', 'system']);
            $table->foreignId('sender_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('body');
            $table->timestamps();
            $table->index(['helpdesk_conversation_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('helpdesk_messages');
    }
};
