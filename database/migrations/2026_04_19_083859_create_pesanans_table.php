<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_paket')->nullable();
            $table->string('nama_pemesan');
            $table->string('no_hp');
            $table->decimal('diskon', 15, 2)->default(0);
            $table->decimal('total_harga', 15, 2);
            $table->date('tanggal_acara');
            $table->integer('jumlah_orang')->default(1);
            $table->string('invoice')->unique()->nullable();
            $table->string('status')->nullable();
            $table->boolean('is_custom')->default(false);
            $table->json('custom_places')->nullable();
            $table->json('custom_fasilitas')->nullable();
            $table->foreign('id_paket')->references('id')->on('pakets')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
