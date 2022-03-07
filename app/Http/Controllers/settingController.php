<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB,Response;
class settingController extends Controller
{
    public function settingHarga(Request $request){
      $id = $request->id;
      $kondisi = $request->kondisi;
      DB::beginTransaction();
      try {
        if ($kondisi == 'true') {
          $set = DB::table('user_setting')
          ->where('user_id',$id)
          ->update([
            'harga' => 'on'
          ]);
        }
        else {
          $set = DB::table('user_setting')->where('user_id',$id)->update([
            'harga' => 'off'
          ]);
        }
        DB::commit();
        return response([
            'message' => 'Berhasil',
        ], 200);
      } catch (\Exception $e) {
        DB::rollback();
        return response([
            'message' => 'Gagal',
        ], 500);
      }
    }
    public function settingStok(Request $request){
      $id = $request->id;
      $kondisi = $request->kondisi;
      DB::beginTransaction();
      try {
        if ($kondisi == 'true') {
          $set = DB::table('user_setting')
          ->where('user_id',$id)
          ->update([
            'stok' => 'on'
          ]);
        }
        else {
          $set = DB::table('user_setting')->where('user_id',$id)->update([
            'stok' => 'off'
          ]);
        }
        DB::commit();
        return response([
            'message' => 'Berhasil',
        ], 200);
      } catch (\Exception $e) {
        DB::rollback();
        return response([
            'message' => 'Gagal',
        ], 500);
      }
    }
}
