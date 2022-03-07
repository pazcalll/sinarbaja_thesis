<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Gudang;

class logstocks extends Model {

    protected $table = 'tbl_log_stok';
    protected $fillable = array('id_barang', 'id_ref_gudang', 'id_satuan', 'tanggal', 'unit_masuk', 'unit_keluar', 'status', 'id_user');

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function gudang() {
        return $this->belongsTo(Gudang::class, 'id_ref_gudang');
    }
}