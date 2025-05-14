<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LowonganKompetensiModel extends Model
{
    protected $table = 'm_lowongan_kompetensi';

    protected $fillable = ['lowongan_id', 'kompetensi_id'];

    public function lowongan()
    {
        return $this->belongsTo(LowonganMagangModel::class, 'lowongan_id');
    }

    public function kompetensi()
    {
        return $this->belongsTo(KompetensiModel::class, 'kompetensi_id');
    }
}
