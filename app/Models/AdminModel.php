<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminModel extends Model
{
    protected $table = 'm_table_admin';

    protected $fillable = ['user_id', 'nik', 'jabatan'];

    public function user()
    {
        return $this->belongsTo(UsersModel::class, 'user_id');
    }
}
