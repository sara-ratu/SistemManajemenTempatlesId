<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 'Member_id', 'tutor_id',
        'rating', 'komentar',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function Member()
    {
        return $this->belongsTo(User::class, 'Member_id');
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }
}
