<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackLogAktivitasModel extends Model
{
    use HasFactory;
     protected $table = 'feedback_log_aktivitas';
    protected $fillable = ['log_aktivitas_id', 'dosen_id', 'komentar'];

    public function logAktivitas()
{
    return $this->belongsTo(LogAktivitasModel::class, 'log_aktivitas_id', 'log_id');
}
    public function dosen()
    {
        return $this->belongsTo(DosenModel::class, 'dosen_id');
    }
}
