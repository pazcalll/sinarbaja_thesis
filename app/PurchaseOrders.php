<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use stdClass;

class PurchaseOrders
{
    public $id;
    public $no_nota;
    public $user_id;
    public $total_harga = 0;
    public $tanggal_pesan;
    public $order = [];
    public $tagihan;

    public function __construct($id, $no_nota, $user_id, $tanggal_pesan)
    {
        $this->id = $id;
        $this->no_nota = $no_nota;
        $this->user_id = $user_id;
        $this->tanggal_pesan = $tanggal_pesan;
        
        $order_query = DB::table('orders')
            ->where('po_id', $id)
            ->where('status', '!=', 'AWAL PESAN')
            ->get();
        foreach ($order_query as $key => $value) {
            // dd($value->product_id);
            $this->order[] = new Order(
                $value->id,
                $value->po_id,
                $value->product_id,
                $value->qty,
                $value->status,
                $value->harga_order,
                $value->nama_barang
            );
            $this->total_harga += $value->qty * $value->harga_order;
        }

        $tagihan_query = DB::table('tagihans')
            ->where('po_id', $id)
            ->get();
        if (count($tagihan_query) > 0) {
            $this->tagihan = new Tagihan(
                $tagihan_query[0]->id,
                $tagihan_query[0]->po_id,
                $tagihan_query[0]->nominal_total,
                $tagihan_query[0]->status,
                $tagihan_query[0]->kirim
            );
        }
    }
    
    public function getThis()
    {
        $attr = new stdClass();
        $attr->id = $this->id;
        $attr->no_nota = $this->no_nota;
        $attr->user_id = $this->user_id;
        $attr->total_harga = $this->total_harga;
        $attr->tanggal_pesan = $this->tanggal_pesan;
        $attr->order = $this->order;
        $attr->tagihan = $this->tagihan;
        return $attr;
    }

    public function setTagihan($tagihan)
    {
        $this->tagihan = $tagihan;
    }
}
