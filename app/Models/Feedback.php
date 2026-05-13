<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    protected $fillable = [
        'booking_id',
        'member_id',
        'tutor_id',
        'rating',
        'komentar',
        'kejelasan_materi',
        'ketepatan_waktu',
        'keramahan',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    /** Rata-rata dari 3 aspek penilaian detail */
    public function getRatingDetailAvgAttribute(): ?float
    {
        $vals = array_filter([
            $this->kejelasan_materi,
            $this->ketepatan_waktu,
            $this->keramahan,
        ]);
        return count($vals) ? array_sum($vals) / count($vals) : null;
    }
}
