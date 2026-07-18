<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id', 'sender_id', 'type', 'content_original', 'content_translated',
        'source_language', 'target_language', 'translation_provider', 'translation_confidence', 'metadata'
    ];
    
    protected $casts = [
        'metadata' => 'array',
        'translation_confidence' => 'decimal:2',
    ];

    public function conversation() { return $this->belongsTo(Conversation::class); }
    public function sender() { return $this->belongsTo(User::class, 'sender_id'); }
}
