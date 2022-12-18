<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class BarangExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return BarangModel::all();
    // }
    public function view(): View
    {
        $query = DB::select("SELECT
            tb.barang_id as id_barang,
            tb.barang_kode as barang_kode,
            tb.barang_nama as barang_nama,
            tb.barang_alias as barang_alias,
            ts.satuan_satuan as barang_satuan,
            tb.barangnama_asli as barangnama_asli,
            sum(case when hgp.id_group = 2 then hgp.harga_group else 0 end) as h1,
            sum(case when hgp.id_group = 3 then hgp.harga_group else 0 end) as h2,
            sum(case when hgp.id_group = 4 then hgp.harga_group else 0 end) as h3,
            sum(case when hgp.id_group = 5 then hgp.harga_group else 0 end) as h4
            FROM tbl_barang as tb
            LEFT JOIN harga_produk_group as hgp
            ON hgp.id_product = tb.barang_id
            LEFT JOIN tbl_satuan as ts
            ON ts.satuan_id = tb.satuan_id
            GROUP BY tb.barang_kode
            ORDER BY tb.barang_id ASC;
        ");
        $arrayExcel = [];
        $query = json_decode(json_encode($query), true);
        foreach ($query as $key => $value) {
            $arrayExcel[] = [
                "id_barang" => $value['id_barang'],
                "kode_item" => $value['barang_kode'],
                "nama_item" => $value['barang_nama'],
                "barang_alias" => $value['barang_alias'],
                "satuan" => $value['barang_satuan'],
                "harga1" => $value['h1'],
                "harga2" => $value['h2'],
                "harga3" => $value['h3'],
                "harga4" => $value['h4'],
                "keterangan" => $value['barangnama_asli']
            ];
        }
        // dd($arrayExcel);
        return view('exports.exportBarang')->with('arrayExcel', $arrayExcel);
    }
}
