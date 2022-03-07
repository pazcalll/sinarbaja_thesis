<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "tbl_barang";
    // protected $fillable = array('id','nama', 'deskripsi', 'merek', 'kategori', 'jenis_berbahaya', 'ditampilkan', 'barang_kode', 'barang_alias');
    protected $fillable = array('barang_id', 'satuan_id', 'barang_nama', 'barang_kode', 'barang_id_parent', 'barang_status_bahan', 'barang_alias', 'paper', 'barangnama_asli');

    public function category() {
        return $this->belongsTo(Category::class, 'kategori');
    }

    public function forecasts() {
        return $this->hasMany(Forecast::class, 'product_id');
    }

    public function images() {
        return $this->hasMany(Image::class, 'product_id');
    }

    public function image() {
        return $this->belongsTo(Image::class, 'barang_alias');
    }

    public function orders() {
        return $this->hasMany(Order::class, 'product_id');
    }

    public function stock() {
        return $this->belongsTo(Stock::class, 'id', 'product_id');
    }

    public function stocks() {
        return $this->hasMany(Stock::class, 'product_id');
    }

    public function variation() {
        return $this->hasMany(Variation::class, 'product_id');
    }

    public function merek() {
        return $this->belongsTo(Merek::class, 'merek');
    }

    public function harga_group(){
        return $this->hasMany(HargaProdukGroup::class, 'id_product', 'barang_id');
    }

    public function harga_user(){
        return $this->hasMany(HargaProdukUser::class, 'id_product', 'barang_id');
    }

}
