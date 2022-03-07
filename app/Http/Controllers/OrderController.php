<?php

namespace App\Http\Controllers;

use App\GroupUser;
use App\Helper;
use App\Order;
use App\Tracking;
use App\PurchaseOrder;
use App\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Intervention\Image\ImageManagerStatic as Image;
use DB;
use Redirect;

class OrderController extends Controller
{

    public function index()
    {
        $order = Order::all();
        // dd($order);
        $pesananSelesai = PurchaseOrder::with(['orders.tagihan.tracking_newest','tagihans.tracking_newest','user' => function($db){
            return $db->with('group_user');
        }, 'orders' => function($gd){
            return $gd->whereIn('status', ['DISETUJUI SEBAGIAN','DISETUJUI SEMUA']);
        },])->whereHas('tagihans.tracking_newest', function($table) {
            return $table->whereIn('status', ['ARRIVED', 'ARRIVED WITH RETURN']);
        })->whereNotNull('jatuh_tempo')->orderBy('created_at', 'desc')
        ->where('user_id', Auth::user()->id)->get();
        // dd($pesananSelesai);
        $riwayat = PurchaseOrder::where('user_id', Auth::user()->id)->with(['user',
         'orders.barang'
         ])
         ->get();
        // dd($riwayat);
        $status = Tagihan::where('status', ['DIBAYAR SEBAGIAN', 'LUNAS'])->get();
        $user = Auth::user();
        $group_name = GroupUser::where('id', $user->id_group)->get('group_name')->first();
        // if($group_name == 'DRIVER' && $status != ['DIBAYAR SEBAGIAN'. 'LUNAS']){
        // dd($riwayat);
        if($group_name == 'DRIVER'){
            return view('driver.order', compact('riwayat'));
        }else {
            return view('client.order', compact('riwayat', 'pesananSelesai'));
        }
    }

    public function viewRiwayat() {
        return view('dashboard.riwayat');
    }

    public function dataRiwayat() {
        $data = PurchaseOrder::orderBy('created_at', 'desc')
            ->with(['orders.product', 'user'])
            ->get();
        return datatables($data)->toJson();
    }

    public function proses($id) {
        $user = Auth::user();
        if($user->id_group == 3){
            $data = Tagihan::with(['po.user', 'orders' => function($db) {
                                return $db->where('status', 'BELUM DISETUJUI');
                            },'orders.barang','trackings','tracking_newest','order_last_status'])
                            ->where('driver_id',$user->id)
                            ->whereIn('status', '')
                            ->get();
            return datatables($data)->toJson();

        }else{
            $SQLtotalUangPesanan = Order::get('harga_order');
            $data = PurchaseOrder::with(['user', 'tagihans.orders.barang', 'tagihans.orders' => function($db) {
                            return $db->where('status', 'BELUM DISETUJUI');
                        },'tagihans.trackings','tagihans.tracking_newest','tagihans.driver','order_last_status','order_awal.product'])
                        // ->whereHas('tagihans', function($db)
                        // {
                        //     $db->whereHas('orders', function($db){
                        //         $db->where('status', 'BELUM DISETUJUI');
                        //     });
                        // })
                        ->where('jatuh_tempo', null)
                        ->when($user, function ($db) use ($user){
                            if($user->id_group == 3){
                                return $db->whereHas('tagihans', function($table) use ($user) {
                                    return $table->where('driver_id',$user->id);
                                });
                            }else{
                                return $db->whereHas('user', function($table) use ($user) {
                                    return $table->where('user_id',$user->id);
                                });
                            }
                        })
                    ->get();
            $data_belum_disetujui = [];
            foreach ($data->toArray() as $key => $value) {
                if (count($value["tagihans"]) == 0) {
                    $data_belum_disetujui[] = $value;
                }
                // dd($value);
                // $data_belum_disetujui[] =
            }
            return datatables($data_belum_disetujui)->toJson();
        }

    }

