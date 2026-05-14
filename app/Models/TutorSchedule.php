<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorSchedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';   // Tambahkan ini karena tabelnya 'schedules'

    protected $fillable = [
        'tutor_profile_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function tutorProfile()
    {
        return $this->belongsTo(TutorProfile::class);
    }

    // Relasi lain jika ada
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
