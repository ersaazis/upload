<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectLayout extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_layout', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tema_photobook_id')->nullable();
            $table->unsignedBigInteger('jenis_photobook_id');
            $table->unsignedBigInteger('users_id');

            $table->string('kode_transaksi');
            $table->string('no_resi')->nullable();
            $table->string('foto_cover')->nullable();
            $table->string('text_cover')->nullable();
            $table->string('hasil_cover')->nullable();
            $table->string('hasil_layout')->nullable();
            $table->string('status');

            $table->foreign('users_id')->references('id')->on('users');
            $table->foreign('jenis_photobook_id')->references('id')->on('jenis_photobook');
            $table->foreign('tema_photobook_id')->references('id')->on('tema_photobook');
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
        Schema::dropIfExists('project_layout');
    }
}
