<?php

namespace App\Http\Controllers;

use App\HargaProdukUser;
use App\Image;
use App\LogStock;
use App\Product;
use App\Stock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        // $stocks = Stock::where('user_id', Auth::user()->id)->with(['product'=>function ($db){
        //     return $db->with('images');
        // }])->get();
        $data = DB::table('tbl_barang AS a')
        // ->join('tbl_log_stok AS b','a.barang_id','b.product_id')
        // ->where('b.user_id',Auth::user()->id)
        ->get();
    //     $data = DB::table('tbl_barang')->leftjoin('tbl_satuan','tbl_satuan.satuan_id','=','tbl_barang.satuan_id')
    //     ->join('tbl_log_stok', 'tbl_barang.barang_id', '=', 'tbl_log_stok.id_barang')
    //     ->orderBy('barang_id', 'ASC')
    //     ->select(DB::raw('
    //       barang_id,
    //       tbl_barang.satuan_id,
    //       tbl_barang.barang_kode,
    //       tbl_barang.barang_nama,
    //       tbl_barang.barang_id_parent,
    //       tbl_barang.barang_status_bahan,
    //       tbl_barang.barang_alias,
    //       tbl_barang.paper,
    //       tbl_barang.keterangan,
    //       tbl_barang.created_at,
    //       tbl_barang.updated_at,
    //       satuan_nama,
    //       satuan_satuan,
    //       konversi,
    //       SUM( tbl_log_stok.unit_masuk - tbl_log_stok.unit_keluar ) AS stok'
    //   ))
    //     ->groupBy('tbl_barang.barang_id')->get();
        // dd($data);
        $i = 1;
        foreach ($data as $key => $value) {
          $stocks[] = array(
            'no' => $i++,
            'id' => $value->barang_id,
            'nama' => $value->barang_nama,
            'kode_barang' => $value->barang_kode,
            // 'grup' =>
          );
        }
        if (count($data) == 0) {
            $stocks = [];
        }
        // dd($stocks);
        return view('dashboard.product')->with('stocks', $stocks);
    }

    public function create()
    {
        return view('dashboard.new-product');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return response()->json(['data' => $request->all()], 200);

        $input = $request->except(['_token', 'stock', 'photo']);
        // dd($input);
        // try {
            DB::beginTransaction();

            $data = Product::where('id', $input['pId'])->first();

            // Stock::create([
            //     'user_id' => Auth::id(),
            //     'product_id' => $data->id,
            //     'stock' => $request->get('stock')
            // ]);

            Helper::storeImage($request, 'photo', function($hashname) use ($data) {
                Image::create([
                    'path' => $hashname,
                    'product_id' => $data->id,
                    'barang_alias' => $data->barang_alias
                ]);
            });

            DB::commit();
            return response([
                'message' => 'Berhasil Memasukkan data',
                'data' => $data
            ], 200);

    }

    public function show(Product $product)
    {
        //
    }

    public function edit($product)
    {
        $product = Product::find($product);
        return view('dashboard.new-product', compact('product'));
    }

    public function prices($id, $name)
    {
        $data['id'] = $id;
        $data['name'] = $name;
        // $data['category'] = $category;
        return view('dashboard.product-prices', compact('data'));
    }

    public function userPriceList($id, $name){
        $priceByProductId = HargaProdukUser::where('id_product', $id)->get();
        // dd($priceByProductId);
        return view('dashboard.list-harga-user')->with('priceByProductId', $priceByProductId)->with('productName', $name)->with('productId', $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {

    }

    public function setVisible($product)
    {
        $product = Product::find($product);
        $status = $product->ditampilkan === 0 ? 'menampilkan' : 'menyembunyikan';

        try {
            DB::beginTransaction();

            $product->ditampilkan = $product->ditampilkan === 0 ? 1 : 0;
            $product->save();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => "Berhasil $status data produk dari katalog."
            ], 200);
        } catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => "Gagal $status data produk dari katalog."
            ], 400);
        }
    }

    public function inventory() {
        return view('dashboard.inventory');
    }

    public function updateStock(Request $request) {
        $input = $request->except('_token');
        LogStock::create($input);
        $stock = Stock::find($request->input('stock_id'));
        $stock->stock = $request->input('current');
        $stock->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mengupdate stock product '. $stock->product->nama,
        ], 200);
    }

    public function inventoryDatatables() {
        $data = Product::with(['category', 'stock']);
        return datatables($data)->toJson();
    }

    public function inventoryInOutDatatables($type, $id) {
        $data = LogStock::whereHas('stock', function($table) use ($id) {
                return $table->where('user_id', Auth::user()->id)->where('product_id', $id);
            })
            ->where('type', strtoupper($type));

        return datatables($data)->toJson();
    }

    public function destroy($product)
    {
        try {
            DB::beginTransaction();

            $product = Product::find($product);
            $product->delete();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menghapus data product',
                'data' => $product
            ], 200);
        } catch(Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data product'
            ], 400);
        }
    }
}
