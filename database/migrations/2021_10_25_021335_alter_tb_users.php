<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTbUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function($table){
            if (!Schema::hasColumn('users', 'id_group')) {
                $table->unsignedBigInteger('id_group')->nullable();
                $table->foreign('id_group')->references('id')->on('group_users')->onDelete('cascade');
            }
            if (Schema::hasColumn('users', 'group_id')) {
                $table->dropColumn('group_id');    
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
        //
    }
}
