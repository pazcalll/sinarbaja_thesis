<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTbPurchaseOrderNOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('purchase_orders')) {
            if (!Schema::hasColumn('purchase_orders', 'potongan_po_rp')) {
                Schema::table('purchase_orders', function($table) {
                    $table->integer('potongan_po_rp')->nullable()->default(0);
                    $table->integer('potongan_po_persen')->nullable()->default(0);
                });
            }
            if (Schema::hasTable('orders')) {
                if (!Schema::hasColumn('orders', 'potongan_order_rp')) {
                    Schema::table('orders', function($table) {
                        $table->integer('potongan_order_rp')->nullable()->default(0);
                        $table->integer('potongan_order_persen')->nullable()->default(0);
                    });
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('purchase_orders')) {
            if (Schema::hasColumn('purchase_orders', 'potongan_po_rp')) {
                Schema::table('purchase_orders', function($table) {
                    $table->dropColumn('potongan_po_rp');
                    $table->dropColumn('potongan_po_persen');
                });
            }
            if (Schema::hasTable('orders')) {
                if (Schema::hasColumn('orders', 'potongan_order_persen')) {
                    Schema::table('orders', function($table) {
                        $table->dropColumn('potongan_order_persen');
                        $table->dropColumn('potongan_order_rp');
                    });
                }
            }
        }
    }
}
