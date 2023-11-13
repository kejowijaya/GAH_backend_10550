<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    protected $table = 'reservasi';
    protected $primaryKey = 'id_reservasi';
    public $timestamps = false;

    protected $fillable = [
        'id_pegawai',
        'id_customer',
        'id_booking',
        'tanggal_booking',
        'tanggal_check_in',
        'tanggal_check_out',
        'dewasa',
        'anak',
        'total_harga',
        'permintaan_khusus',
        'status',
        'nomor_rek',
        'tanggal_bayar'
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function reservasi_kamar()
    {
        return $this->hasMany(Reservasi_Kamar::class, 'id_reservasi', 'id_reservasi');
    }

    public function reservasi_layanan()
    {
        return $this->hasMany(Reservasi_Layanan::class, 'id_reservasi', 'id_reservasi');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }
}