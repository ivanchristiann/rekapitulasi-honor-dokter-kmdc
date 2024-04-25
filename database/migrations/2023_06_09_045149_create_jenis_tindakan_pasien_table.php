<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisTindakanPasienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_tindakan_pasien', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pasien_id');
            $table->foreign('pasien_id')->references('id')->on('pasiens');

            $table->unsignedBigInteger('jenis_tindakan_id');
            $table->foreign('jenis_tindakan_id')->references('id')->on('jenis_tindakans');

            $table->unsignedBigInteger('dokter_id');
            $table->foreign('dokter_id')->references('id')->on('dokters');

            $table->unsignedBigInteger('admin_id');
            $table->foreign('admin_id')->references('id')->on('admins');

            $table->unsignedBigInteger('diagnosa_id');
            $table->foreign('diagnosa_id')->references('id')->on('diagnosas');

            $table->dateTime('tanggal_kunjungan')->default(now());
            $table->integer('jumlah_tindakan');
            $table->double('total_biaya');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jenis_tindakan_pasien');
    }
}
