<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJenisPhotobook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_photobook', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('ukuran');
            $table->integer('jumlah_foto');
            $table->integer('jumlah_halaman');
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
        Schema::dropIfExists('jenis_photobook');
    }
}
