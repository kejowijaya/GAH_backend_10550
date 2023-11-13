<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi_Layanan extends Model
{
    use HasFactory;

    protected $table = 'reservasi_layanan';
    protected $primaryKey = 'id_reservasi_layanan';
    public $timestamps = false;

    protected $fillable = [
        'id_reservasi',
        'id_fasilitas',
        'tanggal_pakai',
        'total_harga',
        'jumlah',
    ];

    public function reservasis()
    {
        return $this->belongsTo(Reservasi::class,'id_reservasi');
    }

    public function fasilitass()
    {
        return $this->belongsTo(Fasilitas::class,'id_fasilitas');
    }

}