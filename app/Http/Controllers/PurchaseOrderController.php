<?php

namespace App\Http\Controllers;

use App\LogStock;
use App\Order;
use App\Payment;
use App\Product;
use App\PurchaseOrder;
use App\Tracking;
use App\Tagihan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.purchase-order');
    }

    public function perintahKirim()
    {
        return view('dashboard.perintah-kirim');
    }

    public function tagihan()
    {
        return view('dashboard.histori-tagihan');
    }

    public function pesananProses()
    {
        return view('dashboard.pesanan-proses');
    }

    public function pesananSelesai() {
        return view('dashboard.pesanan-selesai');
    }

    public function dataTagihan(){
        return response([
            'status'  => 'message',
            'message' => 'Berhasil load data',
            'data'    => Payment::with('po.user', 'po.tagihans.payment')->get()
            // PurchaseOrder::whereHas('orders', function($table)  {
            //     return $table->where('tagihan_id','!=',null);
            // })
            // ->with('orders.product.stock', 'user', 'tagihans.payment')
            // ->get()
        ], 200);
    }

    public function detailTagihan(Request $request){
        return response([
            'status'  => 'message',
            'message' => 'Berhasil load data',
            'data'    => Order::whereHas('po', function($table) use ($request)  {
                return $table   ->where('po_id', $request->po_id)
                                ->whereIn('status', ['DISETUJUI SEBAGIAN','DISETUJUI SEMUA']);
            })->with('po.user','product')
            ->get()
        ], 200);
    }


    public function pending() {
        return view('dashboard.pending-order');
    }

    public function newPurchaseOrder() {
        $status = 'BELUM DISETUJUI';

        $data = PurchaseOrder::with(['orders.product.stock', 'orders.product.harga_group', 'user.group_user', 'orders'=> function($db) {
                return $db->where('status', 'BELUM DISETUJUI');
            }])
            ->whereHas('orders', function($table) use ($status){
                return $table->where('tagihan_id', null)
                ->where('status', $status)
                ->whereNotIn('status', ['AWAL PESAN', 'PENDING', 'DISETUJUI SEBAGIAN', 'DISETUJUI SEMUA']);
            })
            // ->whereHas('orders', function($table) use ($status){
            //     return $table->where('status', $status)->whereNotIn('status', ['AWAL PESAN']);
            // })
            // ->doesnthave('tagihans')
            ->get();
            $num = 0;
            foreach ($data as $key => $value) {
              $jenis = substr($value->no_nota,0,3);
              if ($jenis == 'COD') {
                $jenis = 'COD';
              }
              else {
                $jenis = 'Transfer';
              }
              $data[$num]['jenis_pembayaran'] = $jenis;
              $num++;
              // dd($value->no_nota);
            }
            // dd($num);
        return response([
            'status'  => 'message',
            'message' => 'Berhasil load data',
            'data'    => $data
        ], 200);

        // $maxOrder = Order::selectRaw("MAX(id) AS id, product_id")->groupByRaw('product_id')->get()->pluck('id');

        // $data = PurchaseOrder::with(['orders.product.stock', 'user', 'orders' => function($db) use ($query,$maxOrder) {
        //         return $db->whereIn('id',$maxOrder)->where('status', $query);
        //     }])
        //     ->whereHas('orders', function($table) use ($query,$maxOrder) {
        //         return $table->whereIn('id',$maxOrder)->where('status', $query);
        //     })
        //     ->get();
        // return $this->loadData();
    }

    public function pendingOrder() {
        return $this->loadData('PENDING');
    }

    public function sentOrder() {

        $data = PurchaseOrder::with(['user', 'tagihans.orders.product', 'tagihans' => function($table)  {
                return $table->whereNull('driver_id');
            }])
            ->whereHas('tagihans', function($table)  {
                return $table->whereNull('driver_id');
            })
            ->get();
        // dd($data);
        return response([
            'status'  => 'message',
            'message' => 'Berhasil load data',
            'data'    => $data
        ], 200);
    }

    public function infoGudang($order_id,$tagihan_id) {
        $tagihan = DB::table('tagihans')
        ->where('id',$tagihan_id)
        ->first();
        $get_id = json_decode($tagihan->id_gudang);
        for ($i=0; $i < count($get_id); $i++) {
          if ($get_id[$i][0] == $order_id) {
            $gudang = DB::table('ref_gudang')
            ->where('nama','LIKE','%'.$get_id[$i][1].'%')->first();
            $arr_gudang[] = $gudang->id;
            $stok[] = array($gudang->id,$get_id[$i][2]);
          }
        }
        $query = DB::select("SELECT g.id AS id, g.nama AS Gudang,SUM(s.unit_masuk - s.unit_keluar)AS Stok
            FROM ref_gudang AS g
            LEFT JOIN tbl_log_stok AS s
            ON s.id_ref_gudang = g.id
            WHERE id IN (" . implode(',', $arr_gudang) . ")
            GROUP BY g.id");
            $num = 0;
            foreach ($query as $key => $value) {
                $stok_output = $stok[$num][1];
              $data[] = array(
                'id' => $value->id,
                'Gudang' => $value->Gudang,
                'Stok' => $stok_output
              );
              $num++;
            }
        return response([
            'status'  => 'message',
            'message' => 'Berhasil load data',
            'data'    => $data
        ], 200);
    }

    public function pilihGudang() {

        $pilGud = DB::select("SELECT s.id_ref_gudang AS id, g.nama AS gudang
            FROM ref_gudang AS g
            LEFT JOIN tbl_log_stok AS s
            ON s.id_ref_gudang = g.id
            WHERE s.id_ref_gudang = g.id
            GROUP BY g.id
            ");
        // dd($query);

        return response([
            'status'  => 'message',
            'message' => 'Berhasil load data',
            'data'    => $pilGud
        ], 200);
    }

    public function selesaiPesanan() {
        $data = PurchaseOrder::with(['orders.tagihan.tracking_newest','tagihans.tracking_newest','user' => function($db){
            return $db->with('group_user');
        }, 'orders' => function($gd){
            return $gd->whereIn('status', ['DISETUJUI SEBAGIAN','DISETUJUI SEMUA']);
        },])->whereHas('tagihans.tracking_newest', function($table) {
            return $table->whereIn('status', ['ARRIVED', 'ARRIVED WITH RETURN']);
        })->whereNotNull('jatuh_tempo')->orderBy('created_at', 'desc')->get();

        // dd($data);
        return response([
            'status'  => 'message',
            'message' => 'Berhasil load data',
            'data'    => $data
        ], 200);


    }

    public function riwayat() {
        $data = PurchaseOrder::with(['orders.tagihan' => function($db) {
            return $db->whereIn('status', ['DISETUJUI SEBAGIAN', 'DISETUJUI SEMUA']);
        }, 'tagihans.tracking_newest', 'user' => function($db){
            return $db->with('group_user');
        },])->whereHas('orders', function($table) {
            return $table->whereIn('status', ['DISETUJUI SEBAGIAN', 'DISETUJUI SEMUA']);
        })
        ->get();

        // dd($data);
        return response([
            'status'  => 'message',
            'message' => 'Berhasil load data',
            'data'    => $data
        ], 200);
    }

    private function loadData($query)
    {
        $data = PurchaseOrder::with(['orders.product.stock', 'user', 'orders' => function($db) use ($query) {
                return $db->where('status', $query);
            }])
            ->whereHas('orders', function($table) use ($query) {
                return $table->where('status', $query);
            })
            ->get();

        return response([
            'status'  => 'message',
            'message' => 'Berhasil load data',
            'data'    => $data
        ], 200);
    }

    public function getPesananProses()
    {
        $data = PurchaseOrder::with(['user', 'tagihans.orders', 'tagihans.orders' => function($db) {
                        return $db->whereIn('status', ['DISETUJUI SEBAGIAN','DISETUJUI SEMUA','SENT']);
                    },'tagihans.trackings','tagihans.tracking_newest','tagihans.driver'])
                ->whereHas('tagihans', function($table)  {
                    return $table->whereNotNull('driver_id');
                })
                ->whereHas('tagihans.tracking_newest', function($table) {
                    return $table->where('status', 'SENDING');
                })
                ->get();

        return response([
            'status'  => 'message',
            'message' => 'Berhasil load data',
            'data'    => $data
        ], 200);
    }

    public function store(Request $request)
    {
        // dd(Auth::user()->id);
        // return response($request, 500);
        DB::beginTransaction();
        $cod = isset($request->cod);
        try {
            $noNota = function() use ($cod) {
                $id = base64_encode(base64_encode(base64_encode(Auth::user()->id)));
                $time = date('YmdHis');
                if ($cod == 1) {
                  return 'COD-ORD-'.$id.$time;
                }
                else {
                  return 'ORD-'.$id.$time;
                }
            };
            $purchaseOrder = PurchaseOrder::create([
                'no_nota' => $noNota() ,
                'user_id' => Auth::user()->id ]
            );
            $newOrder=null;

            $cart = DB::table('cart')->where('id_user',$request->id_user)->get();
            foreach($cart as $i => $item) {
                // return response(intval($item['harga_user'][0]['harga_user']), 400);
                $harga = DB::table('harga_produk_user')
                ->where('id_product',$item->id_barang)
                ->where('id_user',$request->id_user)
                ->first();
                $barang = DB::table('tbl_barang')
                ->where('barang_id',$item->id_barang)
                ->first();
                $newOrder = Order::create([
                    'po_id'      => $purchaseOrder->id,
                    'product_id' => $item->id_barang,
                    'qty'        => $item->jumlah,
                    'harga_order'=> $harga->harga_user,
                    'nama_barang'=> $barang->barang_nama
                ]);

                $replicate = $newOrder->replicate();
                $replicate->status = 'BELUM DISETUJUI';
                $replicate->save();
                DB::table('cart')->where('id',$item->id)->delete();
            }

            DB::commit();

            return response([
                'message' => 'Terimakasih sudah membeli product kami',
                'data' => [['input',$request->input('data')],['response',$newOrder]]
            ], 200);
        } catch(Exception $e) {
            DB::rollBack();

            return response(['message' => $e->getMessage()], 400);
        }
    }

    public function loadData_po(Request $request){
      $index = 0;
      $id = $request->id;
      $data = [];
      $qty_arr = !empty($request->qty)?$request->qty:null;
      $id_barang = !empty($request->id_barang)?$request->id_barang:[];
      $limit = is_null($request["length"]) ? 10 : $request["length"];
      $offset = is_null($request["start"]) ? 0 : $request["start"];
      $draw = $request["draw"];
      $num = 1;
      $arr_gudang = [];
      $get_count = null;
      if (count($id_barang) > 0) {
        $get = DB::table('purchase_orders AS a')
        ->where('a.id',$id)
        ->where('b.status','BELUM DISETUJUI')
        ->whereIn('b.id',$id_barang)
        ->leftJoin('orders AS b','a.id','b.po_id');
        $fetch = $get->get();
        $get = $get->get();
        $get_count = count($fetch);
        if ($get_count > 0) {
            foreach ($get as $key => $value) {
                $gudang = DB::table('tbl_log_stok AS a')
                ->select('a.*','b.*',DB::raw("SUM(a.unit_masuk) AS total_unit_masuk"),
                DB::raw("SUM(a.unit_keluar) AS total_unit_keluar"))
                ->where('a.id_barang',$value->product_id)
                ->leftJoin('ref_gudang AS b','a.id_ref_gudang','b.id')
                ->groupBy('b.id')
                ->get();
              if (!empty($qty_arr)) {
              foreach ($gudang as $val_gudang) {
                $stock_value = $val_gudang->total_unit_masuk - $val_gudang->total_unit_keluar;
                if (!empty($qty_arr)) {
                  $check_stock =  $val_gudang->unit_masuk - $qty_arr[$index];
                  if ($check_stock < 0) {
                    $check_stock_status = 'Stock Tidak Memenuhi';
                  }
                  else {
                    $check_stock_status = 'Stock Memenuhi';
                  }
                }
                $arr_gudang[] = array(
                  'id' => $value->id,
                  'id_gudang' => $val_gudang->id_ref_gudang,
                  'nama' => $val_gudang->nama,
                  'stock' =>$stock_value,
                  'status' => $check_stock_status
                );
              }
            }
            // dd($arr_gudang);
            $data[] = array(
              'num' => $num++,
              'nota' => $value->no_nota,
              'id' => $value->id,
              'nama_barang' => $value->nama_barang,
              'qty' => $value->qty,
              'harga' => $value->harga_order,
              'gudang' => $arr_gudang
            );
            $index++;
          }
        }
      }
      // dd($data);
      $recordsTotal = is_null($get_count) ? 0 : $get_count;
      $recordsFiltered = is_null($get_count) ? 0 : $get_count;
      return response()->json(compact("data","arr_gudang","draw", "recordsTotal", "recordsFiltered"));
    }
    public function sentPesanan(Request $request){

            foreach ($request->data as $key => $value) {
             $id_tagihan[] = $value['Id'];
            }
            try {
              $update_stat = DB::table('orders AS a')
              ->leftJoin('purchase_orders AS b','a.po_id','b.id')
              ->leftJoin('tagihans AS c','c.po_id','b.id')
              ->whereIn('c.id',$id_tagihan)
              ->where('a.status','DISETUJUI SEMUA')
              ->groupBy('c.id')
              ->get();
              $temp_gudang = $request->gudang_detail_unit['qty_gudang'];
              foreach ($temp_gudang as $key => $value) {
                foreach ($value as $key_val => $get_val) {
                  foreach ($get_val as $keys => $items) {
                    $gudang_nama = DB::table('ref_gudang')->select('nama','id')->where('id',$keys)->first();
                    if (!empty($items)) {
                        $gudang_aray[$key][] = array(
                          $key_val,$gudang_nama->nama,$items,$gudang_nama->id
                        );
                    }
                  }
                }
              }
              foreach ($gudang_aray as $key => $value_update) {
                foreach ($value_update as $values) {
                  $get_order = DB::table('orders AS a')
                  ->leftJoin('purchase_orders AS b','a.po_id','b.id')
                  ->leftJoin('tbl_barang AS c','c.barang_id','a.product_id')
                  ->where('a.id',$values[0])
                  ->first();
                  $set_logstok = DB::table('tbl_log_stok')
                  ->insert([
                    'id_barang' => $get_order->product_id,
                    'id_ref_gudang' => $values[3],
                    'id_satuan' => $get_order->satuan_id,
                    'tanggal' => date('Y-m-d'),
                    'unit_masuk' => 0,
                    'unit_keluar' => intVal($values[2]),
                    'status' => 'J1',
                    'id_user' => $get_order->user_id,
                    'created_at' => date('Y-m-d H:i:s')
                  ]);
                }
                  DB::table('tagihans')->where('id',$key)->update([
                    'id_gudang' => json_encode($value_update)
                  ]);
                }
            } catch (\Exception $e) {
              // dd('tes');
              return response([
                  'message' => 'Periksa unit keluar gudang',
                  'data' => []
              ], 500);
            }
            foreach($request->data as $key => $item) {

                $tagihan = Tagihan::find($item['tagihan_id']);
                $tagihan->driver_id = $request->id_driver;
                $tagihan->memo = $request->memo;
                $tagihan->save();

                $purchaseOrder = PurchaseOrder::find($item['po_id']);
                $purchaseOrder->jatuh_tempo = $request->input('jatuh_tempo');
                $purchaseOrder->save();

                $filteredPO = $purchaseOrder->with(['orders.product','orders' => function($db) use ($item){
                    $db->where('tagihan_id',$item['tagihan_id']);
                }])->whereHas('tagihans', function ($query) use ($item){
                    $query->where('id',$item['tagihan_id']);
                })->first();

                // return $filteredPO;
                Tracking::create([
                    'tagihan_id' => $item['tagihan_id'],
                    'driver_id' => $request->id_driver,
                    'id_user' => $purchaseOrder->user->id,
                    'status' => 'SENDING'
                ]);
                // foreach ($filteredPO->orders as $key1 => $value1) {
                //     // $stock = Product::find($value1->product_id)->stock;
                //     $stock->stock = $stock->stock - $value1->qty;
                //     LogStock::create([
                //         'stock_id' => $stock->id,
                //         'type' => 'OUT',
                //         'note' => "Barang dibeli oleh ". $purchaseOrder->user->group_id. ' dengan nama : '. $purchaseOrder->user->name,
                //         'before' => $stock->stock,
                //         'current' => $stock->stock - $value1->qty
                //     ]);
                //     $stock->save();
                // }
            }

        try {
            DB::beginTransaction();



            DB::commit();
            $code = 200;
        } catch(Exception $e) {
            DB::rollBack();
            $code = 400;
            return response($e, 500);
        }

        return response([
            'message' => 'Berhasil mengubah status order menjadi dikirim',
            'data' => []
        ], $code);
    }
    public function approval() {
        $payment = Payment::with(['po' => function($db){
            return $db->with('user');
        }, 'tagihan' => function($db){
            return $db->with(['orders' => function($db2){
                return $db2->with('product');
            }]);
        }])->where('bukti_tf', '!=', null)->get();
        return response($payment, 200);
    }

    public function select_gudang(Request $request) {

        // $idbarang = '303';
        $idbarang = $request->id_barang;
        $qty = $request->qty_barang;

        $gudang = DB::select("
        SELECT * FROM
            (SELECT g.id,g.nama, SUM( s.unit_masuk ) AS stok_masuk, SUM( s.unit_keluar ) AS stok_keluar
            FROM tbl_log_stok AS s
            LEFT JOIN ref_gudang AS g ON s.id_ref_gudang = g.id
            WHERE s.id_barang = '$idbarang'
            GROUP BY s.id_ref_gudang) AA
        WHERE (stok_masuk - stok_keluar) >= $qty
        ");

        if(empty($gudang)){
            $gudang = [0=>
                ['id' => '0',
                'nama' => 'STOK KOSONG']
            ];
        }
        return response($gudang);
    }
}
