<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTbTagihan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::disableForeignKeyConstraints();
        Schema::table('tagihans', function($table) {
            if (!Schema::hasColumn('tagihans', 'no_tagihan')) {
                $table->string('no_tagihan');
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
        Schema::table('tagihans', function($table){
            if (Schema::hasColumn('tagihans', 'no_tagihan')) {
                $table->dropColumn('no_tagihan');
            }
        });
    }
}
