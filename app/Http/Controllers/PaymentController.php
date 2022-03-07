<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Tagihan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function getPayment(Request $request){
        // dd($request->data[1]);
        $tagihan_id = $request->data[0];
        $po_id = $request->data[1][0];
        // $data = Payment::with(['po_id' => function($table)use($po_id){
        //     return $table->where('po_id', $po_id);
        // }])->get();
        $data = Payment::where('po_id',$po_id)->get();

        return response($data, 200);
        
    }
    public function validateTransfer(Request $request){
        // dd($request);
        $validateEvidence = Payment::where('id', (int)$request['transferValidationId']);
        $tagihan = Tagihan::with(['payment' => function($db) use ($request){
            return $db->where('id', (int)$request['transferValidationId']);
        }])->where('po_id', $validateEvidence->get('po_id')->first()['po_id']);
        // dd($tagihan);
        if ($request['transferValidationStatus'] == "true") {
            $validateEvidence->update([ 'valid' => 2 ]);
            $tagihan->update([
                'status' => 'LUNAS'
            ]);
        }
        else $validateEvidence->update(['valid' => 0]);
        return response($validateEvidence->get(), 200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $user = Auth::user();
        // $payment = Payment::all();
        // return response([$payment, $user], 200);
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
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
