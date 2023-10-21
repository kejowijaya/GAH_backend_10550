<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    use HasFactory;

    protected $table = 'tarif_season';
    protected $primaryKey = 'id_tarif';
    public $timestamps = false;

    protected $fillable = [
        'id_season',
        'id_jenis',
        'perubahan_tarif',
    ];

    public function seasons()
    {
        return $this->belongsTo(Season::class,'id_season');
    }

    public function jeniskamars()
    {
        return $this->belongsTo(Jenis_Kamar::class,'id_jenis');
    }

}