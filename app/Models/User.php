<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'users'; // Nama tabel di database

    protected $primaryKey = 'id';

    protected $fillable = [
        'username',
        'email',
        'phone',
        'password',
        'userType_id', // FK ke user_types
        'role_id',     // FK ke user_roles
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_banned' => 'boolean',
    ];

    // Relasi ke UserRole (roles table)
    public function userRole()
    {
        return $this->belongsTo(UserRole::class, 'role_id', 'role_id');
    }

    // === PENAMBAHAN START ===
    // Relasi ke penyewa
    public function penyewa()
    {
        return $this->hasOne(Penyewa::class, 'id_users', 'id');
    }

    // Inisial nama
    public function getInitialsAttribute(): string
    {
        $name = $this->name ?? $this->username ?? '';
        $names = explode(' ', trim($name));
        $ini = array_map(fn($n) => strtoupper(substr($n, 0, 1)), $names);
        return implode('', array_slice($ini, 0, 2));
    }

    // Accessor foto profil universal (penyewa/user)
    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->penyewa && $this->penyewa->photo_profil) {
            return asset('storage/' . $this->penyewa->photo_profil);
        }
        if ($this->profile_picture) {
            return asset('storage/' . $this->profile_picture);
        }
        return null;
    }

    // app/Models/User.php
    public function isPenyewaProfileComplete()
    {
        if (!$this->penyewa) return false;
        return $this->penyewa->phone_number_penyewa &&
            $this->penyewa->address_penyewa &&
            $this->penyewa->gender_penyewa &&
            $this->penyewa->photo_profil; // Atau kolom lain yang wajib
    }

    // === PENAMBAHAN END ===
}
