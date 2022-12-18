<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Cookie;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
      // dd($request->cookie('login'));

        // $this->middleware('guest')->except('logout');
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);
        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        if (Auth::user()->id_group === '1') {
            return redirect(url('/dashboard'));
        } elseif(Auth::user()->group_id === 'DRIVER') {
            return redirect(url('/order'));
        } else {
            return redirect(url('/'));
        }
    }
}
