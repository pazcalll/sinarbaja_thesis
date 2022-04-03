<?php

namespace App\Http\Controllers;

use App\Exports\BarangExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Excel;

class ThesisItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('adminThesis.itemTable');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function export_excel()
    {
      return Excel::download(new BarangExport, 'Produk Barang.xlsx');
    }
    
    public function listData()
    {
        $barang = DB::table('tbl_barang')->leftjoin('tbl_satuan','tbl_satuan.satuan_id','=','tbl_barang.satuan_id')
            ->join('tbl_log_stok', 'tbl_barang.barang_id', '=', 'tbl_log_stok.id_barang')
            ->orderBy('barang_id', 'ASC')
            ->select(DB::raw('
                barang_id,
                tbl_barang.satuan_id,
                tbl_barang.barang_kode,
                tbl_barang.barang_nama,
                tbl_barang.barang_alias,
                tbl_barang.barangnama_asli,
                satuan_nama,
                satuan_satuan,
                SUM( tbl_log_stok.unit_masuk - tbl_log_stok.unit_keluar ) AS stok'
                ))
            ->groupBy('tbl_barang.barang_id');
        $barang2 = DB::table('tbl_barang')->leftjoin('tbl_satuan','tbl_satuan.satuan_id','=','tbl_barang.satuan_id')
            ->orderBy('tbl_barang.barang_id', 'ASC')
            ->whereNotIn('tbl_barang.barang_id', function($query){
                $query->select('id_barang')->from('tbl_log_stok')->groupBy('id_barang');
            })
            ->selectRaw('
                tbl_barang.barang_id,
                tbl_barang.satuan_id,
                tbl_barang.barang_kode,
                tbl_barang.barang_nama,
                tbl_barang.barang_alias,
                tbl_barang.barangnama_asli,
                tbl_satuan.satuan_nama,
                tbl_satuan.satuan_satuan,
                "0" AS stok'
                )
            ->unionAll($barang)
            ->get();
        $no = 0;
        $data = array();
        foreach ($barang2 as $list) {
            //id parent

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $list->barang_alias;
            $row[] = $list->barangnama_asli;
            $row[] = $list->barang_kode;
            $row[] = $list->barang_nama;
            $row[] = $list->satuan_nama;
            $row[] = $list->stok;
            $row[] = '<a onclick="showListHarga('.$list->barang_id.')" class="btn btn-xs btn-info" data-toggle="tooltip" data-placement="botttom" title="Lihat Harga Barang" style="color:white;"><i class="fa fa-list"> Cek Harga</i></a>';
            $row[] = '<div class="btn-group"><a href="" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="botttom" title="Detail Harga Barang"><i class="fa fa-plus"></i></a>
            <a onclick="editForm('.$list->barang_id.')" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="botttom" title="Edit Data"  style="color:white;"><i class="fa fa-edit"></i></a>
            <a onclick="barcode('.$list->barang_id.','.$list->satuan_id.')" class="btn btn-xs btn-warning" data-toggle="tooltip" data-placement="botttom" title="QR Code"  style="color:black;"><i class="fa fa-qrcode"></i></a>
            <a onclick="deleteData('.$list->barang_id.')" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="botttom" title="Hapus Data" style="color:white;"><i class="fa  fa-trash"></i></a></div>';
            $data[] = $row;

        }
        $output = array("data" => $data);
        return response()->json($output);
    }
}
