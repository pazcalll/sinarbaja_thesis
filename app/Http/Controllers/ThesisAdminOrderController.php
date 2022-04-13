<?php

namespace App\Http\Controllers;

use App\Helpers\ConditionalHelper;
use App\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ThesisAdminOrderController extends Controller
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

    public function sendPage()
    {
        return view('adminThesis.orderSend');
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

    public function approvalUrl(Request $request)
    {
        $po_id = $request->post('po_id');
        $bukti_tf = DB::select('SELECT *
            FROM payments
            WHERE po_id = '.$po_id.'
        ');
        return response(collect($bukti_tf)->first()->bukti_tf, 200);
    }

    public function approvalBill(Request $request)
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

    public function sendOrder(Request $request)
    {
        try {
            DB::beginTransaction();
            DB::table('tagihans')
                ->where('po_id', $request->po_id)
                ->where('status', $request->status_pembayaran)
                ->update(['kirim' => "PERJALANAN"]);
            DB::commit();
            return response('Success', 200);
        } catch (\Throwable $th) {
            return response($th, 500);
        }
    }

    public function sendingPage()
    {
        return view('adminThesis.orderSending');
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

    public function completedPage()
    {
        return view('adminThesis.orderCompleted');
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
}
