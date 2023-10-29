<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamar';
    protected $primaryKey = 'nomor_kamar';
    public $timestamps = false;

    protected $fillable = [
        'nomor_kamar',
        'id_jenis',
    ];

    public function jenis_kamar()
    {
        return $this->belongsTo(Jenis_Kamar::class,'id_jenis');
    }


}