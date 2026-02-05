<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HelpdeskMessage extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'helpdesk_conversation_id',
        'sender_role',
        'sender_user_id',
        'body',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sender_role' => 'string',
        ];
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(HelpdeskConversation::class, 'helpdesk_conversation_id');
    }

    public function senderUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }
}
