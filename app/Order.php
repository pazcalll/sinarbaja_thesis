<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\barang;

class Order extends Model
{
    //
    // use Columns;
    protected $fillable = array('po_id', 'product_id', 'qty', 'status', 'tagihan_id', 'harga_order', 'nama_barang', 'order');

    public function po() {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    // public function product() {
    //     return $this->belongsTo(Product::class, 'product_id');
    // }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'barang_id');
    }

    public function tagihan() {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }

    public function tracking() {
        return $this->hasMany(Tracking::class, 'order_id');
    }
    public function order() {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function barang() {
        return $this->belongsTo(barang::class, 'product_id');
    }


}
