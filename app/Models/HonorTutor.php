<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HonorTutor extends Model
{
    use HasFactory;

    protected $table = 'honor_tutors';

    protected $fillable = [
        'pembayaran_id',
        'booking_id',
        'tutor_id',
        'jumlah_bruto',      // total bayar murid
        'komisi_platform',   // potongan platform (%)
        'jumlah_honor',      // yang diterima tutor
        'status',            // pending | ditransfer
        'rekening_bank',
        'nama_rekening',
        'no_rekening',
        'bukti_transfer',
        'ditransfer_at',
        'ditransfer_by',
        'catatan',
    ];

    protected $casts = [
        'jumlah_bruto'     => 'decimal:2',
        'komisi_platform'  => 'decimal:2',
        'jumlah_honor'     => 'decimal:2',
        'ditransfer_at'    => 'datetime',
    ];

    // ── Relasi ────────────────────────────────────────────

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function ditransferOleh()
    {
        return $this->belongsTo(User::class, 'ditransfer_by');
    }

    // ── Scopes ───────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDitransfer($query)
    {
        return $query->where('status', 'ditransfer');
    }

    // ── Helpers ──────────────────────────────────────────

    public function getJumlahHonorRpAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah_honor, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'     => 'Belum Ditransfer',
            'ditransfer'  => 'Sudah Ditransfer',
            default       => ucfirst($this->status),
        };
    }
}
