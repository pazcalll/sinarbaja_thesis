<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Exports\BarangExport;
use App\Exports\StockExport;
use App\Imports\BarangImport;
use App\Imports\StokImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Excel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ThesisItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        //
        return view('adminThesis.itemTable');
    }
    
    public function import_excel(Request $request)
    {
        return $this->admin->import_excel($request);
    }

    public function export_excel()
    {
        return $this->admin->export_excel();
    }
    
    public function listData()
    {
        return $this->admin->listData();
    }

    public function list_harga($id){
        return response($this->admin->list_harga($id), 200);
    }

    public function stockTable()
    {
        return view('adminThesis.itemStock');
    }

    public function allItemStock(){
        return response()->json($this->admin->allItemStock());
    }

    public function importStock(Request $request)
    {
        return $this->admin->importStock($request->all());
    }

    public function exportStock()
    {
        return $this->admin->exportStock();
    }

    public function truncateStock(Request $request)
    {
        return $this->admin->truncateStock();
    }
}
