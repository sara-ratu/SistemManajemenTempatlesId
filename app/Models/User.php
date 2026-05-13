<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property string|null $no_hp
 * @property string|null $foto_profil
 * @property string|null $alamat
 * @property string|null $kota
 * @property float|null $latitude
 * @property float|null $longitude
 */

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'no_hp', 'foto_profil', 'alamat',
        'kota', 'latitude', 'longitude',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // ── Role Helpers ──────────────────────────
    public function isMurid(): bool
    {
        return $this->role === 'murid';
    }

    public function isTutor(): bool
    {
        return $this->role === 'tutor';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // ← TAMBAHAN BARU
    public function dashboardRoute(): string
    {
        if ($this->isAdmin()) return route('admin.dashboard');
        if ($this->isTutor()) return route('tutor.dashboard');
        return route('murid.dashboard');
    }

    // ── Relasi ────────────────────────────────
    public function tutorProfile()
    {
        return $this->hasOne(TutorProfile::class);
    }

    public function bookingsAsMurid()
    {
        return $this->hasMany(Booking::class, 'murid_id');
    }

    public function bookingsAsTutor()
    {
        return $this->hasMany(Booking::class, 'tutor_id');
    }

    public function reviewsAsMurid()
    {
        return $this->hasMany(Review::class, 'murid_id');
    }

    public function reviewsAsTutor()
    {
        return $this->hasMany(Review::class, 'tutor_id');
    }

    public function matchingLogs()
    {
        return $this->hasMany(MatchingLog::class, 'murid_id');
    }

    public function routeNotificationForFonnte(): ?string
    {
    $noHp = $this->no_hp ?? null;
    if (! $noHp) return null;

    if (str_starts_with($noHp, '0')) {
        $noHp = '62' . substr($noHp, 1);
    }
    return $noHp;
    }
}
