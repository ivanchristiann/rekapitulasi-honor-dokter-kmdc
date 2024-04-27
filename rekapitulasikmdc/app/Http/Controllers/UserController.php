<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Dokter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('registerUser');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = ($request['role'] === 'admin') ? 'admin' : 'dokter';
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8',
            'confirmPassword' => 'required|same:password',
        ], [
            'password.required' => 'Password harus diisi.',
            'confirmPassword.required' => 'Confirm Password harus diisi.',
            'confirmPassword.same' => 'Konfirmasi Password harus sama dengan Password.',
            'password.min' => 'Password minimal harus terdiri dari 8 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $user = new User();
        $user->name = $request['nama'];
        $user->email = $request['email'];
        $user->username = $request['username'];
        $user->nomor_telp = $request['nomorTelepon'];
        $user->password = Hash::make($request['password']);
        $user->role = $role;
        $user->created_at = now("Asia/Bangkok");
        $user->updated_at = now("Asia/Bangkok");
        $user->save();

        if ($role == 'admin') {
            $admin = new Admin();
            $admin->nama_lengkap = $request['nama'];
            $admin->status = "0";
            $admin->user_id = $user->id;
            $admin->created_at = now("Asia/Bangkok");
            $admin->updated_at = now("Asia/Bangkok");
            $user->admin()->save($admin);
        } else {
            $dokter = new Dokter();
            $dokter->kode_nama_dokter = $request['namaSingkatan'];
            $dokter->nama_lengkap = $request['nama'];
            $dokter->status = "0";
            $dokter->user_id = $user->id;
            $dokter->created_at = now("Asia/Bangkok");
            $dokter->updated_at = now("Asia/Bangkok");
            $user->dokter()->save($dokter);
        }
        return redirect()->route('login')->with('showModal', true);
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
        //
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
        //
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
}
