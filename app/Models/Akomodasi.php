<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Akomodasi extends Model
{
    protected $fillable = [
        'id_paket',
        'fasilitas_akomodasi'
    ];

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'id_paket');
    }
}
