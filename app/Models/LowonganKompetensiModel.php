<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LowonganKompetensiModel extends Model
{
    protected $table = 'm_lowongan_kompetensi';
    protected $primaryKey = 'lowongan_kompetensi_id'; // pastikan kolom ini ada di DB
    public $incrementing = true;
    public $timestamps = true; // pastikan kolom created_at & updated_at ada di tabel

    protected $fillable = ['lowongan_id', 'kompetensi_id'];

    public function lowongan()
    {
        return $this->belongsTo(LowonganMagangModel::class, 'lowongan_id', 'lowongan_id');
    }

    public function kompetensi()
    {
        return $this->belongsTo(KompetensiModel::class, 'kompetensi_id', 'kompetensi_id');
    }
}