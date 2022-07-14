<?php

namespace App\Http\Controllers;

use App\Customer;
// use App\GroupUser;
use App\PurchaseOrder;
use App\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ThesisCustomerController extends Controller
{
    public $customer;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->customer = new Customer();
            
            return $next($request);
        });
    }
    
    public function index()
    {
        return view('clientThesis.order');
    }

    public function orderUnaccepted()
    {
        $orderUnaccepted = $this->customer->orderUnaccepted();
        $to_datatables = datatables($orderUnaccepted)->toJson();
        $data_po = str_replace('\u0000*\u0000', '', json_encode(datatables($orderUnaccepted)->toJson()->getData(true)));
        $to_datatables = $to_datatables->setData((array) json_decode($data_po));
        return $to_datatables;
    }

    public function orderUnpaid()
    {
        $orderUnpaid = $this->customer->orderUnpaid();
        $to_datatables = datatables($orderUnpaid)->toJson();
        $data_po = str_replace('\u0000*\u0000', '', json_encode(datatables($orderUnpaid)->toJson()->getData(true)));
        $to_datatables = $to_datatables->setData((array) json_decode($data_po));
        return $to_datatables;
    }

    public function orderPaid()
    {
        $orderPaid = $this->customer->orderPaid();
        $to_datatables = datatables($orderPaid)->toJson();
        $data_po = str_replace('\u0000*\u0000', '', json_encode(datatables($orderPaid)->toJson()->getData(true)));
        $to_datatables = $to_datatables->setData((array) json_decode($data_po));
        return $to_datatables;
    }

    public function uploadTransfer(Request $request) {
        $uploadTransfer = $this->customer->uploadTransfer($request);
        return $uploadTransfer;
    }

    public function confirmOrder(Request $request)
    {
        $confirmOrder = $this->customer->confirmOrder($request);
        return $confirmOrder;
    }

    public function completedList()
    {
        $completedList = $this->customer->completedList();
        $to_datatables = datatables($completedList)->toJson();
        $data_po = str_replace('\u0000*\u0000', '', json_encode(datatables($completedList)->toJson()->getData(true)));
        $to_datatables = $to_datatables->setData((array) json_decode($data_po));
        return $to_datatables;
    }

    public function customerProfile()
    {
        $customer = $this->customer->getThis();
        return view('clientThesis.profile', compact('customer'));
    }

    public function customerUpdateProfile(Request $request)
    {
        $customerUpdateProfile = $this->customer->customerUpdateProfile($request);
        return response($customerUpdateProfile, 200);
    }
}
