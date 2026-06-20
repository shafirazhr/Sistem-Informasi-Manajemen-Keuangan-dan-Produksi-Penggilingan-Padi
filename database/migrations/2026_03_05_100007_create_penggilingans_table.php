<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penggilingans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->integer('jumlah_padi');
            $table->integer('hasil_beras');
            $table->integer('hasil_bekatul');
            $table->integer('hasil_sekam');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penggilingans');
    }
};