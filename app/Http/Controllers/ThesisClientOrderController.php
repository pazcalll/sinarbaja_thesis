<?php

namespace App\Http\Controllers;

use App\GroupUser;
use App\PurchaseOrder;
use App\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ThesisClientOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd($pesanan);
        
        return view('clientThesis.order');
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

    public function pesananBelumDisetujui()
    {
        $pesanan = DB::select('SELECT po.*, SUM(o.qty * o.harga_order) as total_harga, CONCAT( "[", GROUP_CONCAT(JSON_OBJECT("nama_barang", o.nama_barang, "qty", o.qty, "harga", o.harga_order)), "]") as barang
            FROM purchase_orders AS po
            LEFT JOIN orders as o 
                ON o.po_id = po.id
            WHERE NOT EXISTS(
                SELECT * 
                FROM tagihans as t
                WHERE po.id = t.po_id
            ) AND o.status = "BELUM DISETUJUI"
            GROUP BY po.no_nota
        ');
        // dd(json_encode(explode('"', $pesanan[0]->barang)));
        return datatables($pesanan)->toJson();
    }
}
