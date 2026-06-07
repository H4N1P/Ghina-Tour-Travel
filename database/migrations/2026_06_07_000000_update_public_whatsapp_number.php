<?php

use App\Models\CompanyProfile;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Menetapkan nomor WhatsApp publik aktif ke nomor resmi terbaru.
     */
    public function up(): void
    {
        DB::table('company_profiles')->update([
            'whatsapp' => CompanyProfile::DEFAULT_WHATSAPP,
        ]);
    }

    /**
     * Tidak mengembalikan nomor lama karena nilainya tidak dapat dipastikan.
     */
    public function down(): void
    {
        //
    }
};
