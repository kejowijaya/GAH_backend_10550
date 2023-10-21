<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pegawai extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;

    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';
    public $timestamps = false;

    protected $fillable = [
        'role',
        'username',
        'password',
        'nama_pegawai',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function reservasis()
    {
        return $this->hasMany(Reservasi::class);
    }

}