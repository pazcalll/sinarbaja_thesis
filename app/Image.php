<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    // use Columns;
    protected $fillable = array('id', 'path', 'product_id', 'barang_alias');
}
