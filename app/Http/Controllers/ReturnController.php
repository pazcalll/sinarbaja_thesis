<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function getData(Request $request) {

        $data = Order::whereIn('tagihan_id', $request->data)
            ->where('status', 'RETURN')->get();
        return response($data, 200);
    }
    public function reasonsReturn(Request $request) {
        $data = [
            'po_id' => $request['po_id'],
            'tagihan_id' => $request['tagihan_id'],
            'tanggal_return' => $request['created_at'],
            'nama_produk' => $request['nama_produk'],
            'qty' => $request['qty'],
            'alasan' => ''
        ];
    }
    public function returns() {
        return view('dashboard.approval-return');
    }
}
