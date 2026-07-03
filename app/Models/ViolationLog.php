<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViolationLog extends Model
{
    use HasFactory;

    protected $table = 'violation_logs';

    protected $fillable = [
        'user_id',
        'violation_type',   // 'Menoleh', 'Melirik', 'Wajah Hilang'
        'euclidean_score',  // Nilai desimal hasil rumus jarak
        'violation_image',  // Nama file gambar bukti pelanggaran (nullable)
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
