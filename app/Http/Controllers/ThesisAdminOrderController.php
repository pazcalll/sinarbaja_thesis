<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Helpers\ConditionalHelper;
use App\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ThesisAdminOrderController extends Controller
{
    public $admin;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->admin = new Admin();
            
            return $next($request);
        });
    }

    public function index()
    {
        $incomingOrder = $this->admin->incomingOrder();
        return view('adminThesis.orderIncoming')->with('order', $incomingOrder);
    }
    
    public function store(Request $request)
    {
        $store = $this->admin->store($request);
        return $store;
    }
    
    public function show($no_nota)
    {
        $show = $this->admin->show($no_nota);
        return $show;
    }
    
    public function sendPage()
    {
        return view('adminThesis.orderSend');
    }

    public function sendList()
    {
        $sendList = $this->admin->sendList();
        return $sendList;
    }

    public function approvalUrl(Request $request)
    {
        $approvalUrl = $this->admin->approvalUrl($request);
        return $approvalUrl;
    }

    public function approvalBill(Request $request)
    {
        $approvalBill = $this->admin->approvalBill($request);
        return response()->json($approvalBill);
    }

    public function sendOrder(Request $request)
    {
        $sendOrder = $this->admin->sendOrder($request);
        return $sendOrder;
    }

    public function sendingPage()
    {
        return view('adminThesis.orderSending');
    }

    public function sendingList()
    {
        $sendingList = $this->admin->sendingList();
        return $sendingList;
    }

    public function completedPage()
    {
        return view('adminThesis.orderCompleted');
    }

    public function completedList()
    {
        $completedList = $this->admin->completedList();
        return $completedList;
    }
}
