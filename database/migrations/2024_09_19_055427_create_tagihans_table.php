<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice');
            $table->string('nama_invoice');
            $table->date('tanggal_terbit')->nullable();
            $table->date('tanggal_lunas')->nullable();
            $table->string('bulan');
            $table->year('tahun');
            $table->unsignedBigInteger('user_penerbit_id')->nullable();
            $table->unsignedBigInteger('user_melunasi_id')->nullable();
            $table->enum('status',['Belum Lunas','Diajukan','Lunas'])->default('Belum Lunas');
            $table->string('bukti_pelunasan')->nullable();

            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onDelete('cascade');
            $table->foreignId('biaya_id')->constrained()->onDelete('cascade')->onDelete('cascade');
            $table->foreign('user_penerbit_id')->references('id')->on('users')->onDelete('cascade')->onDelete('cascade');
            $table->foreign('user_melunasi_id')->references('id')->on('users')->onDelete('cascade')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tagihans');
    }
};