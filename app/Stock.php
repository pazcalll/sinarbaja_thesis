<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    //
    // use Columns;
    protected $fillable = array('user_id', 'product_id', 'stock');

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
