<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    //
    protected $fillable = array('id', 'status');

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }
}
