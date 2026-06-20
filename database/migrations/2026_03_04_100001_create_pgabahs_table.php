<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePgabahsTable extends Migration
{
    public function up()
    {
        Schema::create('pgabahs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produk_id');
            $table->date('tanggal');
            $table->string('nama_pemasok');
            $table->integer('jumlah_gabah');
            $table->decimal('harga', 15, 2);
            $table->timestamps();

            $table->foreign('produk_id')
                ->references('id')
                ->on('produks')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pgabahs');
    }
}