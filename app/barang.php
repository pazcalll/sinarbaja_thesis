<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barang
{
    public $barang_id;
    public $satuan_id;
    public $barang_kode;
    public $barang_nama;
    public $barang_alias;
    public $barangnama_asli;
    public $harga_user;

    public function __construct($barang_id, $satuan_id, $barang_kode, $barang_nama, $barang_alias, $barangnama_asli, $harga_user)
    {
        $this->barang_id = $barang_id;
        $this->satuan_id = $satuan_id;
        $this->barang_kode = $barang_kode;
        $this->barang_nama = $barang_nama;
        $this->barang_alias = $barang_alias;
        $this->barangnama_asli = $barangnama_asli;
        $this->harga_user = $harga_user;
    }
    
}
