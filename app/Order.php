<?php

namespace App;

use Illuminate\Support\Facades\DB;
use stdClass;

class Order
{
    protected $id;
    protected $po_id;
    protected $product_id;
    protected $qty;
    protected $status;
    protected $harga_order;
    protected $nama_barang;

    public function __construct($id, $po_id, $product_id, $qty, $status, $harga_order, $nama_barang)
    {
        $this->id = $id;
        $this->po_id = $po_id;
        $this->product_id = $product_id;
        $this->qty = $qty;
        $this->status = $status;
        $this->harga_order = $harga_order;
        $this->nama_barang = $nama_barang;
    }

    public function getThis()
    {
        $getThis = new stdClass();
        $getThis->id;
        $getThis->po_id;
        $getThis->product_id;
        $getThis->qty;
        $getThis->status;
        $getThis->harga_order;
        $getThis->nama_barang;
        return $getThis;
    }
    
    public function save()
    {
        DB::table('orders')
            ->insert([
                'po_id' => $this->po_id,
                'product_id' => $this->product_id,
                'qty' => $this->qty,
                'status' => $this->status,
                'harga_order' => $this->harga_order,
                'nama_barang' => $this->nama_barang
            ]);
    }
}
