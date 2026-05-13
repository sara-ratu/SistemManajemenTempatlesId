<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PksDocument extends Model
{
    use HasFactory;

    protected $table = 'pks_documents';

    protected $fillable = [
        'tutor_id',
        'nomor_pks',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',           // draft | sent | signed | expired | terminated
        'file_path',
        'signed_at',
        'signed_by',
        'catatan',
        'generated_by',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'signed_at'       => 'datetime',
    ];

    // ── Status helpers ──────────────────────────────

    public function statusBadge(): string
    {
        return match($this->status) {
            'draft'       => 'bg-gray-100 text-gray-500',
            'sent'        => 'bg-yellow-100 text-yellow-700',
            'signed'      => 'bg-green-100 text-green-700',
            'expired'     => 'bg-red-100 text-red-600',
            'terminated'  => 'bg-red-200 text-red-800',
            default       => 'bg-gray-100 text-gray-500',
        };
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'draft'       => 'Draft',
            'sent'        => 'Dikirim',
            'signed'      => 'Ditandatangani',
            'expired'     => 'Kadaluarsa',
            'terminated'  => 'Diakhiri',
            default       => ucfirst($this->status),
        };
    }

    public function isActive(): bool
    {
        return $this->status === 'signed'
            && $this->tanggal_selesai >= now()->toDateString();
    }

    // ── Relasi ──────────────────────────────────────

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function tutorProfile()
    {
        return $this->hasOneThrough(
            TutorProfile::class,
            User::class,
            'id',
            'user_id',
            'tutor_id',
            'id'
        );
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function signedBy()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }
}
