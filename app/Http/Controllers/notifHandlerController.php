<?php

namespace App\Http\Controllers;
use Route;
use Illuminate\Http\Request;
use DB,Response;
class notifHandlerController extends Controller
{
    public function count_harganull(){
      $get = DB::table('harga_produk_user AS a')
      ->select('b.id','b.id_group','b.name',DB::raw('COUNT(a.id) AS count'))
      ->whereNull('a.harga_user')
      ->leftJoin('users AS b','a.id_user','b.id')
      ->get();
      // $value->id.'/'.$value->name.'/'.$value->id_group.
      foreach ($get as $key => $value) {
        $output[] ='
          <div class="col-12">
          <a href="'Route::currentRouteName()'">
            <div class="card border-bottom shadow-sm bg-white rounded">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <div class="text-info" ><h4>'.$value->name.'</h4></div>
                  </div>
                  <div class="col">
                    <div style="float:right;padding-right:20px">
                        <h6 class="text-danger">'.$value->count.' Unit harga kosong</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </a>
          </div>
          ';
      }
      $data['count'] = count($get);
      $data['data'] = $output;
      return response()->json($data,200);
    }
}
