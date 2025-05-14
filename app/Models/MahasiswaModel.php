<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaModel extends Model
{
    protected $table = 'm_table_mahasiswa';

    protected $fillable = [
        'user_id', 'nim', 'program_studi_id', 'lokasi_id', 'minat_id', 'skema_id', 'ipk'
    ];

    public function user()
    {
        return $this->belongsTo(UsersModel::class, 'user_id');
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudiModel::class, 'program_studi_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(LokasiModel::class, 'lokasi_id');
    }

    public function minat()
    {
        return $this->belongsTo(MinatModel::class, 'minat_id');
    }

    // public function skema()
    // {
    //     return $this->belongsTo(SkemaModel::class, 'skema_id');
    // }

    public function mahasiswaKompetensi()
    {
        return $this->hasMany(MahasiswaKompetensiModel::class, 'mahasiswa_id');
    }

    public function mahasiswaKeahlian()
    {
        return $this->hasMany(MahasiswaKeahlianModel::class, 'mahasiswa_id');
    }

    public function pengajuanMagang()
    {
        return $this->hasMany(PengajuanMagangModel::class, 'mahasiswa_id');
    }

    public function bookmark()
    {
        return $this->hasMany(BookmarkModel::class, 'mahasiswa_id');
    }
}
