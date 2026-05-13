<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanSesi extends Model
{
    protected $table = 'laporan_sesi';

    protected $fillable = [
        'booking_id',
        'tutor_id',
        'member_id',
        'tanggal_sesi',
        'jam_mulai',
        'jam_selesai',
        'materi_diajarkan',
        'perkembangan_siswa',
        'catatan_tutor',
        'foto_bukti',
        'status',
        'confirmed_at',
    ];

    protected $casts = [
        'tanggal_sesi' => 'date',
        'confirmed_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }
}
