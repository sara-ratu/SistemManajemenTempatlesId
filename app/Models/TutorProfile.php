<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'bio', 'harga_min', 'harga_max',
        'pendidikan', 'universitas', 'dokumen_ktp',
        'dokumen_ijazah', 'rating_rata', 'total_review',
        'total_murid', 'status_verifikasi', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rating_rata' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'tutor_subjects');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
