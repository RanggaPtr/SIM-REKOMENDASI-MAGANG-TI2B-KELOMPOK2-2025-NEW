<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaModel extends Model
{
    protected $table = 'm_mahasiswa';
    protected $primaryKey = 'mahasiswa_id'; // Disamakan dengan migrasi (perbaiki typo)
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'user_id', 'nim', 'program_studi_id', 'wilayah_id', 'skema_id', 'ipk', 'periode_id','file_cv'
    ];

    public function user()
    {
        return $this->belongsTo(UsersModel::class, 'user_id', 'user_id');
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudiModel::class, 'program_studi_id', 'prodi_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(WilayahModel::class, 'wilayah_id', 'wilayah_id');
    }

    public function skema()
    {
        return $this->belongsTo(SkemaModel::class, 'skema_id', 'skema_id');
    }
      public function periode()
    {
        return $this->belongsTo(PeriodeMagangModel::class, 'periode_id', 'periode_id');
    }

    public function mahasiswaKompetensi()
    {
        return $this->hasMany(MahasiswaKompetensiModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    public function mahasiswaKeahlian()
    {
        return $this->hasMany(MahasiswaKeahlianModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    public function pengajuanMagang()
    {
        return $this->hasMany(PengajuanMagangModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    public function bookmark()
    {
        return $this->hasMany(BookmarkModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }
}