<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class Customer extends User
{
    protected $po = [];
    protected $cart = [];
    public function __construct()
    {
        parent::__construct(Auth::user()->toArray());
        $query_po = DB::table('purchase_orders')
            ->where('user_id', Auth::user()->id)
            ->get();
        foreach ($query_po as $key => $value) {
            $this->po[] = new PurchaseOrders(
                $value->id,
                $value->no_nota,
                $value->user_id,
                $value->created_at
            );
        }
        
        $query_cart = DB::table('cart')
            ->where('id_user', $this->id)
            ->get();
        foreach ($query_cart as $key => $value) {
            $barang = DB::table('tbl_barang as tb')
                ->leftJoin('harga_produk_user as hpu', 'hpu.id_product', '=', 'tb.barang_id')
                ->leftJoin('users as u', 'u.id_group', '=', 'hpu.id_group')
                ->where('barang_id', $value->id_barang)
                ->where('id_user', $value->id_user)
                ->select('tb.*', 'hpu.harga_user')
                ->first();
            $barangObj = new Barang(
                $barang->barang_id,
                $barang->satuan_id,
                $barang->barang_kode,
                $barang->barang_nama,
                $barang->barang_alias,
                $barang->barangnama_asli,
                $barang->harga_user
            );
            $this->cart[] = new Cart(
                $value->id,
                $value->jumlah,
                $barangObj
            );
        }
    }

    public function getThis()
    {
        $customer = new stdClass();
        $customer->po = $this->po;
        $customer->cart = $this->cart;
        $customer->name = $this->name;
        $customer->id = $this->id;
        $customer->address = $this->address;
        $customer->no_handphone = $this->no_handphone;
        $customer->email = $this->email;
        return $customer;
    }
    
    public function customerCart(){
        $cart = $this->cart;
        $total_harga = 0;
        $data['get_total'] = count($cart);
        $output = [];
        foreach ($cart as $key => $value) {
            $thisValue = $value->getThis();
            $total_harga += ($thisValue->barang->harga_user * $thisValue->jumlah);
            $output[] = '
            <div class="col-12">
                <div class="card border-bottom shadow-sm p-3 mb-5 bg-white rounded">
                <div class="card-body">
                    <div class="row">
                    <div class="col">
                        <div class="text-info" ><h5>'.$thisValue->barang->barang_nama.'</h5></div>
                        <div><h6>'.$thisValue->barang->barang_kode.'</h6></div>
                    </div>
                    <div class="col">
                        <div><h6>'.$thisValue->jumlah.'</h6></div>
                    </div>
                    </div>
                </div>
                </div>
            </div>'
            ;
        }
        $data['total_harga'] = 'Rp. '.number_format($total_harga, 0, '', '.');
        $data['cart_data'] = $output;
        echo json_encode($data);
    }

    public function customerCartDetail()
    {
        $data = [];
        $get = $this->cart;
        if (count($get) > 0) {
            foreach ($get as $key => $value) {
                // dd($value);
                $total_harga = $value->getThis()->jumlah * $value->getThis()->barang->harga_user;
                $data[] = array(
                    'id' => "".$value->getThis()->id,
                    'name' => $value->getThis()->barang->barang_nama,
                    'qty' => "".$value->getThis()->jumlah,
                    'total' => 'Rp. '.number_format($total_harga, 0, '', '.')
                );
            }
        }
        return $data;
    }

	public function customerSaveCart($request){
        DB::beginTransaction();
        try {
			$userCart = $this->cart;
            $isUpdate = false;
			foreach ($userCart as $key => $value) {
				$thisValue = $value->getThis();
				if ($thisValue->barang->barang_id == intval($request->id_barang)) {
					DB::table('cart')
						->where('id', $thisValue->id)
						->update(['jumlah' => $thisValue->jumlah + intval($request['jumlah'])]);
					$this->cart[$key]->setJumlah($thisValue->jumlah + intval($request['jumlah']));
                    $isUpdate = true;
					break;
				}
			}
            if ($isUpdate == false) {
                $id = DB::table('cart')->insert([
                    'id_barang' => $request['id_barang'],
                    'id_user' => $request['id_user'],
                    'jumlah' => $request['jumlah']
                ]);
                $newCart = new Cart($id, $request['id_barang'], $request['id_user'], $request['jumlah']);
                array_push($this->cart, $newCart);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return response($th, 500);
        }
    }

    public function customerDeleteCart($request){
		DB::beginTransaction();
		$newCart = [];
		foreach ($this->cart as $key => $value) {
			$thisValue = $value->getThis();
			if ($thisValue->id != $request['cart_id']) {
				$newCart[] = $value;
			}
		}
		$this->cart = $newCart;
        DB::table('cart')->where('id',$request['cart_id'])->delete();
		DB::commit();
    }

	public function store()
    {
        DB::beginTransaction();
        try {
            $noNota = function() {
                $id = base64_encode(base64_encode(base64_encode(Auth::user()->id)));
                $time = date('YmdHis');
                return 'ORD-'.$id.$time;
            };
			$noNota = $noNota();
			$purchaseOrder = DB::table('purchase_orders')->insertGetId(['no_nota' => $noNota, 'user_id' => $this->id]);
			$id = $purchaseOrder;

			foreach ($this->cart as $key => $value) {
				$thisValue = $value->getThis();
				$orderAwalPesan = new Order(
					null,
					$id,
					$thisValue->barang->barang_id,
					$thisValue->jumlah,
					"AWAL PESAN",
					$thisValue->barang->harga_user,
					$thisValue->barang->barang_nama
				);
				$orderBelumDisetujui = new Order(
					null,
					$id,
					$thisValue->barang->barang_id,
					$thisValue->jumlah,
					"BELUM DISETUJUI",
					$thisValue->barang->harga_user,
					$thisValue->barang->barang_nama
				);
				$orderAwalPesan->save();
				$orderBelumDisetujui->save();
			}
			DB::table('cart')->where('id_user', $this->id)->delete();
            DB::commit();

            return response([
                'message' => 'Terimakasih sudah membeli product kami'
            ], 200);
        } catch(Exception $e) {
            DB::rollBack();

            return response(['message' => $e->getMessage()], 400);
        }
    }

    public function orderUnaccepted()
    {
        $order_unaccepted = [];
        foreach ($this->po as $key => $value) {
            if ($value->getThis()->tagihan == null) {
                $order_unaccepted[] = $value;
            }
        }
        return $order_unaccepted;
    }

    public function orderUnpaid()
    {
        $order_unpaid = [];
        foreach ($this->po as $key => $value) {
            if ($value->getThis()->tagihan != null) {
                if ($value->getThis()->tagihan->getThis()->status != "LUNAS") {
                    $order_unpaid[] = $value;
                }
            }
        }
        return $order_unpaid;
    }

    public function orderPaid()
    {
        $order_paid = [];
        foreach ($this->po as $key => $value) {
            if ($value->getThis()->tagihan != null) {
                $tagihan = $value->getThis()->tagihan;
                if ($tagihan->getThis()->status == "LUNAS" && $tagihan->getThis()->kirim != "DITERIMA") {
                    $order_paid[] = $value;
                }
            }
        }
        return $order_paid;
    }

    public function uploadTransfer($request) {
        // nilai-nilai untuk kolom 'valid' di tabel Payment sebagai berikut:
        // 0 = memiliki arti bukti transfer ditolak
        // 1 = memiliki arti bukti transfer diterima sebagian
        // 2 = memiliki arti bukti transfer diterima semua
        // 9 = memiliki arti bukti transfer belum diproses admin
        try {
            DB::beginTransaction();
            $validator = \Validator::make($request->all(), [
                'jumlahBayarInput' => 'required|numeric|min:100',
                'inputBukti' => 'required|image'
            ],[
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
                
                $data = [
                    'po_id' => $request['po_id_pembayaran'],
                    'valid' => 9,
                    'nominal_bayar' => $request['jumlahBayarInput'],
                    'bukti_tf' => 'public/tagihan/'.$filename
                ];
                DB::table('payments')->insert($data);
				foreach ($this->po as $key => $value) {
					$thisValue = $value->getThis();
					if ($thisValue->id == $request['po_id_pembayaran']) {
						$this->po[$key]->getThis()->tagihan->setStatus('BELUM DIPROSES ADMIN');
						DB::table('tagihans')->where('po_id', $request['po_id_pembayaran'])->update(['status' => 'BELUM DIPROSES ADMIN']);
						break;
					}
				}
                DB::commit();
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response($th, 500);
        }
    }

    public function confirmOrder($request)
    {
        DB::beginTransaction();
        try {
            DB::table('tagihans as t')
                ->leftJoin('purchase_orders as po', 't.po_id', 'po.id')
                ->where('t.po_id', $request->po_id_confirm)
                ->where('po.user_id', Auth::user()->id)
                ->update(['t.kirim' => 'DITERIMA']);
			foreach ($this->po as $key => $value) {
				$thisValue = $value->getThis();
				if ($thisValue->tagihan != null) {
					if ($thisValue->tagihan->getThis()->po_id == $request->po_id_confirm) {
						$thisValue->tagihan->setKirim("DITERIMA");
					}
				}
			}
            DB::commit();
        } catch (\Throwable $th) {
            return response($th, 500);
        }
    }

    public function completedList()
    {
        $completedList = [];
        foreach ($this->po as $key => $value) {
            if ($value->getThis()->tagihan != null) {
                $tagihan = $value->getThis()->tagihan;
                if ($tagihan->getThis()->status == "LUNAS" && $tagihan->getThis()->kirim == "DITERIMA") {
                    $completedList[] = $value;
                }
            }
        }
        return $completedList;
    }

    public function customerUpdateProfile($request)
    {
        try {
            DB::beginTransaction();
            DB::table('users')
                ->where('id', '=', $this->id)
                ->update(['name' => $request->name, 'address' => $request->address, 'no_handphone' => $request->no_handphone]);
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            $data = [
                'status' => 'error',
                'data' => $this
            ];
            return response($data, 500);
        }
    }
}
