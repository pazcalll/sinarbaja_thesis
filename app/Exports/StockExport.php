<?php

namespace App\Exports;

// use App\BarangModel;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class StockExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $barang = DB::table('tbl_barang')->leftjoin('tbl_satuan','tbl_satuan.satuan_id','=','tbl_barang.satuan_id')
            ->join('tbl_log_stok', 'tbl_barang.barang_id', '=', 'tbl_log_stok.id_barang')
            ->orderBy('barang_id', 'ASC')
            ->select(DB::raw('
                tbl_barang.barang_id as barang_id,
                tbl_barang.barang_kode as barang_kode,
                tbl_barang.barang_nama as barang_nama,
                tbl_satuan.satuan_nama as nama_satuan,
                tbl_satuan.satuan_id as id_satuan,
                SUM( tbl_log_stok.unit_masuk - tbl_log_stok.unit_keluar ) AS stok'
        ))
            ->groupBy('tbl_barang.barang_id');
        $barang2 = DB::table('tbl_barang')->leftjoin('tbl_satuan','tbl_satuan.satuan_id','=','tbl_barang.satuan_id')
            // ->leftjoin('tbl_log_stok', 'tbl_barang.barang_id', '=', 'tbl_log_stok.id_barang')
            // ->leftjoin('ref_gudang', 'ref_barang.')
            ->orderBy('tbl_barang.barang_id', 'ASC')
            ->whereNotIn('tbl_barang.barang_id', function($query){
                $query->select('id_barang')->from('tbl_log_stok')->groupBy('id_barang');
            })
            ->selectRaw('
                tbl_barang.barang_id as barang_id,
                tbl_barang.barang_kode as barang_kode,
                tbl_barang.barang_nama as barang_nama,
                tbl_satuan.satuan_nama as nama_satuan,
                tbl_satuan.satuan_id as id_satuan,
                "0" AS stok
            ')
            ->unionAll($barang)
            ->orderBy('barang_id', 'ASC')
            ->get()->toArray();
            // dd($barang2);
        return view('exports.exportStok')->with('arrayExcel', $barang2);
    }
}
