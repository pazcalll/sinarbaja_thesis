<?php

namespace App\Http\Controllers;

use App\GroupUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupUserController extends Controller {

    public function index() {
        return view('dashboard.group-user');
    }

    
}