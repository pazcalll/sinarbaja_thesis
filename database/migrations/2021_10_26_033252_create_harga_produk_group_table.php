<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHargaProdukGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        if (!Schema::hasTable('harga_produk_group')) {
            Schema::create('harga_produk_group', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_group')->nullable();
                $table->unsignedBigInteger('id_product')->nullable();
                $table->integer('harga_group');
                $table->timestamps();
    
                $table->foreign('id_group')->references('id')->on('group_users')->onDelete('cascade');
                $table->foreign('id_product')->references('id')->on('products')->onDelete('cascade');
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
        Schema::dropIfExists('harga_produk_group');
    }
}
