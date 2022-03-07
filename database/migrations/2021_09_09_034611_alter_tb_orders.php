<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTbOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('orders', function($table) {
            if (!Schema::hasColumn('orders', 'tagihan_id')) {
                $table->unsignedBigInteger('tagihan_id')->nullable();
    
                $table->foreign('tagihan_id')->references('id')->on('tagihans')->onDelete('cascade');        
            }
            else if (!Schema::hasColumn('orders', 'harga_order')) {
                $table->bigInteger('harga_order')->nullable();
                $table->string('nama_barang')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    //     Schema::disableForeignKeyConstraints();
    //     Schema::table('orders', function($table) {
    //         $table->dropColumn('tagihan_id');
    //         $table->foreign('tagihan_id')->references('id')->on('tagihans')->onDelete('cascade');                    
    //     });
    }
}
