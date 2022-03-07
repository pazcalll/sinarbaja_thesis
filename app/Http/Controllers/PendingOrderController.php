<?php

namespace App\Http\Controllers;

use App\Order;
use App\PendingOrder;
use Illuminate\Http\Request;

class PendingOrderController extends Controller
{

    public function index()
    {
        
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {

    }

    public function show(PendingOrder $pendingOrder)
    {
        //
    }

    public function edit(PendingOrder $pendingOrder)
    {
        //
    }

    public function update(Request $request, $pendingOrder)
    {
        $data = Order::find($pendingOrder);
        $data->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PendingOrder  $pendingOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(PendingOrder $pendingOrder)
    {
        //
    }
}
