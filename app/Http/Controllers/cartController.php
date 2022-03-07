<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB,Response;
use Illuminate\Support\Facades\Auth;

class cartController extends Controller
{
    public function cartProcess(Request $request){
      // dd($request->all());
      DB::beginTransaction();
      try {
        $userCart = DB::table('cart')->select('id', 'id_barang', 'jumlah')->where('id_user', intval($request->post('id_user')))->where('id_barang', intval($request->post('id_barang')));
        if ($userCart->first() != null) {
          DB::table('cart')
            ->where('id', $userCart->first()->id)
            ->update(['jumlah' => $userCart->first()->jumlah + intval($request->post('jumlah'))]);
        }else {
          DB::table('cart')->insert([
            'id_barang' => $request->id_barang,
            'id_user' => $request->id_user,
            'jumlah' => $request->jumlah
          ]);
        }
        DB::commit();
        return response()->json('berhasil');
      } catch (\Throwable $th) {
        DB::rollback();
        return response($th, 500);
      }
    }
    public function cartData(Request $request){
      $output = [];
      $cart = DB::table('cart AS a')->where('a.id_user',$request->id_user)
      ->leftJoin('tbl_barang AS b','b.barang_id','a.id_barang')
      ->get();
      $total = DB::table('cart')->where('id_user',$request->id_user)->get();
      $sum = DB::table('cart AS a')
      ->select('harga_user','jumlah')
      ->where('a.id_user',$request->id_user)
      ->where('c.id_user',$request->id_user)
      ->leftJoin('tbl_barang AS b','b.barang_id','a.id_barang')
      ->leftJoin('harga_produk_user AS c','c.id_product','a.id_barang')
      ->get();
      if (count($sum)>0) {
        foreach ($sum as $val) {
          $harga[] = $val->harga_user*$val->jumlah;
        }
        $curr = 'Rp. '.number_format(array_sum($harga), 0, '', '.');
        $data['total_harga'] = $curr;
      }
      else {
        $data['total_harga'] = 'Rp. '.number_format(0, 0, '', '.');
      }
      $data['get_total'] = count($total);
      if (count($cart) > 0) {
        foreach ($cart as $value) {
          $output[] = '
          <div class="col-12">
            <div class="card border-bottom shadow-sm p-3 mb-5 bg-white rounded">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <div class="text-info" ><h5>'.$value->barang_nama.'</h5></div>
                    <div><h6>'.$value->barang_kode.'</h6></div>
                  </div>
                  <div class="col">
                    <div><h6>'.$value->jumlah.'</h6></div>
                  </div>
                </div>
              </div>
            </div>
          </div><hr>';
        }
      }
      $data['cart_data'] = $output;
      echo json_encode($data);
    }
    public function json_cartAll(Request $request){
      $data = [];
      $get = DB::table('cart AS a')
      ->select('a.id AS id','d.barang_nama','b.name AS name','a.created_at AS tanggal','a.jumlah AS qty','c.harga_user AS harga')
      ->where('a.id_user',$request->user)
      ->where('c.id_user',$request->user)
      ->leftJoin('users AS b','b.id','a.id_user')
      ->leftJoin('harga_produk_user AS c','c.id_product','a.id_barang')
      ->leftJoin('tbl_barang AS d','a.id_barang','d.barang_id')
      ->get();
      if (count($get) > 0) {
        foreach ($get as $key => $value) {
          $total_harga = $value->qty*$value->harga;
          $data[] = array(
            'id' => $value->id,
            'name' => $value->barang_nama,
            'tanggal' => date('d-m-Y',strtotime($value->tanggal)),
            'qty' => $value->qty,
            'total' => 'Rp. '.number_format($total_harga, 0, '', '.')
          );
        }
      }
      return response()->json(compact("data"));
    }
    public function delete_cart(Request $request){
      DB::table('cart')->where('id',$request->cart_id)->delete();
      $get = DB::table('cart')->where('id_user',$request->users)->get();
      $count = count($get);
      return response()->json($count);
    }
}
