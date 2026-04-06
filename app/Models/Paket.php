<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    protected $fillable = [
        'nama_paket',
        'harga_paket',
        'pax',
        'note',
    ];

    public function tempats()
    {
        return $this->hasMany(Tempat::class);
    }

    public function konsumsis()
    {
        return $this->hasMany(Konsumsi::class);
    }

    public function akomodasis()
    {
        return $this->hasMany(Akomodasi::class);
    }

    public function transportasis()
    {
        return $this->hasMany(Transportasi::class);
    }
}
