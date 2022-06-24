<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Customer;
use Illuminate\Http\Request;
use DB,Response;
use Illuminate\Support\Facades\Auth;

class cartController extends Controller
{
  public $customer;
  
  public function __construct()
  {
    $this->middleware(function ($request, $next) {
      $this->customer = new Customer();
      return $next($request);
    });
  }
  
    public function cartProcess(Request $request){
      $cartProcess = $this->customer->customerSaveCart($request->all());
      return $cartProcess;
    }
    
    public function cartData(Request $request){      
      $cart = $this->customer->customerCart();
      return $cart;
    }

    public function json_cartAll(Request $request){
      $data = $this->customer->customerCartDetail($request);
      return response()->json(compact("data"));
    }
    public function delete_cart(Request $request){
      return $this->customer->customerDeleteCart($request->all());
    }
}
