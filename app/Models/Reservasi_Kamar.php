<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi_Kamar extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_reservasi',
        'id_jenis',
        'jumlah',
        'subtotal',
    ];

    public function reservasis()
    {
        return $this->belongsTo(Reservasi::class,'id_reservasi');
    }

    public function jeniskamars()
    {
        return $this->belongsTo(Jenis_Kamar::class,'id_jenis');
    }

}