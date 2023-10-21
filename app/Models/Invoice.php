<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_reservasi',
        'id_pegawai',
        'tanggal',
        'pajak',
        'total_harga',
        'jaminan',
        'deposit',
        'cash',
    ];

    public function reservasis()
    {
        return $this->belongsTo(Reservasi::class,'id_reservasi');
    }

    public function pegawais()
    {
        return $this->belongsTo(Pegawai::class,'id_pegawai');
    }

}