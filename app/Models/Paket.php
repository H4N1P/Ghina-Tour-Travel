<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    protected $fillable = [
        'nama_paket',
        'harga_paket',
        'durasi',
        'note',
        'image',
    ];


    /**
     * Mendefinisikan kumpulan destinasi dalam paket.
     */
    public function destinasis()
    {
        return $this->hasMany(Destinasi::class, 'id_paket');
    }

    /**
     * Mendefinisikan kumpulan foto paket melalui destinasi.
     */
    public function fotos()
    {
        return $this->hasManyThrough(Gallery::class, Destinasi::class, 'id_paket', 'id_destinasi');
    }

    /**
     * Mendefinisikan kumpulan fasilitas dalam paket.
     */
    public function fasilitas()
    {
        return $this->hasMany(Fasilitas::class, 'id_paket');
    }

    /**
     * Mendefinisikan kumpulan rundown dalam paket.
     */
    public function rundowns()
    {
        return $this->hasMany(Rundown::class, 'id_paket');
    }

    /**
     * Mendefinisikan kumpulan pesanan yang menggunakan paket.
     */
    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'id_paket');
    }
}
