<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $adminAktif = Admin::join('users', 'admins.user_id', '=', 'users.id')
                ->select('admins.*', 'users.*')
                ->where('status', '1')
                ->where('users.verification', 'verification')
                ->get();
            $adminNonaktif = Admin::join('users', 'admins.user_id', '=', 'users.id')
                ->select('admins.*', 'users.*')
                ->where('status', '0')
                ->where('users.verification', 'verification')
                ->get();
        } else {
            return redirect('/login');
        }

        return view('admin.index', compact('adminAktif', 'adminNonaktif'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
            return view('admin.create');
        } else {
            return redirect('/login');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8',
        ], [
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal harus terdiri dari 8 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $user = new User();
        $user->name = $request->get('namaAdmin');
        $user->email = $request->get('emailAdmin');
        $user->nomor_telp = $request->get('nomorTeleponAdmin');
        $user->username = $request->get('usernameAdmin');
        $user->password = Hash::make($request->get('password'));
        $user->role = "admin";
        $user->created_at = now("Asia/Bangkok");
        $user->updated_at = now("Asia/Bangkok");
        $user->save();

        $admin = new Admin();
        $admin->nama_lengkap = $request->get('namaAdmin');
        $admin->status = "1";
        $admin->user_id = $user->id;
        $admin->created_at = now("Asia/Bangkok");
        $admin->updated_at = now("Asia/Bangkok");
        $user->admin()->save($admin);

        return redirect()->route('admin.index')->with('status', 'New Admin  ' .  $admin->nama_lengkap . ' is already inserted');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::check()) {
            $admin = Admin::where('user_id', $id)->first();
            $user = User::where('id', $id)->first();
            return view('admin.edit', compact('admin', 'user'));
        }else{
            return redirect('/login');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $admin = Admin::find($id);
        $user = User::find($admin->user_id);

        $user->name = $request->get('namaAdmin');
        $user->email = $request->get('emailAdmin');
        $user->nomor_telp = $request->get('nomorTeleponAdmin');
        $user->username = $request->get('usernameAdmin');
        $user->updated_at = now("Asia/Bangkok");
        $user->save();

        $admin->nama_lengkap = $request->get('namaAdmin');
        $admin->created_at = now("Asia/Bangkok");
        $admin->updated_at = now("Asia/Bangkok");
        $user->dokter()->save($admin);

        return redirect()->route('admin.index')->with('status', 'Admin ' .  $admin->nama_lengkap . ' is already updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function nonaktifkan(Request $request)
    {
        $admin = Admin::where('user_id', $request->get('id'))->first();
        $admin->status = '0';
        $admin->save();
        return response()->json(array('status' => 'success'), 200);
    }

    public function aktifkan(Request $request)
    {
        $admin = Admin::where('user_id', $request->get('id'))->first();
        $admin->status = '1';
        $admin->save();
        return response()->json(array('status' => 'success'), 200);
    }

    public function ubahPassword()
    {
        return view('ubahPassword');
    }
    public function newPassword(Request $request)
    {
        if ((Hash::check($request->oldPassword, auth()->user()->password)) == false) {
            return back()->with("error", "Old Password Doesn't match!");
        }

        $validator = Validator::make($request->all(), [
            'newPassword' => 'required|min:8',
            'KonfNewPassword' => ['same:newPassword'],
        ], [
            'newPassword.required' => 'Password harus diisi.',
            'KonfNewPassword.required' => 'Confirm Password harus diisi.',

            'newPassword.min' => 'Password minimal harus terdiri dari 8 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            User::find(auth()->user()->id)->update(['password' => Hash::make($request->newPassword)]);
            return redirect('/');
        }
    }
    public function saveData(Request $request)
    {
        $id = $request->get('id');
        $name = $request->get('name');
        $value = $request->get('value');
        $setting = Setting::find($id);

        if ($name == 'name') {
            $setting->name = $value;
        } else {
            $setting->value = $value;
        }

        $setting->save();
        // return response()->json(array(
        //     'status'=>'ok',
        //     'msg'=>'Updated Data Done'
        //     ),200);
        // }
    }
    public function setting()
    {
        if (Auth::check()) {
            $settings = Setting::all();
            return view('setting', compact('settings'));
        } else {
            return redirect('/login');
        }
        
    }
}
