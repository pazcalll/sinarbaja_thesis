<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    // use Columns;
    protected $fillable = array('id', 'limit');
}
