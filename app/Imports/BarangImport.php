<?php

namespace App\Imports;

use App\BarangModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BarangImport implements WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function model(array $row)
    // {
    //     // dd($row);
    //     return new BarangModel([
    //         //
    //         'barang_kode' => $row['kode_item'],
    //         'barang_nama' => $row['nama_item'],
    //         'nama_barang_asli' => $row['keterangan'],
    //         'barang_alias' => $row['jenis'],
    //     ]);
    // }
    public function headingRow(): int
    {
        return 1;
    }
}
