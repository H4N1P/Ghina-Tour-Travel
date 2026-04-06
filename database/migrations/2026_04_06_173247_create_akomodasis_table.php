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
        Schema::create('akomodasis', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('fasilitas_akomodasi');
            $table->unsignedBigInteger('id_paket');
            $table->foreign('id_paket')->references('id')->on('pakets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akomodasis');
    }
};
