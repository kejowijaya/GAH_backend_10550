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
        'tanggal_checkin',
        'tanggal_checkout',
        'jumlah_dewasa',
        'jumlah_anak',
        'total_harga',
        'permintaan_khusus',
        'status',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function reservasikamars()
    {
        return $this->hasMany(Reservasi_Kamar::class);
    }

    public function reservasilayanans()
    {
        return $this->hasMany(Reservasi_Layanan::class);
    }

    public function customers()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    public function pegawais()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }
}