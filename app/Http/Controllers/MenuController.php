<?php

namespace App\Http\Controllers;

use App\Menu;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.menu');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $menus = Menu::insert([
            'nama' => $request->post()['nama'],
            'link' => $request->post()['link'],
            'parent' => $request->post()['parent'],
            'urutan' => $request->post()['urutan'],
            'icon' => $request->post()['icon']
        ], 200);

        return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->exepct(['_token']);

        try {
            DB::beginTransaction();

            $data = Menu::create($input);

            DB::commit();

            return response([
                'message' => 'Berhasil Memasukkan data',
                'data' => $data
            ], 200);
        } catch(Exception $e) {
            DB::rollBack();

            return response(['message' => ''], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        Menu::where('id', $request->idMenu)->update([
            'nama' => $request->namaEdit,
            'urutan' => $request->urutanEdit,
            'icon' => $request->iconEdit
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Menu::find($id)->delete();
        return back()->with('success', 'Menu berhasil di hapus !');
    }
}
