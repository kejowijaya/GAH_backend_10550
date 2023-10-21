<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;

    protected $table = 'season';
    protected $primaryKey = 'id_season';
    public $timestamps = false;

    protected $fillable = [
        'nama_season',
        'tanggal_mulai',
        'tanggal_selesai',
        'jenis_season',
    ];

    public function tarifs()
    {
        return $this->hasMany(Tarif::class);
    }

}