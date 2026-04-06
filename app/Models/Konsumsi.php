<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konsumsi extends Model
{
    protected $fillable = [
        'id_paket',
        'fasilitas_konsumsi'
    ];

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'id_paket');
    }
}
