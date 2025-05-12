<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaKompetensiModel extends Model
{
    protected $table = 'm_table_mahasiswa_kompetensi';

    protected $fillable = ['mahasiswa_id', 'kompetensi_id'];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id');
    }

    public function kompetensi()
    {
        return $this->belongsTo(KompetensiModel::class, 'kompetensi_id');
    }
}
