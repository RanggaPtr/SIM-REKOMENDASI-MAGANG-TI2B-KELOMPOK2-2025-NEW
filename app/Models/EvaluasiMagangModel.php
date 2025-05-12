<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluasiMagangModel extends Model
{
    protected $table = 't_table_evaluasi_magang';

    protected $fillable = ['pengajuan_id', 'nilai', 'komentar'];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanMagangModel::class, 'pengajuan_id');
    }
}
