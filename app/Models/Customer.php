<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;

    protected $table = 'customer';
    protected $primaryKey = 'id_customer';
    public $timestamps = false;

    protected $fillable = [
        'no_identitas',
        'nama_institusi',
        'nama',
        'nomor_telepon',
        'email',
        'alamat',
        'password',
        'jenis_tamu',
    ];

    public function reservasis()
    {
        return $this->hasMany(Reservasi::class);
    }

}