<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Gudang extends Model {

    protected $table = 'ref_gudang';
    protected $fillable = array('id_profil', 'nama', 'alamat', 'status', 'kode');


}
