<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'Member_id', 'tutor_id',
        'skor_lokasi', 'skor_mapel', 'skor_harga',
        'skor_jadwal', 'skor_rating', 'skor_total',
        'kriteria_input',
    ];

    protected $casts = [
        'kriteria_input' => 'array',
    ];

    public function Member()
    {
        return $this->belongsTo(User::class, 'Member_id');
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }
}
