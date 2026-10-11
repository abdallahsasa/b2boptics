<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['subject', 'type', 'status', 'factory_id', 'product_id'];

    public function participants() { return $this->hasMany(ConversationParticipant::class); }
    public function messages() { return $this->hasMany(Message::class); }
    public function factory() { return $this->belongsTo(Factory::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function escalations() { return $this->hasMany(Escalation::class); }

    public function buyer()
    {
        return $this->hasOneThrough(User::class, ConversationParticipant::class, 'conversation_id', 'id', 'id', 'user_id')
            ->where('conversation_participants.role', 'buyer');
    }

    public function getBuyerUserAttribute()
    {
        return $this->participants()->where('role', 'buyer')->first()?->user;
    }
}
