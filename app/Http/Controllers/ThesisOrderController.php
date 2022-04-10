<?php

namespace App\Http\Controllers;

use App\Helpers\ConditionalHelper;
use App\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ThesisOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
            return view('adminThesis.orderIncoming')->with('order', $data);

        } catch (\Throwable $th) {
            dd($th);
            return response([
                'status' => 500,
                'data' => $th
            ]);
        }
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
        $queries = DB::select("SELECT o.*, po.user_id
            FROM purchase_orders as po
            LEFT JOIN orders as o
                ON o.po_id = po.id
            WHERE po.no_nota = '$request->prop' AND status = 'BELUM DISETUJUI'
        ");
        // dd($queries);
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
}
