<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitasModel extends Model
{
    protected $table = 't_log_aktivitas';
    public $timestamps = false;

    protected $fillable = ['pengajuan_id', 'aktivitas'];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanMagangModel::class, 'pengajuan_id', 'pengajuan_id');
    }
}
