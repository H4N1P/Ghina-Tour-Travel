<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanans';

    protected $fillable = [
        'id_paket',
        'nama_pemesan',
        'no_hp',
        'diskon',
        'jumlah_orang',
        'total_harga',
        'tanggal_acara',
        'tanggal_selesai',
        'invoice',
        'status',
        'is_custom',
        'custom_places',
        'custom_fasilitas',
    ];

    protected $casts = [
        'tanggal_acara' => 'date',
        'tanggal_selesai' => 'date',
        'custom_places' => 'array',
        'custom_fasilitas' => 'array',
    ];

    /**
     * Mendefinisikan paket yang digunakan oleh pesanan.
     */
    public function paket()
    {
        return $this->belongsTo(Paket::class, 'id_paket');
    }

    /**
     * Memeriksa apakah pesanan dibuat sebagai paket custom.
     */
    public function isCustom()
    {
        return $this->is_custom;
    }

    /**
     * Memeriksa apakah status pesanan sudah final dan tidak dapat diubah lagi.
     */
    public function isFinal(): bool
    {
        return in_array($this->status, ['selesai', 'batal'], true);
    }

    /**
     * Memformat tanggal mulai dan selesai menjadi label rentang yang mudah dibaca.
     */
    public function formatRentangTanggal(string $format = 'd M Y'): string
    {
        if (!$this->tanggal_acara) {
            return '-';
        }

        $tanggalSelesai = $this->tanggal_selesai ?? $this->tanggal_acara;
        $tanggalMulaiLabel = $this->tanggal_acara->translatedFormat($format);

        if ($this->tanggal_acara->isSameDay($tanggalSelesai)) {
            return $tanggalMulaiLabel;
        }

        return $tanggalMulaiLabel . ' - ' . $tanggalSelesai->translatedFormat($format);
    }

    /**
     * Mengembalikan daftar tujuan dari pesanan custom atau paket terkait.
     */
    public function getPlacesAttribute()
    {
        if ($this->is_custom) {
            return $this->custom_places ?? [];
        }
        return $this->paket?->destinasis->pluck('nama_destinasi') ?? [];
    }
}
