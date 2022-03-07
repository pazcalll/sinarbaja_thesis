<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Order;
use App\HargaProdukUser;
use App\HargaProdukGroup;

class barang extends Model
{
      protected $table = 'tbl_barang';
      protected $primaryKey = 'barang_id';
      protected $fillable = array('barang_id', 'satuan_id', 'barang_kode', 'barang_nama', 'barang_alias');

      public function order() {
            return $this->hasMany(Order::class, 'product_id');
      }

      public function harga_group() {
            return $this->hasMany(HargaProdukGroup::class, 'id_product');
      }

      public function harga_user() {
            return $this->hasMany(HargaProdukUser::class, 'id_product');
      }

      // function group
}
