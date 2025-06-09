<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyewa extends Model
{
    use HasFactory;

    protected $table = 'penyewa';

    protected $primaryKey = 'id_penyewa'; // Sesuaikan dengan PK table

    protected $fillable = [
        'id_users', // FK ke users
        'nama_penyewa',
        'phone_number_penyewa',
        'address_penyewa',
        'gender_penyewa',
        'email_penyewa',
        'photo_profil',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users', 'id');
    }
}
