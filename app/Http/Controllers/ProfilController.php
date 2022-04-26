<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use App\PurchaseOrder;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agent = Auth::user();
        $limitUsed = Order::wherehas('po.user', function($query) {
            return $query->where('id', Auth::user()->id)->with('group_user');
        })
        ->with(['po', 'product'])
        ->get()
        ->sum(function($data) {
            // dd($data);
            return $data->qty * $data->product->harga;
        });

        return view('client.profile', compact('agent', 'limitUsed'));
    }

    public function pending()
    {
        $order_pending = Order::wherehas('po.user',function($query){
            return $query->where('id', Auth::user()->id);
        })
        ->where('status','PENDING')
        ->with(['po','product']);

        return DataTables::of($order_pending)
        ->addColumn('nama_produk', function ($d){
            return $d->product->nama;
        })->addColumn('no_nota', function ($d){
            return '#'.$d->po->no_nota;
        })->addColumn('tgl_pesan', function ($d){
            $tanggal = ($d->po->jatuh_tempo == NULL)?'-':Helper::tgl_full($d->po->jatuh_tempo,1);
            return '<span class="text-muted"><i class="icon md-time"></i> '.$tanggal.'</span>';
        })->addColumn('total_pesanan', function ($d){
            return 'Rp. '.Helper::format_angka($d->product->harga * $d->qty);
        })->rawColumns(['tgl_pesan'])->make(true);
    }

    public function return()
    {
        $order_return = Order::wherehas('po.user',function($query){
            return $query->where('id', Auth::user()->id);
        })
        ->where('status','RETURN')
        ->with(['po','product']);

        return DataTables::of($order_return)
        ->addColumn('tgl_return', function ($d){
            $tanggal = ($d->po->tgl_return == NULL)?'-':Helper::tgl_full($d->po->tgl_return,1);
            return '<span class="text-muted"><i class="icon md-time"></i> '.$tanggal.'</span>';
        })->addColumn('nama_produk', function ($d){
            return $d->product->nama;
        })->addColumn('no_nota', function ($d){
            return '#'.$d->po->no_nota;
        })->addColumn('alasan', function ($d){
            return $d->po->alasan;
        })->addColumn('status', function ($d){
            return '-';
        })->rawColumns(['tgl_return'])->make(true);
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
        return response([
            'status' => 'error',
            'data' => $request->all()
        ], 200);
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
        $data = [
            'status' => 'error',
            'data' => $request->all()
        ];
        try {
            DB::beginTransaction();

            $user = User::find($id);// where('id',$id)->first()
            $user->name = $request->name;
            $user->address = $request->address;
            $user->no_handphone = $request->no_handphone;
            $user->save();

            DB::commit();
            $data = [
                'status' => 'success',
                'data' => $user
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $data = [
                'status' => 'error',
                'data' => $id
            ];
        }
        return response($data, 200);
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
    public function reset($id) {
        
    }
}
