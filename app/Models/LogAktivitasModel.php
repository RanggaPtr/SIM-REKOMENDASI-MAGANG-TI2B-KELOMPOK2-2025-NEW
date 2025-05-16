<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitasModel extends Model
{
    protected $table = 't_log_aktivitas';
    public $timestamps = false;

    protected $fillable = ['user_id', 'aktivitas'];

    public function user()
    {
        return $this->belongsTo(UsersModel::class, 'user_id', 'user_id');
    }
}