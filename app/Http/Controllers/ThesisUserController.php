<?php

namespace App\Http\Controllers;

use App\Admin;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ThesisUserController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        $user = new User();
        $create = $user->create($req);
        return $create;
    }

    public function login(Request $req) {
        $user = new User();
        $login = $user->login($req);
        return $login;
    }

    public function logout (Request $req) {
        $user = new User();
        $logout = $user->logout($req);
        return $logout;
    }

    public function tableUser()
    {
        $users = new Admin();
        $users = $users->allUser();
        return view('adminThesis.userList', compact('users'));
    }

    public function destroy(Request $request)
    {
        //
        $users = new Admin();
        $delete = $users->destroyUser($request->id);
        return $delete;
    }
}
