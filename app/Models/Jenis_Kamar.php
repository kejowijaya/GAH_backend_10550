<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenis_Kamar extends Model
{
    use HasFactory;

    protected $table = 'jenis_kamar';
    protected $primaryKey = 'id_jenis';
    public $timestamps = false;

    protected $fillable = [
        'jenis_kamar',
        'tipe_ranjang',
        'fasilitas',
        'harga',
        'luas_kamar',
        'kapasitas',
    ];

    
    public function kamars()
    {
        return $this->hasMany(Kamar::class);
    }

    public function reservasikamars()
    {
        return $this->hasMany(Reservasi_Kamar::class);
    }

    public function tarifs()
    {
        return $this->hasMany(Tarif::class);
    }

}