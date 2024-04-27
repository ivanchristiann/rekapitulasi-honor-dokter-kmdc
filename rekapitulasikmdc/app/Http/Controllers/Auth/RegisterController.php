<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Dokter;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = RouteServiceProvider::HOME;

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
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $role = ($data['role'] === 'admin') ? 'admin' : 'dokter';
        // $validator = Validator::make($data), [
        //     'password' => 'required|min:8',
        // ], [
        //     'password.required' => 'Password harus diisi.',
        //     'password.min' => 'Password minimal harus terdiri dari 8 karakter.',
        // ]);

        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }

        $user = new User();
        $user->name = $data['nama'];
        $user->email = $data['email'];
        $user->username = $data['username'];
        $user->nomor_telp = $data['nomorTelepon'];
        $user->password = Hash::make($data['password']);
        $user->role = $role;
        $user->created_at = now("Asia/Bangkok");
        $user->updated_at = now("Asia/Bangkok");
        $user->save();

        if ($role == 'admin') {
            $admin = new Admin();
            $admin->nama_lengkap = $data['nama'];
            $admin->status = "0";
            $admin->user_id = $user->id;
            $admin->created_at = now("Asia/Bangkok");
            $admin->updated_at = now("Asia/Bangkok");
            $user->admin()->save($admin);
        } else {
            $dokter = new Dokter();
            $dokter->kode_nama_dokter = $data['namaSingkatan'];
            $dokter->nama_lengkap = $data['nama'];
            $dokter->status = "0";
            $dokter->user_id = $user->id;
            $dokter->created_at = now("Asia/Bangkok");
            $dokter->updated_at = now("Asia/Bangkok");
            $user->dokter()->save($dokter);
        }
        return redirect()->route('login')->with('status', 'New Admin  ' .  $admin->nama_lengkap . ' is already inserted');
        return $user;
    }
}
