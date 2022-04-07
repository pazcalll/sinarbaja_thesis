<?php

namespace App\Http\Controllers;

use App\Helpers\ConditionalHelper;
use App\PurchaseOrder;
use Illuminate\Http\Request;
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
