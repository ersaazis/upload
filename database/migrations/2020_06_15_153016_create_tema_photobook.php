<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemaPhotobook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tema_photobook', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('jenis_photobook_id');
            $table->unsignedBigInteger('kategori_tema_id');
            $table->string('nama');
            $table->string('fliphtml5_url');
            $table->foreign('kategori_tema_id')->references('id')->on('kategori_tema');
            $table->foreign('jenis_photobook_id')->references('id')->on('jenis_photobook');
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
        Schema::dropIfExists('tema_photobook');
    }
}
