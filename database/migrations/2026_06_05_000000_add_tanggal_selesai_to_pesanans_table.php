<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->date('tanggal_selesai')->nullable()->after('tanggal_acara');
        });

        DB::table('pesanans')
            ->whereNull('tanggal_selesai')
            ->update(['tanggal_selesai' => DB::raw('tanggal_acara')]);
    }

    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn('tanggal_selesai');
        });
    }
};
