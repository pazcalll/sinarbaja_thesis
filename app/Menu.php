<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'id', 'nama', 'link', 'parent', 'urutan', 'icon'
    ];
    
    public function map() {
        return $this->hasMany(MappingMenu::class, 'menu_id');
    }

    public function parentString() {
        return $this->belongsTo(Menu::class, 'parent');
    }
}
