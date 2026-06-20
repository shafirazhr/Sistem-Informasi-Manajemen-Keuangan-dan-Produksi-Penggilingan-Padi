<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');

            $table->foreignId('pembeli_id')
                ->nullable()
                ->constrained('pembelis')
                ->nullOnDelete();

            $table->decimal('total_harga', 15, 2)->default(0);
            $table->decimal('pembayaran', 15, 2)->default(0);
            $table->decimal('kembalian', 15, 2)->default(0);

            $table->enum('metode_pembayaran',['tunai','transfer'])->default('tunai');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};