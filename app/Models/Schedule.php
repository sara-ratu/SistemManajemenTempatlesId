<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'tutor_profile_id', 'hari',
        'jam_mulai', 'jam_selesai', 'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function tutorProfile()
    {
        return $this->belongsTo(TutorProfile::class);
    }
}
