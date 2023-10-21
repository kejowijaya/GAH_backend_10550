<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_jenis',
    ];

    public function jeniskamars()
    {
        return $this->belongsTo(Jenis_Kamar::class,'id_jenis');
    }


}