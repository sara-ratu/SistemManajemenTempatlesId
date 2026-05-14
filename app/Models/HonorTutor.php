<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HonorTutor extends Model
{
    use HasFactory;
    // === INI YANG PENTING ===
    protected $table = 'honor_tutor';     // singular sesuai migration

    protected $fillable = [
        'tutor_id',
        'jumlah_honor',
        'jumlah_bruto',
        'komisi_platform',
        'periode',
        'status',
        'catatan',
        'bukti_transfer',
        'tanggal_transfer',
        'admin_id',
    ];

    protected $casts = [
        'jumlah_bruto'     => 'decimal:2',
        'jumlah_honor'     => 'decimal:2',
        'tanggal_transfer' => 'datetime',
    ];

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function transferBy()
    {
        return $this->belongsTo(User::class, 'transfer_by');
    }

    // Accessor
    public function getJumlahHonorRpAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_honor ?? 0, 0, ',', '.');
    }
}
