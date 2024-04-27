<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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

    public function redirectTo()
    {
        $role = Auth::user()->role;
        return '/';
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    protected function authenticated(Request $request, $user)
    {
        if ($user->verification === 'Process') {
            $request->session()->flash('status', 'proses');
            $this->guard()->logout();
            return redirect()->route('login');
        } elseif ($user->verification === 'Failed') {
            $request->session()->flash('status', 'failed');
            $this->guard()->logout();
            return redirect()->route('login');
        }
    
        $user->forceFill([
            'last_login' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
        ])->save();
    
        $request->session()->flash('status', 'verif');
    
        return redirect('/');
    }
}
