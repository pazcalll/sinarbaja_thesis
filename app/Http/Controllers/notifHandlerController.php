<?php

namespace App\Http\Controllers;
use Route;
use Illuminate\Http\Request;
use DB,Response;
class notifHandlerController extends Controller
{
  public function data_notifikasi(){
    $get = DB::table('tbl_log_activity')
    ->orderBy('id','DESC')
    ->get();
    if (count($get) > 0) {
      foreach ($get as $key => $value) {
        $keterangan = json_decode($value->ket);
        foreach ($keterangan as $keterangan) {
          $admin = DB::table('users')->where('id',$keterangan->ket->admin)->first();
          $user = DB::table('users')->where('id',$keterangan->ket->user)->first();
          $result[] = array(
            'admin' => $admin->name,
            'user' => $user->name,
            'status' => $keterangan->ket->status == 'true'? 'Aktif': 'Tidak Aktif'
          );
          $output[] = view('template.pages.notifikasi',compact('keterangan','result','value'))->render();
          $result = [];
          // dd($output);
        }
      }
    }
    echo json_encode($output);
  }
}
