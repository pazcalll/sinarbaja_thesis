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
            else if (Auth::user()->id_group == 3) {
                // return redirect('order');
                return view('driver.order');
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
        DB::raw('SUM(b.unit_keluar) AS unit_keluar_sum'),'e.harga','e.stok')
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
        ->leftJoin('user_setting AS e','d.id_user','e.user_id')
        ->groupBy('a.barang_id');
        $count = $get->get();
        $get_count = count($count);
        $get = $get->limit($limit)->offset($offset)->get();
        foreach ($get as $key => $value) {
          // dd($value->stok);
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
            $value->stok == 'on'?$stk_str = $stok.'  '.Satuan::where('satuan_id', $value->satuan_id)->get('satuan_nama')[0]->satuan_nama:$stk_str = null;
            $value->harga == 'on'?$harga = $harga:$harga = null;
            $data[] = array(
                      'id' => $value->barang_id,
                      'nama' => $value->barang_nama,
                      'deskripsi' => $value->barang_kode.' - '.$value->barang_alias,
                      'stok' => !empty(Auth::user())?$stk_str:null,
                      'harga' => !empty($harga)?$harga:null,
                      'btn' => ''
                    );
          }
        }
        // dd($data);
        // if (count($get) > 0) {
        //   foreach ($get as $value) {
        //     // dd($get);
        //     $persediaan = DB::select("SELECT
        //                                   *
        //                                 FROM
        //                                     (
        //                                     SELECT
        //                                         tls.*,
        //                                         tb.barang_kode AS kode_barang,
        //                                         tb.barang_nama AS nama_barang,
        //                                         rg.nama AS nama_gudang,
        //                                         SUM( tls.unit_masuk - tls.unit_keluar ) AS stok,
        //                                         ts.satuan_nama AS nama_satuan  ,
        //                                     SUM( (tls.unit_masuk * ts.konversi) - (tls.unit_keluar * ts.konversi) ) as konversi
        //                                     FROM
        //                                         tbl_log_stok AS tls
        //                                         LEFT JOIN tbl_barang AS tb ON tls.id_barang = barang_id
        //                                         LEFT JOIN ref_gudang AS rg ON tls.id_ref_gudang = rg.id
        //                                         LEFT JOIN tbl_satuan AS ts ON tls.id_satuan = ts.satuan_id
        //                                     WHERE tb.barang_id = $value->barang_id
        //                                     GROUP BY
        //                                         id_barang,
        //                                         id_ref_gudang, id_satuan
        //                                     ORDER BY
        //                                         tb.barang_nama
        //                                     ) b
        //                                 WHERE
        //                                   b.stok NOT LIKE '%-%'");
        //     // dd($persediaan[0]->stok);
        //     if(!empty(Auth::user()->id_group)){
        //       $harga = 'Rp. '.number_format($value->harga_group, 2, ',', '.');
        //     }
        //     else {
        //       $harga = 'Login untuk melihat harga';
        //     }
        //     // if (!empty($value->unit_masuk)) {
        //     if ($persediaan[0]->stok != "0" || $persediaan[0]->stok != 0) {
        //       $stok = (int)$value->unit_masuk - (int)$value->unit_keluar;
        //       if ($stok != 0) {
        //         $data[] = array(
        //           'id' => $value->barang_id,
        //           'nama' => $value->barang_nama,
        //           'deskripsi' => $value->barang_kode.' - '.$value->barang_alias,
        //           'stok' => $persediaan[0]->stok,
        //           'harga' => $harga,
        //           'btn' => ''
        //         );
        //       }
        //     }
        //   }
        // }
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

    public function search(Request $request)
    {
        session()->forget(['filter']);
        $input = $request->search;
        $get = DB::table('tbl_barang AS a')
        ->leftJoin('tbl_log_stok AS b','a.barang_id','b.id_barang')
        ->where('a.barang_alias','like','%'.$input.'%')
        ->whereNotNull('b.log_stok_id')
        ->groupBy('a.barang_alias')
        ->get();
        if (count($get) > 0) {
          foreach ($get as $key => $value) {
            if (!empty($value->log_stok_id)) {
              $status = 'Tersedia';
            }
            else {
              $status = 'Kosong';
            }
            $produk[] = array(
              'barang_alias' => $value->barang_alias,
              'status' => $status
            );
          }
        }
        else {
          $produk = [];
        }
        return response()->json([
            'status' => 'success',
            'data' => $produk
        ], 200);
        // $product = Product::with(['images', 'stocks', 'category'])->where('nama', 'like', '%' . $input . '%');
        // return $this->response($product);
    }

    public function filter(Request $request)
    {
        if (!empty($request->all())) {
            $filterBy = $request->all();
            session(['filter' => $filterBy]);
        } else {
            return redirect(url('/'));
        }

        $filter = session()->get('filter') ?? [];
        $hargaMin = session()->get('filter')['hargaMin'];
        $hargaMax = session()->get('filter')['hargaMax'];
        $product = Product::with(['images', 'stocks', 'category']);

        if ($hargaMin != null) {
            $product = $product->where('harga', '>=', $hargaMin);
        }
        if ($hargaMax != null) {
            $product = $product->where('harga', '<=', $hargaMax);
        }
        foreach($filter as $key => $value) {
            if ($key != 'hargaMin' && $key != 'hargaMax' && $value != null) {
                $product = $product->where($key, $value);
            }
        }

        return $this->response($product);
    }

    private function response($product) {
        return response()->json([
            'status' => 'success',
            'message' => "Berhasil load data produk dari katalog.",
            'data' => $product->paginate(4)
        ], 200);
    }

    public function hargaGroup(){
        $harga = HargaProdukGroup::get();
        return response($harga, 200);
    }
}
