<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaKompetensiModel extends Model
{
    protected $table = 'm_mahasiswa_kompetensi';
    protected $primaryKey = 'mahasiswa_kompetensi_id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = ['mahasiswa_id', 'kompetensi_id'];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    public function kompetensi()
    {
        return $this->belongsTo(KompetensiModel::class, 'kompetensi_id', 'kompetensi_id');
    }
}