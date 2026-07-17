<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Sesuai instruksi terbaru: 'status_peserta' dihapus.
     * Kolom 'role' dimasukkan agar bisa diisi saat registrasi/seeding.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'face_vector', // Nilainya: 'admin' atau 'peserta'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi ke tabel log kecurangan tetap dipertahankan.
     */
    public function violationLogs(): HasMany
    {
        return $this->hasMany(ViolationLog::class);
    }
}
