<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'tbl_satuan';
    protected $fillable = array('satuan_nama', 'satuan_satuan', 'konversi');
}
