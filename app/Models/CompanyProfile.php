<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    public const DEFAULT_WHATSAPP = '085707733901';

    protected $fillable = [
        'about',
        'vision_mission',
        'whatsapp',
        'email',
        'address',
        'instagram',
    ];

    /**
     * Menormalkan nomor WhatsApp menjadi format lokal untuk ditampilkan.
     */
    public static function whatsappDisplay(?string $value): string
    {
        $digits = preg_replace('/\D/', '', $value ?: self::DEFAULT_WHATSAPP);

        if ($digits === '') {
            $digits = self::DEFAULT_WHATSAPP;
        }

        if (str_starts_with($digits, '62')) {
            return '0' . substr($digits, 2);
        }

        return str_starts_with($digits, '0') ? $digits : '0' . $digits;
    }

    /**
     * Menormalkan nomor WhatsApp menjadi format internasional untuk tautan wa.me.
     */
    public static function whatsappLinkNumber(?string $value): string
    {
        $localNumber = self::whatsappDisplay($value);

        return '62' . ltrim($localNumber, '0');
    }
}
