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

    protected $primaryKey = 'id'; // Primary key tabel

    protected $fillable = [
        'username',
        'email',
        'phone',
        'password',
        'userType_id', // FK ke user_types
        'role_id', // FK ke user_roles
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relasi ke userType
    // public function userType()
    // {
    //     return $this->belongsTo(UserType::class, 'userType_id', 'userType_id');
    // }

    // Relasi ke role (berdasarkan tabel user_roles)
    public function userRole()
    {
        return $this->belongsTo(UserRole::class, 'role_id', 'role_id');
    }
}
