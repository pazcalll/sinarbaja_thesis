<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HargaProdukUser extends Model
{
    //
    protected $table = 'harga_produk_user';
    protected $fillable = array('id_group', 'id_product', 'id_user', 'harga_user');

    function user(){
        return $this->belongsTo(User::class, 'id_user');
    }

    function group() {
        return $this->belongsTo(GroupUser::class, 'id_group');
    }

    function product() {
        return $this->belongsTo(Product::class, 'id_product');
    }

    function hargaGroup()
    {
        return $this->belongsTo(HargaProdukGroup::class, 'harga_user');
    }
}
