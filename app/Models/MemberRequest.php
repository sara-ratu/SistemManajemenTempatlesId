<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberRequest extends Model
{
    protected $fillable = [
        'member_id',
        'subject_id',
        'jenjang',
        'metode',
        'kota_kabupaten',
        'kecamatan',
        'budget_min',
        'budget_max',
        'catatan',
        'status',
        'matched_at',
        'closed_at',
    ];

    protected $casts = [
        'matched_at' => 'datetime',
        'closed_at'  => 'datetime',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function isOpen(): bool    { return $this->status === 'open'; }
    public function isMatched(): bool { return $this->status === 'matched'; }
}
