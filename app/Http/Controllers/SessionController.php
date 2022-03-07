<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function store(Request $request) {
        $key = $request->input('key');
        $val = $request->input('data');
        $request->session()->put($key, $val);

        return response([
            'status'  => 'success',
            'message' => 'Berhasil menyimpan data sementar',
            'data'    => session($key)
        ], 200);
    }

    public function retrieve($key) {
        return response([
            'status' => 'success',
            'message' => 'Berhasil menyimpan data sementar',
            'data' => session($key)
        ], 200);
    }

    public function remove(Request $request) {
        $keys = $request->input('keys');
        $request->session()->forget($keys);
        return response([
            'status' => 'success',
            'message' => 'Berhasil menghapus data sementar'
        ], 200);
    }
}
