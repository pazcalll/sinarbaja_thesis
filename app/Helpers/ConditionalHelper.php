<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class ConditionalHelper
{
    public static function stok_getter($id, $user)
    {
        // dd($id, $user);
        // $get = DB::table('tbl_barang AS a')
        // ->select('a.*','b.*','c.*','d.*',DB::raw('SUM(b.unit_masuk) AS unit_masuk_sum'),
        // DB::raw('SUM(b.unit_keluar) AS unit_keluar_sum'),'e.harga','e.stok')
        // ->where('a.barang_id', $id);
        // $get = $get->where('d.id_user', $user['id_group']);
        // if (!empty($user['id_group'])) {
        //   $get = $get->where('c.id_group', $user['id_group']);
        // }
        // if (empty($all_data)) {
        //   $get = $get->whereNotNull('b.unit_masuk');
        // }
        // $get = $get->leftJoin('tbl_log_stok AS b','a.barang_id','b.id_barang')
        // ->join('harga_produk_group AS c','c.id_product','a.barang_id')
        // ->leftJoin('harga_produk_user AS d','d.id_product','a.barang_id')
        // ->leftJoin('user_setting AS e','d.id_user','e.user_id')
        // ->groupBy('a.barang_id');
        // $count = $get->get();
        // return $count;
        $idbarang = $id;

        $gudang = DB::select("
        SELECT * FROM
            (SELECT SUM( s.unit_masuk ) AS unit_masuk_sum, SUM( s.unit_keluar ) AS unit_keluar_sum
            FROM tbl_log_stok AS s
            WHERE s.id_barang = '$idbarang') AA
        WHERE (unit_masuk_sum - unit_keluar_sum) > 0
        ");

        return $gudang;
    }
}