<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use stdClass;

class Cart
{
    //
    protected $id;
    protected $jumlah;
    protected $barang;

    public function __construct($id, $jumlah, $barang)
    {
        $this->id = $id;
        $this->jumlah = $jumlah;
        $this->barang = $barang;
        // dd($this->barang);
    }

    public function getThis()
    {
        $cart = new stdClass();
        $cart->id = $this->id;
        $cart->jumlah = $this->jumlah;
        $cart->barang = $this->barang;
        return $cart;
    }
    
    public function setJumlah($jumlah)
    {
        $this->jumlah = $jumlah;
    }

}
