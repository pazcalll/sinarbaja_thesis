<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    //
    // use Columns;
    // protected $visible = ['status'];
    
    protected $fillable = array('tagihan_id', 'status', 'id_user');

    public function orders() {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function drivers() {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'id_user');
    }
}
