<?php

namespace App\Http\Controllers\Auth;

use App\Customer;
use App\Agent;
use App\GroupUser;
use App\HargaProdukGroup;
use App\HargaProdukUser;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // dd($data);
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string'],
        ], [
            'email.unique' => 'Email sudah ada!',
            'email.required' => 'email wajib di isi!',
            'name.required' => 'nama wajib di isi!',
            'password.required' => 'password wajib di isi!'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $create = null;
        $group_id = $data['group_id'] ?? 'LEVEL 1';
        $id_group = GroupUser::where('group_name', $group_id)->get('id')[0]['id'];
        // dd($id_group);
        // dd(GroupUser::where('group_name', $id_group)->get('id')[0]['id']);
        $submission = [
            'name' => $data['name'],
            'email' => $data['email'],
            'address' => $data['address'],
            'no_handphone' => $data['no_handphone'],
            'password' => Hash::make($data['password']),
            'group_id' => $data['group_id'] ?? 'LEVEL 1',
            'id_group' => $id_group
        ];
        $create = User::create($submission);
        $user = User::where('name', $data['name'])->where('email', $data['email'])->where('no_handphone', $data['no_handphone'])->get('id');
        $idGroup = User::where('name', $data['name'])->where('email', $data['email'])->where('no_handphone', $data['no_handphone'])->get('id_group');
        $pricePerGroup = HargaProdukGroup::where('id_group', $idGroup[0]->id_group)->groupBy('id_group', 'id_product', 'harga_group')->get(['id_group', 'id_product', 'harga_group']);
        // $insertUserPrices = HargaProdukUser::create()
        $insertions = [];
        foreach ($pricePerGroup as $key => $value) {
            $insertions[$key]['id_user'] = $user[0]->id;
            $insertions[$key]['id_group'] = $value['id_group'];
            $insertions[$key]['id_product'] = $value['id_product'];
            $insertions[$key]['harga_user'] = $value['harga_group'];
        }
        $insert = HargaProdukUser::insert($insertions);

        // dd($data);
        // return view('catalogue/index');
        return $create;
    }
}
