<?php
namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StokImport implements WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function headingRow(): int
    {
        return 1;
    }
}