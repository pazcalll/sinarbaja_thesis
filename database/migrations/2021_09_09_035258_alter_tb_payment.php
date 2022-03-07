<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTbPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('payments', function($table) {    
            if (!Schema::hasColumn('payments', 'tagihan_id')) {
                $table->unsignedBigInteger('tagihan_id')->nullable();
        
                $table->foreign('tagihan_id')->references('id')->on('tagihans')->onDelete('cascade');      
            }
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    // public function down()
    // {
    //     Schema::disableForeignKeyConstraints();
    //     Schema::table('payments', function($table) {
    //         $table->dropColumn('tagihan_id');            
    //     });    
    // }
}
