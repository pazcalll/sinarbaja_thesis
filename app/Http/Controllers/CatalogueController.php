<?php

namespace App\Http\Controllers;

use App\HargaProdukGroup;
use App\Image;
use App\Product;
use App\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Response,Cookie;

class CatalogueController extends Controller
{
  private $userLogin;

  public function __construct(Request $request)
  {
    // if (!empty($request->cookie('login'))) {
    //   $user = DB::table('users')->where('email',$request->cookie('login'))->first();
    //   Auth::loginUsingId($user->id);
    // }
  }

    public function index()
    {
        if (Auth::user() != null) {
            if (Auth::user()->id_group == 1) {
                return redirect('dashboard');
            }
        }
        return view('catalogue.index');
    }

    public function tabel()
    {
        return view('catalogue.index-table');
    }

    public function notFound() {
        return view('catalogue.not-found');
    }

    public function detailTable($barang_alias) {
        return view('catalogue.detail-table', compact('barang_alias'));
    }

    public function get_detailBarang(Request $request){
        $data = [];
        // dd($request->post('alias'));
        $all_data = $request["all_data"];
        $draw = $request["draw"];
        $search = $request['search']['value'];
        $limit = is_null($request["length"]) ? 10 : $request["length"];
        $offset = is_null($request["start"]) ? 0 : $request["start"];
        $get = DB::table('tbl_barang AS a')
        ->select('a.*','b.*','c.*','d.*',DB::raw('SUM(b.unit_masuk) AS unit_masuk_sum'),
        DB::raw('SUM(b.unit_keluar) AS unit_keluar_sum'), 'ts.satuan_nama as satuan_nama')
        ->where('a.barang_alias', str_replace('__', ' ',$request->alias));
        if (Auth::user() != null) {
          $get = $get->where('d.id_user',Auth::user()->id);
        }
        if (!empty($search)) {
          $get = $get->where('a.barang_nama','like','%'.$search.'%');
        }
        if (!empty(Auth::user()->id_group)) {
          $get = $get->where('c.id_group',Auth::user()->id_group);
        }
        if (empty($all_data)) {
          $get = $get->whereNotNull('b.unit_masuk');
        }
        $get = $get->leftJoin('tbl_log_stok AS b','a.barang_id','b.id_barang')
        ->join('harga_produk_group AS c','c.id_product','a.barang_id')
        ->leftJoin('harga_produk_user AS d','d.id_product','a.barang_id')
        ->leftJoin('tbl_satuan as ts', 'ts.satuan_id', 'a.satuan_id')
        ->groupBy('a.barang_id');
        $count = $get->get();
        $get_count = count($count);
        $get = $get->limit($limit)->offset($offset)->get();
        foreach ($get as $key => $value) {
          $stok = $value->unit_masuk_sum - $value->unit_keluar_sum;
          if(!empty(Auth::user()->id_group)){
            if (!empty($value->harga_user)) {
              $harga = 'Rp. '.number_format($value->harga_user, 2, ',', '.');
            }
          }
          else {
            $harga = 'Login untuk melihat harga';
          }
          if ($stok > 0) {
            $data[] = array(
                      'id' => $value->barang_id,
                      'nama' => $value->barang_nama,
                      'deskripsi' => $value->barang_kode.' - '.$value->barang_alias,
                      'stok' => !empty(Auth::user())?$stok:null,
                      'satuan' => $value->satuan_nama,
                      'harga' => !empty($harga)?$harga:null,
                      'btn' => ''
                    );
          }
        }
        $recordsTotal = is_null($get_count) ? 0 : $get_count;
        $recordsFiltered = is_null($get_count) ? 0 : $get_count;
        // dd($request->post());
        return response()->json(compact("data", "draw", "recordsTotal", "recordsFiltered"));
    }
    public function tableDetail($barang_alias) {
        $stok = DB::select("SELECT b.id_barang, b.unit_masuk, b.unit_keluar, p.barang_alias
        FROM tbl_log_stok as b
        LEFT JOIN products as p ON b.id_barang = p.id
        WHERE p.barang_alias = '$barang_alias'
        ");
        $arr = [];
        foreach ($stok as $key => $value) {
            array_push($arr, $value->id_barang);
        }
        // dd($arr);
        // dd($stok);
        $product = Product::with(['images', 'category', 'harga_user' => function($db){
            return $db->where('id_user', Auth::id());
        }])->whereIn('id', $arr)->get();
        foreach ($stok as $key => $value) {
            // dd($value);
            $product[$key]['unit_masuk'] = $value->unit_masuk;
            $product[$key]['unit_keluar'] = $value->unit_keluar;
        }
        // dd($product);

        return response()->json([
            'status' => 'success',
            'data' => $product
        ], 200);
        // dd($product);
    }

    public function show($id)
    {
        return view('catalogue.detail', compact('id'));
    }

    public function catalogue()
    {
      $produk = [];
      $get = DB::table('tbl_log_stok AS a')
      ->select('a.id_barang AS id',
      DB::raw('SUM(a.unit_masuk) AS unit_masuk'),
      DB::raw('SUM(a.unit_keluar) AS unit_keluar'),
      'tb.barang_alias')
      ->leftJoin('tbl_barang as tb', 'tb.barang_id', 'a.id_barang')
      ->groupBy('a.id_barang')
      ->get();
      
      $tmpId = [];
      foreach ($get as $key => $value) {
        if (intval($value->unit_masuk) > intval($value->unit_keluar)) {
          $tmpId[] = $value->id;
        }
      }
      
      $barang = array_column(DB::table('tbl_barang')->whereIn('barang_id',$tmpId)->distinct()->get(['barang_alias'])->toArray(), 'barang_alias');
      // dd($tmpId); 
      $tmpCounter = 0;
      foreach ($get as $key => $value) {
        $stock = $value->unit_masuk - $value->unit_keluar;
        if ($stock > 0) {
          if (in_array($value->barang_alias, $barang)) {
            if (!in_array($value->barang_alias, $produk)) {
              $produk[] = $barang[$tmpCounter];
              $tmpCounter += 1;
            }
          }
        }
      }
      return response()->json([
        'status' => 'success',
        'data' => $produk
      ], 200);
    }

    public function detail(Request $request){
        $product = Product::with(['images', 'stocks', 'category'])->where('ditampilkan', 0)->where("id", $request->id);
        return $this->response($product);
    }

    public function hargaGroup(){
        $harga = HargaProdukGroup::get();
        return response($harga, 200);
    }
}
