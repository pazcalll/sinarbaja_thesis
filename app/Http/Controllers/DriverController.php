<?php

namespace App\Http\Controllers;

use App\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{

    public function index()
    {
        DB::table('users as u')
        ->leftjoin('tbl_group as tg', 'tg.group_id', 'u.group_id')
        ->where('tg.group_nama', '=', 'Driver')
        ->get();
        return response([
            'status' => 'success',
            'message' => 'Berhasil load data',
            // 'data'    => Driver::where('status', 'ACTIVE')->with('user')->get()
            'data'    => DB::table('users as u')
                ->leftjoin('tbl_group as tg', 'tg.group_id', 'u.group_id')
                ->where('tg.group_nama', '=', 'Driver')
                ->get()
        ], 200);
    }
}
