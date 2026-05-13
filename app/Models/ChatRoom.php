<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $fillable = [
        'booking_id',
        'tutor_id',
        'member_id',
        'status',
        'closed_at',
    ];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at');
    }

    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class)->latestOfMany();
    }

    public function unreadFor(int $userId): int
    {
        return $this->messages()->where('sender_id', '!=', $userId)->where('is_read', false)->count();
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
