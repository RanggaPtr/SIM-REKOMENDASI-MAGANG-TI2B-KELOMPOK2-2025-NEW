<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudiModel extends Model
{
    protected $table = 'm_program_studi';
    protected $primaryKey = 'prodi_id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = ['nama'];

    public function dosen()
    {
        return $this->hasMany(DosenModel::class, 'prodi_id', 'prodi_id');
    }

    public function mahasiswa()
    {
        return $this->hasMany(MahasiswaModel::class, 'program_studi_id', 'prodi_id');
    }
}