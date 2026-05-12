<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_mapel', 'kategori', 'icon', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tutorProfiles()
    {
        return $this->belongsToMany(TutorProfile::class, 'tutor_subjects');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
