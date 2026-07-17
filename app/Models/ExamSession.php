<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSession extends Model
{
    protected $fillable = ['session_name', 'is_active'];

    // Relasi ke User / Peserta yang ikut di sesi ini (opsional jika dibutuhkan)
    public function violations()
    {
        return $this->hasMany(ViolationLog::class, 'exam_session_id');
    }
}
