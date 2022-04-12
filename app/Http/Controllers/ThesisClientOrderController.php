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

    public function orderUnaccepted()
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

    public function orderUnpaid()
    {
        $pesanan = DB::select('SELECT po.*, 
                SUM(o.qty * o.harga_order) as total_harga, 
                CONCAT( "[", GROUP_CONCAT(JSON_OBJECT("nama_barang", o.nama_barang, "qty", o.qty, "harga", o.harga_order)), "]") as barang,
                t.status as status_pembayaran,
                t.kirim
            FROM purchase_orders AS po
            LEFT JOIN orders as o 
                ON o.po_id = po.id
            LEFT JOIN tagihans as t
                ON t.po_id = po.id
            WHERE o.status = "DISETUJUI SEMUA" AND t.status = "BELUM DIBAYAR"
            GROUP BY po.no_nota
        ');
        // dd(json_encode(explode('"', $pesanan[0]->barang)));
        return datatables($pesanan)->toJson();
    }

    public function orderPaid()
    {
        $pesanan = DB::select('SELECT po.*, 
                SUM(o.qty * o.harga_order) as total_harga, 
                CONCAT( "[", GROUP_CONCAT(JSON_OBJECT("nama_barang", o.nama_barang, "qty", o.qty, "harga", o.harga_order)), "]") as barang,
                t.status as status_pembayaran,
                t.kirim
            FROM purchase_orders AS po
            LEFT JOIN orders as o 
                ON o.po_id = po.id
            LEFT JOIN tagihans as t
                ON t.po_id = po.id
            WHERE o.status = "DISETUJUI SEMUA" 
                AND t.status = "LUNAS"
                AND t.kirim != "DITERIMA"
            GROUP BY po.no_nota
        ');
        // dd(json_encode(explode('"', $pesanan[0]->barang)));
        return datatables($pesanan)->toJson();
    }

    public function uploadTransfer(Request $request) {
        // nilai-nilai untuk kolom 'valid' di tabel Payment sebagai berikut:
        // 0 = memiliki arti bukti transfer ditolak
        // 1 = memiliki arti bukti transfer diterima sebagian
        // 2 = memiliki arti bukti transfer diterima semua
        // 9 = memiliki arti bukti transfer belum diproses admin

        try {
            //code...
            // dd($request->all());
            DB::beginTransaction();
            $validator = \Validator::make($request->all(), [
                // 'nominal_terkirim' => 'required',
                'jumlahBayarInput' => 'required|numeric|min:100',
                'inputBukti' => 'required|image'
            ],[
                // 'nominal_terkirim.required' => 'Jumlah yang dibayar tidak boleh kosong',
                'jumlahBayarInput.required' => 'Jumlah yang dibayar tidak boleh kosong',
                'jumlahBayarInput.min' => 'Pembayaran tidak boleh kurang dari 100 Rupiah',
                'inputBukti.required' => 'image required',
                'inputBukti.image' => 'file must be an image'
            ]);
            if ($validator->fails()) {
                return response(['message'=>$validator->errors()->toArray()], 400);
            }else{
                $filename =time().'_'.$request->file('inputBukti')->getClientOriginalName();
                $request->file('inputBukti')->storeAs('public/tagihan', $filename);
                $po_id = $request['po_id_pembayaran'];
                $tagihan_id = $request['id'];
                $nominal_bayar = $request['jumlahBayarInput'];

                // if($request['nominal_terkirim'] < $nominal_bayar) $nominal_bayar = $request['nominal_terkirim'];
                if($request['jumlahBayarInput'] < $nominal_bayar) $nominal_bayar = $request['jumlahBayarInput'];

                $data = [
                    'po_id' => $po_id,
                    'tagihan_id' => $tagihan_id,
                    'valid' => 9,
                    'nominal_bayar' => $nominal_bayar,
                    'bukti_tf' => 'public/tagihan/'.$filename
                ];
                DB::table('payments')->insert($data);
                DB::table('tagihans')->where('po_id', $po_id)->update(['status' => 'LUNAS']);
                DB::commit();
                return response([
                    'message' => 'Record created',
                    'status' => 'success'
                ], 200);
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response($th, 500);
        }
    }

    public function confirmOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::table('tagihans')
                ->where('po_id', $request->po_id_confirm)
                ->update(['kirim' => 'DITERIMA']);
            DB::commit();
            return response("confirm order success", 200);
        } catch (\Throwable $th) {
            return response($th, 500);
        }
    }
}
