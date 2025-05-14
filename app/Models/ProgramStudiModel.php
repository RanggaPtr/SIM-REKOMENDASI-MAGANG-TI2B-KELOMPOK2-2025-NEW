<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudiModel extends Model
{
    protected $table = 'm_program_studi';

    protected $fillable = ['nama'];

    public function dosen()
    {
        return $this->hasMany(DosenModel::class, 'program_studi_id');
    }

    public function mahasiswa()
    {
        return $this->hasMany(MahasiswaModel::class, 'program_studi_id');
    }
}
