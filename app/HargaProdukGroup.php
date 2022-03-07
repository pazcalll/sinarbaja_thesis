<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HargaProdukGroup extends Model
{
    //
    protected $table = 'harga_produk_group';
    protected $fillable = array('id', 'id_group', 'id_product', 'harga_group');

    function group(){
        return $this->belongsTo(GroupUser::class, 'id_group');
    }

    function product(){
        return $this->belongsTo(Product::class, 'id_product');
    }
}
