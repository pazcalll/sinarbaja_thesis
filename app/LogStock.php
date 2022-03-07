<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogStock extends Model
{
    use Columns;

    public function stock() {
        return $this->belongsTo(Stock::class, 'stock_id');
    }
}
