<?php

namespace App\Http\Controllers;

use App\HargaProdukGroup;
use App\HargaProdukUser;
use App\User;
use Illuminate\Http\Request;

class HargaProdukUserController extends Controller
{

    public function deleteByGroup(Request $request){
        // dd($request);
        try{
            $delete = HargaProdukUser::where('id_group', $request->post('idGroup'))
                ->where('id_product', $request->post('idProduct'))
                ->delete();
            return response($delete, 200);
            // return response(['req'=>$req->post('hargaDelete')], 200);
        }
        catch(Exception $e){
            return response(['req'=>$e], 200);
        }
    }

    public function usersWithPrices($produkId)
    {
        $userWithprices = HargaProdukUser::with(['user', 'group'])->where('id_product', $produkId)->get();
        // dd($userWithprices);
        return response($userWithprices, 200);
    }

    public function groupPriceSelection($productId){
        $item = HargaProdukGroup::with('group')->where('id_product', $productId)->get();
        return response($item, 200);
        // dd($item);
    }

    public function changeUserPrice(Request $request){
        $newPrice = HargaProdukGroup::where('id', intval($request->post('groupHargaSelect')))->first('harga_group');
        // dd($newPrice);
        $userTarget = HargaProdukUser::where('id', intval($request->post('id')))->update(['harga_user' => $newPrice->harga_group]);
        return response([$request->post(), $newPrice, $userTarget], 200);
    }
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
        $user = User::where('id_group', $request->post()['groupUserSelector'])->get();
        $dataCollection = [];
        $productDeletions = [];
        $idDeletions = [];
        foreach ($user as $key => $value) {
            $dataCollection[$key]['id_group'] = $request->post()['groupUserSelector'];
            $dataCollection[$key]['id_product'] = $request->post()['dataId'];
            $dataCollection[$key]['id_user'] = $value['id'];
            $dataCollection[$key]['harga_user'] = $request->post()['harga'];

            $productDeletions[$key] = $request->post()['dataId'];
            $idDeletions[$key] = $value['id_group'];
            // return response($value['id']);
        }
        $destroyer = HargaProdukUser::whereIn('id_product', $productDeletions)->whereIn('id_group', $idDeletions)->delete();
        $store = HargaProdukUser::insert($dataCollection);
        return response(HargaProdukUser::whereIn('id_product', $productDeletions)->whereIn('id_group', $idDeletions)->get(), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\HargaProdukUser  $hargaProdukUser
     * @return \Illuminate\Http\Response
     */
    public function show(HargaProdukUser $hargaProdukUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\HargaProdukUser  $hargaProdukUser
     * @return \Illuminate\Http\Response
     */
    public function edit(HargaProdukUser $hargaProdukUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\HargaProdukUser  $hargaProdukUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HargaProdukUser $hargaProdukUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\HargaProdukUser  $hargaProdukUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(HargaProdukUser $hargaProdukUser)
    {
        //
    }
}
