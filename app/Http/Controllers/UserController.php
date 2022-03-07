<?php

namespace App\Http\Controllers;

use App\User;
use App\Driver;
use App\GroupUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function authGetter(){
        if (Auth::user() != null) {
            $userOrigin = User::with(['group_user' => function($table){ 
                return $table->where('id', Auth::user()->id_group); 
            }])
            ->where('id', Auth::user()->id)
            ->first();
            return response($userOrigin, 200);
        }
        return response(['message' => 'please login'], 200);
	}

    public function allGroup(){
        $id = request('id');
        $group = GroupUser::whereIn('id', [2,3,4,5,6,7,8])->whereNotIn('id',function($query) use($id){
            $query->select('id_group')->from('harga_produk_group')->where('id_product', $id);
        })->get();
        return response($group, 200);
    }
    
    public function groupUser() {
        return view('dashboard.group-user');
    }

    public function accountUser() {
        $data['users'] = User::with('group_user')
            ->where('id_group', '!=', 0)
            ->where('password', '!=', '')
            // ->whereNotIn('id_group', [1])
            ->get();
        $data['group'] = DB::table('drivers')->pluck('status','id');
        // dd($data);
        return view('dashboard.account-user', compact('data'));
    }

    public function createUser(Request $request) {

        $users = User::create([
            'name' => $request->post()['name'],
            'address' => $request->post()['address'],
            'no_handphone' => $request->post()['no_handphone'],
            'email' => $request->post()['email'],
            'password' => Hash::make($request->post()['password']),
            'id_group' => $request->post()['group'],
            // 'id_profil' => $request->post()['profil'],
        ]);
        if($request->post()['group'] == 3) {
            $data = [
                'id' => $users->id,
                'status' => 'ACTIVE',
            ];
            $driver = Driver::create($data);
        }

        return back();
    }

    public function destroyUser($id) {
        
        User::find($id)->delete();
        return back()->with('success', 'User berhasil di hapus !');
    }
    
    public function createGroup(Request $request) {

        $group = $request->group_name;
        // dd($group);
        foreach ($group as $key)
            // $d = $key;
            // dd($d);
        $groups = GroupUser::create([
            'group_name' => $key,

        ]);
        return back();
    }
    public function destroyGroup($id) {

        GroupUser::find($id)->delete();
        return back()->with('success', 'Group berhasil di hapus !');
    }
    public function editUser(Request $request) {
// dd($request->all());
        User::where('id', $request->idEdit)->update([
            'name' => $request->nameEdit,
            'email' => $request->emailEdit,
            'address' => $request->addressEdit,
            'no_handphone' => $request->no_handphoneEdit,
            'id_group' => $request->id_groupEdit
            // 'id_profil' => $request->id_profilEdit
        ]);
        if($request->id_groupEdit == 3) {
            $data = [
                'status' => $request->status_group_driver
            ];
            $driver = Driver::where('id', $request->idEdit)->update($data);
        }

        return back();
        
    }
    public function editGroup(Request $request) {
        
        GroupUser::where('id', $request->idGroup)->update([
            'group_name' => $request->nameEdit
        ]);
    }

}
