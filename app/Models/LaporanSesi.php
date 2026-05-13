<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaporanSesi extends Model
{
    use HasFactory;

    protected $table = 'laporan_sesi';

    protected $fillable = [
        'booking_id',
        'tutor_id',
        'materi',
        'catatan',
        'status',
        'tanggal_sesi',
    ];

    // ── RELASI KE BOOKING ───────────────────────────
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // ── RELASI KE TUTOR ─────────────────────────────
    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }
}
