<?php

namespace App\Http\Controllers;

use App\HargaProdukGroup;
use Exception;
use Illuminate\Http\Request;

class HargaProdukGroupController extends Controller
{
    public function deleteHargaGroup(Request $req)
    {
        // dd($req);
        try{
            
            $delete = HargaProdukGroup::where('id', $req->post('hargaDelete'))->delete();
            return response($delete, 200);
            // return response(['req'=>$req->post('hargaDelete')], 200);
        }
        catch(Exception $e){
            return response(['req'=>$e], 200);
        }
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
        // dd($request->post());
        $store = HargaProdukGroup::create([
            'id_group' => $request->post()['groupUserSelector'],
            'id_product' => $request->post()['dataId'],
            'harga_group' => $request->post()['harga'],
        ]);
        return back();
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
        $hargaGroup = HargaProdukGroup::with('group')->where('id_product', $id);
        return response($hargaGroup->get(),200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        //
        HargaProdukGroup::with(['group', 'product'])->where('id', $id)->update([
            'harga_group' => $request['harga']
        ]);
        return back();
        // dd([$id, $request]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        // try{
        //     return response(['req'=>$request], 200);
        // }
        // catch(Exception $e){
        //     return response(['req'=>$e], 200);
        // }
    }
}
