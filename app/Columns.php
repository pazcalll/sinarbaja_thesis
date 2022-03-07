<?php

namespace App;

use Illuminate\Support\Facades\Schema;

trait Columns
{
    public function getFillable()
    {
        return Schema::getColumnListing($this->getTable());
    }
}
