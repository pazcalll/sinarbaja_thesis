<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHargaProdukUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('harga_produk_user')) {
            Schema::create('harga_produk_user', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_group')->nullable();
                $table->unsignedBigInteger('id_product')->nullable();
                $table->unsignedBigInteger('id_user')->nullable();
                $table->integer('harga_user');
                $table->timestamps();
    
                $table->foreign('id_group')->references('id')->on('group_users')->onDelete('cascade');
                $table->foreign('id_product')->references('id')->on('products')->onDelete('cascade');
                $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('harga_produk_user');
    }
}
