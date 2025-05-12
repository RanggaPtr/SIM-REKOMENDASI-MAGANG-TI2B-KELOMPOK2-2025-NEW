<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UsersModel extends Authenticatable
{
    protected $table = 'm_table_users';
    protected $primaryKey = 'id'; // Sesuaikan dengan bigIncrements
    protected $fillable = ['nama', 'username', 'email', 'password', 'role', 'foto_profile', 'no_telepon', 'alamat'];
    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];

    public function admin(): HasOne
    {
        return $this->hasOne(AdminModel::class, 'id'); // Sesuaikan dengan primary key
    }

    public function dosen(): HasOne
    {
        return $this->hasOne(DosenModel::class, 'id');
    }

    public function mahasiswa(): HasOne
    {
        return $this->hasOne(MahasiswaModel::class, 'id');
    }

    public function logAktivitas(): HasMany
    {
        return $this->hasMany(LogAktivitasModel::class, 'id');
    }

    public function getRoleName(): string
    {
        return $this->role;
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function getAuthIdentifierName()
    {
        return 'username';
    }
}