<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTbEmailNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('users', 'id_gudang')) {
            Schema::table('users', function(Blueprint $table){
                $table->string('email')->nullable()->change();
                $table->integer('id_gudang')->nullable()->default(1);
    
                $table->foreign('id_gudang')->references('id')->on('ref_gudang')->onDelete('cascade');
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
        //
    }
}