    public function pesananProses($id)
    {
        $user = Auth::user();
        $group_name = GroupUser::where('id', $user->id_group)->get('group_name')->first();
        // dd($group_name['group_name']);
        // if($group_name['group_name'] == 'AGENT' || $group_name['group_name'] == 'CUSTOMER'){
        if($group_name['group_name'] != 'DRIVER' || $group_name['group_name'] != 'ADMINISTRATOR'){
            $data = PurchaseOrder::with(['user', 'tagihans.orders.product' => function($db){
                $db->with('stock');
            },
            'tagihans.orders' => function($db) {
                // return $db->whereIn('status', ['DISETUJUI SEBAGIAN','DISETUJUI SEMUA','SENT']);
            },
            'tagihans.trackings','tagihans.tracking_newest','tagihans.driver','order_last_status','order_awal.product', 'tagihans'])

            ->when($user, function ($db) use ($user){
                if($user->id_group == 3){
                    return $db->whereHas('tagihans', function($table) use ($user) {
                        return $table->where('driver_id',$user->id);
                    });
                }else{
                    return $db->whereHas('user', function($table) use ($user) {
                        return $table->where('user_id',$user->id);
                    })->whereHas('tagihans');
                }
            })
            ->get();

            // dd($data);
            return response($data, 200);
        }
    }

    public function tertunda($id) {
        $data = Order::where('status', 'PENDING')->whereHas('po', function($query) use ($id) {
                return $query->where('user_id', $id);
            })
            ->with(['product', 'po']);
        return datatables($data)->toJson();
    }

    public function return($id) {
        $data = Order::where('status', 'RETURN')->whereHas('po', function($query) use ($id) {
            return $query->where('user_id', $id);
        });
        return datatables($data)->toJson();
    }

    public function storeReturn(Request $request) {
        $orderIds = $request->input('returnIds') ?? [];
        $purchaseOrder = PurchaseOrder::find($request->input('id'));
        $purchaseOrder->orders->each(function($item, $key) use ($orderIds, $request) {
            foreach ($orderIds as $i => $it) {
                if ($item->id == $it) {
                    $replicate = $item->replicate();
                    $replicate->qty = $request->input($it);
                    $replicate->status = 'RETURN';
                    $replicate->save();

                    $item->status = 'DONE';
                    $item->save();

                    return;
                }
            }

            if ($item->status != 'PENDING' || $item->status != 'RETURN') {
                $item->status = 'DONE';
                $item->save();
            }
        });

        return response(['data' => 'success'], 200);
    }

    public function storePickup(Request $request) {
        // return $request->all();
        $orderIds = $request->input('id_order') ?? [];
        $groupName = PurchaseOrder::with(['user'=>function($db){
            return $db->with(['group_user']);
        }])->where('id', $request->po_id)->first();
        // dd($groupName->user->group_user->group_name);
        Tracking::create([
            'tagihan_id' => $request->id,
            'driver_id' => Auth::user()->id,
            'target' => $groupName->user->group_user->group_name,
            'status' => 'SENDING'
        ]);
        Tracking::where('target', null)->delete();
        return response(['data' => 'success', 'content' => $groupName], 200);
    }

    public function storeArrive(Request $request) {
        // return $request->all();
        // $purchaseOrder = PurchaseOrder::find($request->input('id'));
        $cek_return = false;
        $orderIds = $request->input('id_order') ?? [];

        $order = Order::whereIn('id',$orderIds)->get();

        foreach ($order as $key => $value) {
            $newQty = $value->qty - $request->input($value->id);

            if ($newQty > 0) {
                $return = $value->replicate();
                $return->status = 'RETURN';
                $return->qty = $request->input($value->id);
                $return->created_at = date('Y-m-d H:i:s');
                $return->save();
                $cek_return = true;
            } else {
            }
        }

        if ($cek_return == true) {
            $status = 'ARRIVED WITH RETURN';
        } else {
            $status = 'ARRIVED';
        }
        // return $status;

        Tracking::create([
            'tagihan_id' => $request->id,
            'driver_id' => Auth::user()->id,
            'target' => $request->target,
            'status' => $status
        ]);

        return response(['data' => 'success'], 200);
    }

    public function storeConfirm(Request $request) {
        // return $request->all();
        $orderIds = $request->input('id_order') ?? [];
        $purchaseOrder = PurchaseOrder::find($request->input('id'));

        $order = Order::whereIn('id',$orderIds)->each(function($item, $key) use ($orderIds, $request) {
            $item->qty = $request->input($item->id);
            $item->status = 'DONE';
            $item->save();
        });

        return response(['data' => 'success'], 200);
    }

