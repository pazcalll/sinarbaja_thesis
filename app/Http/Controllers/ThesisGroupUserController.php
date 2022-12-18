<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThesisGroupUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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
        try {
            $user_group = DB::select('SELECT gu.id, gu.group_name 
                from group_users as gu
                left join users as u 
                on u.id_group = gu.id
                where u.id = '.$id.'
            ');
            $groups = DB::select('SELECT id, group_name 
                from group_users
                where id != '.$user_group[0]->id.' and group_name != "ADMINISTRATOR"
            ');
            return response([$user_group, $groups], 200);
        } catch (\Throwable $th) {
            return response($th, 500);
        }
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
    public function update(Request $request)
    {
        //
        try {
            DB::beginTransaction();
            $harga_user_group = [];
            $newHarga = false;
            $getUser = DB::table('users')->where('id', intval($request->post('user-id-group-edit')))->get('id_group');
            if (count($getUser) > 0 && $getUser[0]->id_group == 1) $newHarga = true;

            $changeGroup = DB::table('users')
                ->where('id', intval($request->post('user-id-group-edit')))
                ->update(['id_group' => $request->post('select-group-user')]);
            $hargaGroupGet = DB::table('harga_produk_group')
                ->where('id_group',$request->post('select-group-user'))->get();
            if ($newHarga == true) {
                foreach ($hargaGroupGet as $key => $value_harga) {
                    $newHarga = DB::table('harga_produk_group')
                        ->where('id_group',$request->post('select-group-user'))
                        ->where('id_product',$value_harga->id_product)
                        ->first();
                    $harga_user_group[] = array(
                        'id_group' => $request->post('select-group-user'),
                        'id_product' => $value_harga->id_product,
                        'id_user' => intval($request->post('user-id-group-edit')),
                        'harga_user' => $newHarga->harga_group
                    );
                }
                // dd($harga_user_group, 'true');
            }else{
                if (count($hargaGroupGet) > 0) {
                    foreach ($hargaGroupGet as $key => $value_harga) {
                        $groupOld = DB::table('harga_produk_user')
                            ->where('id_user','=', intval($request->post('user-id-group-edit')))
                            ->where('id_product', $value_harga->id_product)
                            ->first();
                        $idG = !empty($groupOld)?$groupOld->id_group:$getUser[0]->id_group;
                        $newHarga = DB::table('harga_produk_group')
                            ->where('id_group',$idG)
                            ->where('id_product',$value_harga->id_product)
                            ->first();
                        $harga_user_group[] = array(
                            'id_group' => $idG,
                            'id_product' => $value_harga->id_product,
                            'id_user' => intval($request->post('user-id-group-edit')),
                            'harga_user' => $newHarga->harga_group
                        );
                    }
                    // dd($harga_user_group, 'false');
                }
            }
            $data_harga_user = collect($harga_user_group);
            $chunk_harga_user = $data_harga_user->chunk(1000);
            
            DB::table('harga_produk_user')->where('id_user', intval($request->post('user-id-group-edit')))->delete();
            foreach ($chunk_harga_user as $chunk){
                $inser_tbl_harga_user = DB::table('harga_produk_user')->insert($chunk->toArray());
            }
            DB::commit();
            return response($changeGroup, 200);
        } catch (\Throwable $th) {
            return response($th, 500);
        }
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
}
