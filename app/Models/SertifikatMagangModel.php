<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SertifikatMagangModel extends Model
{
    protected $table = 'm_table_sertifikat_magang';

    protected $fillable = ['pengajuan_id', 'nama_dokumen', 'jenis_dokumen', 'file_dokumen'];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanMagangModel::class, 'pengajuan_id');
    }
}
