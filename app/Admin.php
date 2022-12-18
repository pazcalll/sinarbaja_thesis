<?php

namespace App;

use App\Exports\BarangExport;
use App\Exports\StockExport;
use App\Imports\BarangImport;
use App\Imports\StokImport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class Admin extends User
{
    public function __construct()
    {
        parent::__construct(Auth::user()->toArray());
    }
    
    public function allUser()
    {
        $users = DB::select('SELECT u.*, gu.group_name as group_name
            From users as u 
            left join group_users as gu on u.id_group = gu.id
            where u.id != '.$this->id.'
        ');
        return $users;
    }
    
    public function destroyUser($id)
    {
        //
        $delete = DB::table('users')->where('id', $id)->delete();
        return $delete;
    }

    public function sendList()
    {
        $data = DB::select('SELECT po.*, 
                SUM(o.qty * o.harga_order) as total_harga, 
                CONCAT( "[", GROUP_CONCAT(JSON_OBJECT("nama_barang", o.nama_barang, "qty", o.qty, "harga", o.harga_order)), "]") as barang,
                t.kirim,
                t.status as status_pembayaran,
                u.name as user_name,
                gu.group_name
            FROM purchase_orders AS po
            LEFT JOIN orders as o 
                ON o.po_id = po.id
            LEFT JOIN tagihans as t
                ON t.po_id = po.id
            LEFT JOIN users as u
                ON u.id = po.user_id
            LEFT JOIN group_users as gu
                ON u.id_group = gu.id
            WHERE o.status = "DISETUJUI SEMUA" 
                AND t.kirim = "BELUM"
            GROUP BY po.no_nota
        ');
        return datatables($data)->toJson();
    }

    public function approvalUrl($request)
    {
        $po_id = $request->post('po_id');
        $bukti_tf = DB::select('SELECT *
            FROM payments
            WHERE po_id = '.$po_id.'
        ');
        return response(collect($bukti_tf)->first()->bukti_tf, 200);
    }

    public function approvalBill($request)
    {
        DB::beginTransaction();
        try {
            if ($request->acceptance == "true") {
                DB::table('payments')
                    ->where('po_id', $request->po_id)
                    ->update(['valid'=>2]);
                DB::table('tagihans')
                    ->where('po_id', $request->po_id)
                    ->update(['status' => 'LUNAS']);
            }elseif ($request->acceptance == "false") {
                DB::table('payments')
                    ->where('po_id', $request->po_id)
                    ->delete();
                DB::table('tagihans')
                    ->where('po_id', $request->po_id)
                    ->update(['status' => 'BELUM DIBAYAR']);
            }
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response($th, 500);
        }
    }

    public function sendOrder($request)
    {
        try {
            DB::beginTransaction();
            DB::table('tagihans')
                ->where('po_id', $request->po_id)
                ->where('status', $request->status_pembayaran)
                ->update(['kirim' => "PERJALANAN"]);
            DB::commit();
        } catch (\Throwable $th) {
            return response($th, 500);
        }
    }

    public function sendingList()
    {
        $data = DB::select('SELECT po.*, 
                SUM(o.qty * o.harga_order) as total_harga, 
                CONCAT( "[", GROUP_CONCAT(JSON_OBJECT("nama_barang", o.nama_barang, "qty", o.qty, "harga", o.harga_order)), "]") as barang,
                t.kirim,
                t.status as status_pembayaran,
                u.name as user_name,
                gu.group_name
            FROM purchase_orders AS po
            LEFT JOIN orders as o 
                ON o.po_id = po.id
            LEFT JOIN tagihans as t
                ON t.po_id = po.id
            LEFT JOIN users as u
                ON u.id = po.user_id
            LEFT JOIN group_users as gu
                ON u.id_group = gu.id
            WHERE o.status = "DISETUJUI SEMUA" 
                AND t.kirim = "PERJALANAN"
            GROUP BY po.no_nota
        ');
        return datatables($data)->toJson();
    }

    public function completedList()
    {
        $data = DB::select('SELECT po.*, 
                SUM(o.qty * o.harga_order) as total_harga, 
                CONCAT( "[", GROUP_CONCAT(JSON_OBJECT("nama_barang", o.nama_barang, "qty", o.qty, "harga", o.harga_order)), "]") as barang,
                t.kirim,
                t.status as status_pembayaran,
                u.name as user_name,
                gu.group_name
            FROM purchase_orders AS po
            LEFT JOIN orders as o 
                ON o.po_id = po.id
            LEFT JOIN tagihans as t
                ON t.po_id = po.id
            LEFT JOIN users as u
                ON u.id = po.user_id
            LEFT JOIN group_users as gu
                ON u.id_group = gu.id
            WHERE o.status = "DISETUJUI SEMUA" 
                AND t.kirim = "DITERIMA"
            GROUP BY po.no_nota
        ');
        return datatables($data)->toJson();
    }

    public function store($request)
    {
        //
        $queries = DB::select("SELECT o.*, po.user_id
            FROM purchase_orders as po
            LEFT JOIN orders as o
                ON o.po_id = po.id
            WHERE po.no_nota = '$request->prop' AND status = 'BELUM DISETUJUI'
        ");
        try {
            DB::beginTransaction();
            $nominal_total = 0;
            foreach ($queries as $key => $query) {
                // dd($query);
                $nominal_total_tmp = $query->qty * $query->harga_order;
                $nominal_total += $nominal_total_tmp;
                $satuan = DB::table('tbl_barang')
                    ->where('barang_id', $query->product_id)
                    ->first('satuan_id');
                    // dd($satuan);
                DB::table('tbl_log_stok')
                    ->insert([
                        'id_barang' => $query->product_id, 
                        'id_satuan' => $satuan->satuan_id, 
                        'tanggal' => Carbon::now()->toDateString(),
                        'unit_masuk' => 0,
                        'unit_keluar' => $query->qty,
                        'status' => 'J1',
                        'id_user' => $query->user_id
                    ]);
            }
            DB::table('orders')
                ->where('po_id', $query->po_id)
                ->where('status', 'BELUM DISETUJUI')
                ->update([
                    'status' => 'DISETUJUI SEMUA'
                ]);
            DB::table('tagihans')
                ->insert([
                    'po_id'=>$query->po_id, 
                    'nominal_total' => $nominal_total, 
                    'status' => 'BELUM DIBAYAR', 
                    'kirim' => 'BELUM'
                ]);
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            return response($th, 500);
        }
    }

    public function show($no_nota)
    {
        //
        $no_nota = str_replace(' ','=',$no_nota);
        // dd($no_nota);
        $order = DB::select('SELECT o.*, po.no_nota
            FROM orders AS o
            LEFT JOIN purchase_orders AS po
                ON o.po_id = po.id
            WHERE po.no_nota = "'.$no_nota.'"
                AND o.status = "BELUM DISETUJUI"
        ');
        $no = 0;
        $data = [];
        foreach ($order as $key => $value) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $value->nama_barang;
            $row[] = $value->harga_order;
            $row[] = $value->qty;
            $row[] = $value->harga_order * $value->qty;
            $data[] = $row;
        }
        $output = array("data" => $data);
        return response()->json($output);
    }

    public function incomingOrder()
    {
        //
        try {
            $status = 'BELUM DISETUJUI';
            $data = DB::select('SELECT po.*, u.name, gu.group_name
                from `purchase_orders` as po
                left join `users` as u
                    on u.id = po.user_id
                left join `group_users` as gu
                    on u.id_group = gu.id
                where exists (select * 
                    from `orders` as o
                    where `po`.`id` = `o`.`po_id` 
                        and `tagihan_id` is null 
                        and `status` = "BELUM DISETUJUI"
                        and `status` not in ("AWAL PESAN", "PENDING", "DISETUJUI SEBAGIAN", "DISETUJUI SEMUA")
                )
            ');
            return $data;

        } catch (\Throwable $th) {
            dd($th);
            return response([
                'status' => 500,
                'data' => $th
            ]);
        }
    }

    // ================================ EXPORT IMPORT ITEM DATA ===============================
    public function import_excel($request)
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
                $nama_file = rand().$file->getClientOriginalName();
                $array = Excel::toArray(new BarangImport, $file);
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
                        'barangnama_asli' => $value['barangnama_asli']
                    );
                    $harga_group[] = array(
                        'id_barang' => $num,
                        '2' => $value['harga_level_1'],
                        '3' => $value['harga_level_2'],
                        '4' => $value['harga_level_3'],
                        '5' => $value['harga_level_4']
                    );

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
                    $data_grpup_harga = collect($set_harga);
                    $chunks_harga = $data_grpup_harga->chunk(1000);
                    foreach ($chunks_harga as $chunks_hargas){
                        $insert_tbl_harga_group = DB::table('harga_produk_group')->insert($chunks_hargas->toArray());
                    }
                }
                if ($insert_tbl_harga_group == 'true') {
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
    // ============================================================================

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
            $data[] = $row;

        }
        $output = array("data" => $data);
        return $output;
    }

    public function list_harga($id)
    {
        $harga=DB::select("SELECT tb.barang_id, tb.barang_nama, hgp.harga_group, gu.group_name
            FROM tbl_barang as tb LEFT JOIN harga_produk_group as hgp
            ON tb.barang_id = hgp.id_product
            RIGHT JOIN group_users as gu
            ON hgp.id_group = gu.id
            WHERE tb.barang_id = $id
        ");
        return json_encode((array)$harga);
    }

    public function allItemStock()
    {
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
        return $output;
    }

    // ==================================== EXPORT IMPORT STOK ===============================================
    public function importStock($request)
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
            DB::table('tbl_log_stok')->delete();
            DB::statement("ALTER TABLE tbl_log_stok AUTO_INCREMENT =  1");
            $insert = DB::table('tbl_log_stok')->insert($toSubmit);
            DB::commit();
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
    // =======================================================================================

    public function truncateStock()
    {
        try {
            DB::table('tbl_log_stok')->delete();
            DB::statement("ALTER TABLE tbl_log_stok AUTO_INCREMENT =  1");
        } catch (\Throwable $th) {
            return response($th, 500);
        }
    }
}
