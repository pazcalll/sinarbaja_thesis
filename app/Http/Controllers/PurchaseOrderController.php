<?php

namespace App\Http\Controllers;

use App\Customer;
use App\LogStock;
use App\Order;
use App\Payment;
use App\Product;
use App\PurchaseOrder;
use App\Tracking;
use App\Tagihan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $customer;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->customer = new Customer();
            return $next($request);
        });
    }

    public function store(Request $request)
    {
        return $this->customer->store();
    }
}
