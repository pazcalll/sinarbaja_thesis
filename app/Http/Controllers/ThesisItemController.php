<?php

namespace App\Http\Controllers;

use App\Exports\BarangExport;
use App\Exports\StockExport;
use App\Imports\BarangImport;
use App\Imports\StokImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Excel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

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
    
    public function import_excel(Request $request)
    {
        DB::beginTransaction();
        try {
            $extensions = array("xls","xlsx","xlm","xla","xlc","xlt","xlw","csv");
            $result = array($request->file('file_excel')->getClientOriginalExtension());
            if(in_array($result[0],$extensions)){
                $path = $request->file('file_excel')->getRealPath();
                $file = $request->file('file_excel');
                $num = 1;
                $num_harga_group = 1;
                // membuat nama file unik
                $nama_file = rand().$file->getClientOriginalName();
                $array = Excel::toArray(new BarangImport, $file);
                // dd($array);
                foreach ($array as $key => $value) {
                    $getArr = $value;
                }
                foreach ($getArr as $key => $value) {
                    $satuan = DB::table('tbl_satuan')->select('satuan_id')->where('satuan_satuan',$value['satuan'])->first();
                    if ($satuan == null) {
                        DB::table('tbl_satuan')->insert(['satuan_nama' => $value['satuan'], 'satuan_satuan' => $value['satuan']]);
                        $satuan = DB::table('tbl_satuan')->select('satuan_id')->where('satuan_satuan',$value['satuan'])->first();
                    }
                    $product[] = array(
                        'barang_id' => $num,
                        'satuan_id' => $satuan->satuan_id,
                        'barang_nama' => $value['nama_item'],
                        'barang_kode' => $value['kode_item'],
                        'barang_alias' => $value['jenis'],
                        'barangnama_asli' => $value['nama_barang_asli']
                    );
                    $harga_group[] = array(
                        'id_barang' => $num,
                        '2' => $value['harga_level_1'],
                        '3' => $value['harga_level_2'],
                        '4' => $value['harga_level_3'],
                        '5' => $value['harga_level_4']
                    );

                    // $harga[]
                    $num++;
                }
                foreach ($harga_group as $key => $values) {
                    for ($i=2; $i < 6; $i++) {
                        $set_harga[] = array(
                            'id' => $num_harga_group++,
                            'id_group' => $i,
                            'id_product' => $values['id_barang'],
                            'harga_group' => $values[$i],
                        );
                        $tbl_detail_harga[] = array(
                            'id_group' => $i,
                            'barang_id' => $values['id_barang'],
                            'detail_harga_barang_tanggal' => date('Y-m-d'),
                            'detail_harga_barang_harga_jual' => $values[$i]
                        );
                    }
                }
                DB::table('tbl_barang')->delete();
                $insert_data = collect($product);
                $chunks = $insert_data->chunk(1000);
                foreach ($chunks as $chunk){
                    $insert_tbl_barang = DB::table('tbl_barang')->insert($chunk->toArray());
                }
                if ($insert_tbl_barang == 'true') {
                    DB::table('harga_produk_group')->delete();
                    DB::table('tbl_detail_harga_barang')->delete();
                    DB::statement("ALTER TABLE tbl_detail_harga_barang AUTO_INCREMENT =  1");
                    $data_grpup_harga = collect($set_harga);
                    $chunks_harga = $data_grpup_harga->chunk(1000);
                    foreach ($chunks_harga as $chunks_hargas){
                        $insert_tbl_harga_group = DB::table('harga_produk_group')->insert($chunks_hargas->toArray());
                    }
                    $data_detail_harga = collect($tbl_detail_harga);
                    $chunks_detail_harga = $data_detail_harga->chunk(1000);
                    foreach ($chunks_detail_harga as $chunks_detail_hargas){
                        $insert_tbl_detail_harga = DB::table('tbl_detail_harga_barang')->insert($chunks_detail_hargas->toArray());
                    }
                }
                if ($insert_tbl_harga_group == 'true' and $insert_tbl_detail_harga == 'true') {
                    $users = DB::table('users')->select('id AS user_id','id_group AS id_group')->get();
                    foreach ($users as $key => $value) {
                        $harga_group_get = DB::table('harga_produk_group')->where('id_group',$value->id_group)->get();
                        if (count($harga_group_get) > 0) {
                            foreach ($harga_group_get as $key => $value_harga) {
                                $groupOld = DB::table('harga_produk_user')
                                    ->where('id_user','=',$value->user_id)
                                    ->where('id_product',$value_harga->id_product)
                                    ->first();
                                $idG = !empty($groupOld)?$groupOld->id_group:$value->id_group;
                                $newHarga = DB::table('harga_produk_group')
                                    ->where('id_group',$idG)
                                    ->where('id_product',$value_harga->id_product)
                                    ->first();
                                $harga_user_group[] = array(
                                    'id_group' => $idG,
                                    'id_product' => $value_harga->id_product,
                                    'id_user' => $value->user_id,
                                    'harga_user' => $newHarga->harga_group
                                );
                            }
                        }
                    }
                }
                DB::table('harga_produk_user')->delete();
                DB::statement("ALTER TABLE harga_produk_user AUTO_INCREMENT =  1");
                $data_harga_user = collect($harga_user_group);
                $chunk_harga_user = $data_harga_user->chunk(1000);
                foreach ($chunk_harga_user as $chunk){
                    $inser_tbl_harga_user = DB::table('harga_produk_user')->insert($chunk->toArray());
                }
                if ($inser_tbl_harga_user) {
                    DB::commit();
                    return response('Success', 200);
                }
            }
        } catch (\Exception $e) {
            dd($e);
        }
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
            // $row[] = '<div class="btn-group"><a href="" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="botttom" title="Detail Harga Barang"><i class="fa fa-plus"></i></a>
            // <a onclick="editForm('.$list->barang_id.')" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="botttom" title="Edit Data"  style="color:white;"><i class="fa fa-edit"></i></a>
            // <a onclick="barcode('.$list->barang_id.','.$list->satuan_id.')" class="btn btn-xs btn-warning" data-toggle="tooltip" data-placement="botttom" title="QR Code"  style="color:black;"><i class="fa fa-qrcode"></i></a>
            // <a onclick="deleteData('.$list->barang_id.')" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="botttom" title="Hapus Data" style="color:white;"><i class="fa  fa-trash"></i></a></div>';
            $data[] = $row;

        }
        $output = array("data" => $data);
        return response()->json($output);
    }

    public function list_harga($id){
        $harga=DB::select("SELECT tb.barang_id, tb.barang_nama, td.detail_harga_barang_tanggal, td.detail_harga_barang_harga_jual, gu.group_name
            FROM tbl_barang as tb LEFT JOIN tbl_detail_harga_barang as td
            ON tb.barang_id = td.barang_id
            RIGHT JOIN group_users as gu
            ON td.id_group = gu.id
            WHERE td.barang_id = $id
        ");
        return response(json_encode((array)$harga), 200);
    }

    public function stockTable()
    {
        return view('adminThesis.itemStock');
    }

    public function allItemStock(){
        $persediaan = DB::select("SELECT *
            FROM
                (
                SELECT
                    tls.*,
                    tb.barang_kode AS kode_barang,
                    tb.barang_nama AS nama_barang,
                    SUM( tls.unit_masuk - tls.unit_keluar ) AS stok,
                    ts.satuan_nama AS nama_satuan
                FROM
                    tbl_log_stok AS tls
                    LEFT JOIN tbl_barang AS tb ON tls.id_barang = barang_id
                    LEFT JOIN tbl_satuan AS ts ON tls.id_satuan = ts.satuan_id
                GROUP BY
                    id_barang,
                    id_satuan
                ) as tbl
        ");
        $no = 0;
        $data = [];
        foreach ($persediaan as $key => $value) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $value->kode_barang;
            $row[] = $value->nama_barang;
            $row[] = $value->stok;
            $row[] = $value->nama_satuan;
            $data[] = $row;
        }
        $output = array("data" => $data);
        return response()->json($output);
    }

    public function importStock(Request $request)
    {
        try {
            //code...
            DB::beginTransaction();
            $extensions = array("xls","xlsx","xlm","xla","xlc","xlt","xlw","csv");
            $result = array($request->file('file_excel')->getClientOriginalExtension());
            if(!in_array($result[0],$extensions)) return response('Salah Format', 401);

            $item = Excel::toArray(new StokImport, $request->file('file_excel'))[0];
            $toSubmit = [];
            foreach ($item as $key => $value) {
                $tmp = [];
                if ($value['stok'] > 0) {
                    $tmp['log_stok_id'] = null;
                    $tmp['id_barang'] = $value['id_barang'];
                    $tmp['id_satuan'] = $value['id_satuan'];
                    $tmp['unit_masuk'] = $value['stok'];
                    $tmp['tanggal'] = Carbon::now()->toDateString();
                    $tmp['unit_keluar'] = 0;
                    $tmp['status'] = 'P1';
                    $tmp['id_user'] = Auth::user()->id;
                    $tmp['created_at'] = null;
                    $tmp['updated_at'] = null;
                    $toSubmit[] = $tmp;
                }
            }
            $insert = DB::table('tbl_log_stok')->insert($toSubmit);
            DB::commit();
            return response(".$insert.", 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response($th, 500);
        }
    }

    public function exportStock()
    {
        try {
            //code...
            return Excel::download(new StockExport, 'stock.xlsx');
        } catch (\Throwable $th) {
            return response($th, 500);
        }
    }
}
