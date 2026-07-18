<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escalation extends Model
{
    use HasFactory;

    protected $fillable = ['conversation_id', 'reason', 'confidence_score', 'priority', 'status', 'assigned_admin_id'];
    protected $casts = ['confidence_score' => 'decimal:2'];

    public function conversation() { return $this->belongsTo(Conversation::class); }
    public function assignedAdmin() { return $this->belongsTo(User::class, 'assigned_admin_id'); }
}
