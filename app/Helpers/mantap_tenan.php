<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

class mantap_tenan
{
    public static function curr($string)
    {
        return number_format($string,2,",",".");
    }
}
