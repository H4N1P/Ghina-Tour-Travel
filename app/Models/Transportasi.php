<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transportasi extends Model
{
    protected $fillable = [
        'id_paket',
        'fasilitas_transportasi'
    ];

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'id_paket');
    }
}
