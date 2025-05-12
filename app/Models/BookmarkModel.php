<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookmarkModel extends Model
{
    protected $table = 'm_table_bookmark';

    protected $fillable = ['mahasiswa_id', 'lowongan_id'];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id');
    }

    public function lowongan()
    {
        return $this->belongsTo(LowonganMagangModel::class, 'lowongan_id');
    }
}
