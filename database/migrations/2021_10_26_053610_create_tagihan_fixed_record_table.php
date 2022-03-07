<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagihanFixedRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        if (!Schema::hasTable('tagihan_fixed_record')) {
            Schema::create('tagihan_fixed_record', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tagihan_id')->nullable();
                $table->string('nama_barang');
                $table->integer('harga_per_barang');
                $table->integer('qty_disetujui');
                $table->timestamps();
    
                $table->foreign('tagihan_id')->references('id')->on('tagihans')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tagihan_fixed_record');
    }
}
