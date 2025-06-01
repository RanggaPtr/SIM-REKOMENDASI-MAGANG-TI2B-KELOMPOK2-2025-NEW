<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodeMagangModel extends Model
{
    protected $table = 'm_periode_magang';
    protected $primaryKey = 'periode_id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = ['nama', 'tanggal_mulai', 'tanggal_selesai'];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function lowonganMagang()
    {
        return $this->hasMany(LowonganMagangModel::class, 'periode_id', 'periode_id');
    }

}