<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rundown extends Model
{
    protected $fillable = [
        'id_paket',
        'waktu',
        'acara',
        'deskripsi',
    ];

    /**
     * Mendefinisikan paket pemilik rundown.
     */
    public function paket()
    {
        return $this->belongsTo(Paket::class, 'id_paket');
    }
}
