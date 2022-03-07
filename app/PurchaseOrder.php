<?php

namespace App;
use App\Tagihan;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    //
    // use Columns;
    protected $fillable = array('no_nota', 'user_id', 'from', 'jatuh_tempo', 'potongan_po_rp', 'potongan_po_persen');

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function orders() {
        return $this->hasMany(Order::class, 'po_id');
    }

    public function tagihans() {
        return $this->hasMany(Tagihan::class, 'po_id');
    }

    public function order_last_status() {
        $data_last_status = Order::selectRaw('max(id) as id, id')->groupBy('id')->whereNotIn('status',['PENDING'])->pluck('id');
        return $this->hasMany(Order::class, 'po_id')->whereIn('id', $data_last_status)->orderBy('product_id','asc');
    }

    public function order_awal() {
        return $this->hasMany(Order::class, 'po_id')->whereIn('status', ['AWAL PESAN'])->orderBy('product_id','asc');
    }
}