    public function riwayat($id) {
        $data = PurchaseOrder::where('user_id', $id)->whereHas('orders', function($db) {
            return $db->where('status', 'DONE');
        });
        return datatables($data)->toJson();
    }

    public function store(Request $request)
    {
        //
    }

    public function show()
    {
        return view('dashboard.pdf_pesanan');
    }

    public function edit(Order $order)
    {
        //
    }

    public function update(Request $request)
    {
        try {
            $returns = $request->post('data');
            $po_id = 0;
            $cart = array();
            $order_ids = array();
            foreach ($returns as $returns => $item) {
                // return response($item, 200);
                # code...
                // array_push($product_ids, $item['product_id']);
                $data = Order::find($item['id']);
                $po_id = $item['po_id'];
                array_push($order_ids, $item['id']);
                // $data->qty = $item['qty'];
                // $data->qty = $data->qty - $item['qty'];
                $qty = $item['qty'];
                $newQty = $data->qty - $item['qty'];
                $status = '';
                if ($newQty > 0) {
                    # code...
                    $replicate = $data->replicate();
                    $replicate->status = 'PENDING';
                    $replicate->qty = $newQty;
                    $replicate->created_at = date('Y-m-d H:i:s');
                    $replicate->save();

                    $status = 'DISETUJUI SEBAGIAN';
                }elseif ($newQty <= 0) {
                    $qty = $data->qty;
                    $status = 'DISETUJUI SEMUA';
                }
                $data->status = $status;
                $data->qty = $qty;
                array_push($cart, $data);
                $data->save();
            }

            $partialOrder = Order::wherein('status', ['DISETUJUI SEBAGIAN', 'DISETUJUI SEMUA'])->where('po_id', $po_id)->whereIn('orders.id', $order_ids);
            // return response($partialOrder->get(), 200);
            $totalPartialApprove = $partialOrder->leftjoin('products as pr', 'pr.id', '=', 'orders.product_id')
                                                ->selectRaw('sum( pr.harga * qty) as total')
                                                ->first()->total;
            $partialData = [
                'po_id' => $po_id,
                'nominal_total' => $totalPartialApprove,
                'no_tagihan' => 'TAG-'.base64_encode(base64_encode(Auth::user()->id)).date('YmdHis')
            ];

            $partialDataTagihan = Tagihan::create($partialData);
   			$partialOrder->update([
                'tagihan_id' => $partialDataTagihan->id
            ]);
			// 	echo $partialOrder->get();

			// 	foreach ($partialApprove as $item) {
			// 		foreach ($awalPesan as $awalan) {
			// 			if ($awalan->product_id == $item->product_id) {
			// 				if ($awalan->qty == $item->qty) {
			// 					$item->status = 'DISETUJUI SEMUA';
			// 					$item->save();
			// 				}
			// 			}
			// 		}
			// 	}
			// }

            // $data->save();

            DB::commit();
            $code = 200;
        } catch(Exception $e) {
            DB::rollBack();
            $code = 400;
        }

        return response(['data' => $request->post('data')], 200);
    }

    // change data status to pending if the checkbox in the admin page is unchecked
    function storeToPending(Request $request){
        $data = $request->post('data');
        // dd($data);
        foreach ($data as $array => $value) {
            $order = Order::find($value['id']);
            $order->status = 'PENDING';
            // dd($order);
            $order->save();
        }
        // $po_id = $item['po_id'];
    }

    public function historiReturn() {
        return view('dashboard.histori-return');
    }

    public function returnHistori() {
        return response([
            'status'  => 'message',
            'message' => 'Berhasil load data',
            'data'    => Order::whereHas('po', function ($table) {
                return $table->where('status', 'RETURN');
            })
            ->with('po.user', 'product')
            ->get()
        ], 200);
    }

    public function pesananDiterima($id_tagihan) {

        Tracking::where('tagihan_id', $id_tagihan)->update([
            'status' => 'ARRIVED'
        ]);

        return back()->with('success', 'Pesanan Diterima !');
    }
}
