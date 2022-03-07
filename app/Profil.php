<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profil extends Model {

    protected $table = 'm_profil';
    protected $fillable = array('nama', 'inisial', 'telp', 'alamat', 'logo');
}