<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TutorArea extends Model
{
    protected $fillable = [
        'tutor_id',
        'provinsi',
        'kota_kabupaten',
        'kecamatan',
        'kelurahan',
        'radius_km',
        'latitude',
        'longitude',
        'is_primary',
    ];

    protected $casts = [
        'radius_km'  => 'decimal:2',
        'latitude'   => 'decimal:7',
        'longitude'  => 'decimal:7',
        'is_primary' => 'boolean',
    ];

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }
}
