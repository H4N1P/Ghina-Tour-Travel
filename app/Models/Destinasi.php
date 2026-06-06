<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destinasi extends Model
{
    protected $fillable = [
        'id_paket',
        'nama_destinasi',
        'image'
    ];

    /**
     * Mendefinisikan paket pemilik destinasi.
     */
    public function paket()
    {
        return $this->belongsTo(Paket::class, 'id_paket');
    }

    /**
     * Mendefinisikan kumpulan media galeri milik destinasi.
     */
    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'id_destinasi');
    }
}
