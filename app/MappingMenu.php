<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MappingMenu extends Model
{
    // use Columns;
    
    public function menu() {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
