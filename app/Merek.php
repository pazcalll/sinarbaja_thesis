<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Merek extends Model
{
    //
    use Columns;
    public function product() {
        return $this->hasMany(Product::class, 'merek');
    }
}
