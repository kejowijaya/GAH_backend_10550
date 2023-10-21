<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_fasilitas';
    public $timestamps = false;

    protected $fillable = [
        'nama_fasilitas',
        'harga',
        'satuan',
    ];

    public function reservasilayanans()
    {
        return $this->hasMany(Reservasi_Layanan::class);
    }
}