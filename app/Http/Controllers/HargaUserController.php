<?php

namespace App\Http\Controllers;

use App\Product;
use App\User;
use App\HargaProdukGroup;
use App\HargaProdukUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Response;

class HargaUserController extends Controller {

    public function index() {
        $settingHargaUser = DB::table('users AS a')
        ->whereIn('id_group', [2,3,4,5,6,7,8])
        ->leftJoin('user_setting AS b','b.user_id','a.id')
        ->leftJoin('group_users AS c','c.id','a.id_group')
        ->get();
        $group = User::with('group_user');
        return view('dashboard.setting-harga-user', compact('settingHargaUser', 'group'));
    }

    public function setting_harga($id, $name, $id_group) {
        $harga = HargaProdukGroup::with(['group'])
        ->where('id_group', $id_group)
        ->orderBy('id_product','ASC')
        ->get();
        return view('dashboard.harga-user')
        ->with('harga', $harga)
        ->with('name', $name)
        ->with('userId', $id)
        ->with('groupId', $id_group);
    }

    public function hargaCheckbox(Request $request) {
        // dd($request->post());
        $baruHarga = HargaProdukGroup::where('id', intval($request->post('harga_user')))->first('harga_group');
        // dd($baruHarga);
        $productCheck = HargaProdukUser::whereIn('id', $request->post('id'))->update(['harga_user' => $baruHarga['harga_group']]);
        // dd($productCheck);
        return response([$request->post(), $baruHarga, $productCheck]);
    }

    public function hargaUser($id, $id_group, Request $request) {
        $id_product = DB::table('harga_produk_user')
        ->where('id_user', $id)
        ->get();
        $array = array();
        foreach ($id_product as $value) {
            array_push($array, $value->id_product);
        }
        // $userHargaSet = DB::SELECT("SELECT p.id , max(p.nama),
        // coalesce(max(case when hpg.id_group = 2 then hpg.harga_group end), 0) as level1,
        // coalesce(max(case when hpg.id_group = 3 then hpg.harga_group end), 0) as level2,
        // coalesce(max(case when hpg.id_group = 4 then hpg.harga_group end), 0) as level3,
        // coalesce(max(case when hpg.id_group = 5 then hpg.harga_group end), 0) as level4
        // FROM harga_produk_group as hpg
        // JOIN products as p ON hpg.id_product = p.id
        // RIGHT JOIN harga_produk_user as hpu on hpg.id_product = hpu.id_product
        // GROUP BY hpg.id_product, p.id
        // ");
        // dd($userHargaSet);
        // dd($array);
        $data = DB::table('harga_produk_group as hpg')
        ->join ('tbl_barang as p', 'hpg.id_product', 'p.barang_id')
        ->whereIn('hpg.id_product', $array)
        ->rightjoin ('harga_produk_user as hpu', 'hpg.id_product', 'hpu.id_product')
        ->where('id_user', $id);
        if ($request->filter_check == 'true') {
          $data = $data->whereNull('hpu.harga_user');
        }
        else {
          $data = $data->whereNotNull('hpu.harga_user');
        }
        $data = $data->groupBy('hpg.id_product', 'p.barang_id', 'hpu.id_user', 'hpu.id_group')
        ->select('p.barang_id as id', 'hpu.id_user', 'hpu.id_group', DB::raw('max(p.barang_nama) as nama'), DB::raw('max(p.barang_kode) as barang_kode'),
        DB::raw('coalesce(max(case when hpg.id_group = 2 then hpg.harga_group end), 0) as level1,
        coalesce(max(case when hpg.id_group = 3 then hpg.harga_group end), 0) as level2,
        coalesce(max(case when hpg.id_group = 4 then hpg.harga_group end), 0) as level3,
        coalesce(max(case when hpg.id_group = 5 then hpg.harga_group end), 0) as level4')
        )
        ->get();
        // $userHargaSet = DB::SELECT("select al.id,al.nama,al.barang_kode, coalesce(sum(case when al.id_group = 2 then al.harga_group end), 0)
        // as level1, coalesce(sum(case when al.id_group = 3 then al.harga_group end), 0) as level2, coalesce(sum(case when al.id_group = 4
        // then al.harga_group end), 0) as level3, coalesce(sum(case when al.id_group = 5 then al.harga_group end), 0) as level4 from
        // (select b.id, b.nama, b.barang_kode, h.id_group, h.harga_group from products b join harga_produk_group h on b.id = h.id_product) al group by al.id
        // ");

        // $userHargaSet = HargaProdukUser::with(['product', 'user'])->where('id_user', $id)->get();
        // dd($userHargaSet);
        return response($data, 200);
    }

    public function pilihHarga($id, $id_group) {
        $item = DB::table('harga_produk_user AS a')
        ->leftJoin('group_users AS b','a.id_group','b.id')
        ->where('a.id_user', $id)
        ->whereNotNull('a.harga_user')
        ->get()->toArray();
        return response($item, 200);
    }

    public function setHarga(Request $request) {
        // dd($request->post());
        $getHarga = DB::table('harga_produk_group')
        ->where('id_product',intval($request->post('id')))
        ->where('id_group',intval($request->post('lev1')))
        ->first();
        $productTarget = HargaProdukUser::where('id_product', intval($request->post('id')))
        ->where('id_user', intval($request->post('idUser')))
        ->update([
          'id_group' => intval($request->post('lev1')),
          'harga_user' => !empty($getHarga)?$getHarga->harga_group:null
        ]);
        return response([$request->post(), $productTarget]);
    }

    public function saveAll(Request $request)
    {
        DB::beginTransaction();
        $hargaAll = $request->post('data');
        // dd($hargaAll);
        // dd($hargaAll[0]['id_user']);
        $id_user = $request->post('data')[0]['id_user'];
        // $idProduct = [];
        foreach ($hargaAll as $key => $value) {
          $getHarga = DB::table('harga_produk_group')
          ->where('id_product',$value['id'])
          ->where('id_group',$value['harga'])
          ->first();
          // array_push($idProduct, $value->id);
            $updateHarga = HargaProdukUser::where('id_user', $id_user)
            ->where('id_product', $value['id'])
            ->update([
                'id_group' => $value['harga'],
                'harga_user' => !empty($getHarga)?$getHarga->harga_group:null
            ]);
        }
        DB::commit();
        return response($updateHarga, 200);
    }
}
