<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KompetensiModel extends Model
{
    protected $table = 'm_table_kompetensi';

    protected $fillable = ['nama', 'deskripsi'];

    public function mahasiswaKompetensi()
    {
        return $this->hasMany(MahasiswaKompetensiModel::class, 'kompetensi_id');
    }

    public function lowonganKompetensi()
    {
        return $this->hasMany(LowonganKompetensiModel::class, 'kompetensi_id');
    }
}
