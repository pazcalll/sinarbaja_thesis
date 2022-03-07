<?php

namespace App\Http\Controllers;

use App\Order;
use App\Tagihan;
use App\User;
use Illuminate\Http\Request;

class BonusController extends Controller
{
    //
    public function getSales(Request $request)
    {
        $seller = User::with(['groupUser'=>function($table){
            return $table->where('group_name', 'SALES');
        }])->whereHas('groupUser' , function ($table){
            $table->whereIn('group_name',['SALES']);
        })->get()->toArray();
        // return response($seller, 200);
        return view('dashboard.seller-list')->with('seller', $seller);
        // dd($seller);
    }

    public function getSalesBonus($id, $name)
    {
        $id = intval($id);
        $sellerBills =Tagihan::with(['po.user' => function($db) use($id) {
            return $db->where('id', $id);
        }])->whereHas('po.user', function($db) use($id){
            $db->whereIn('id', [$id]);
        })
        ->get()->toArray();
        // dd($sellerBills);
        return view('dashboard.seller-bonus', ['sellerBills'=>$sellerBills])->with('name', $name);
        return response($sellerBills, 200);
    }

    public function getTagihanDetail($tagihan)
    {
        // dd($tagihan);
        $detailTagihan = Order::with('tagihan')->where('tagihan_id', $tagihan)->get()->toArray();
        return response($detailTagihan, 200);
    }
}
