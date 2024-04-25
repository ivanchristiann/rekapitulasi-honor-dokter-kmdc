<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DokterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         if (Auth::check()) {
        $dokterAktif = Dokter::join('users', 'dokters.user_id', '=', 'users.id')
            ->select('dokters.*', 'users.*')
            ->where('status', '1')
            ->where('users.verification', 'verification')
            ->get();
        $dokterNonaktif = Dokter::join('users', 'dokters.user_id', '=', 'users.id')
            ->select('dokters.*', 'users.*')
            ->where('status', '0')
            ->where('users.verification', 'verification')
            ->get();
        return view('dokter.index', compact('dokterAktif', 'dokterNonaktif'));
        } else {
            return redirect('/login');
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
            return view('dokter.create');
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
        $user->name = $request->get('namaDokter');
        $user->email = $request->get('emailDokter');
        $user->username = $request->get('usernameDokter');
        $user->password = Hash::make($request->get('password'));
        $user->nomor_telp = $request->get('nomorTeleponDokter');
        $user->role = "dokter";
        $user->created_at = now("Asia/Bangkok");
        $user->updated_at = now("Asia/Bangkok");
        $user->save();

        $dokter = new Dokter();
        $dokter->kode_nama_dokter = $request->get('singkatan');
        $dokter->nama_lengkap = $request->get('namaDokter');
        $dokter->status = "1";
        $dokter->user_id = $user->id;
        $dokter->created_at = now("Asia/Bangkok");
        $dokter->updated_at = now("Asia/Bangkok");
        $user->dokter()->save($dokter);

        return redirect()->route('dokter.index')->with('status', 'New Dokter  ' .  $dokter->nama_lengkap . ' is already inserted');
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
            $dokter = Dokter::where('user_id', $id)->first();
            $user = User::find($id);
            return view('dokter.edit', compact('dokter', 'user'));
        } else {
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
        $dokter = Dokter::where('user_id', $id)->first();
        $user = User::find($id);

        $user->name = $request->get('namaDokter');
        $user->email = $request->get('emailDokter');
        $user->username = $request->get('usernameDokter');
        $user->nomor_telp = $request->get('nomorTeleponDokter');
        $user->updated_at = now("Asia/Bangkok");
        $user->save();

        $dokter->kode_nama_dokter = $request->get('singkatan');
        $dokter->nama_lengkap = $request->get('namaDokter');
        $dokter->created_at = now("Asia/Bangkok");
        $dokter->updated_at = now("Asia/Bangkok");
        $user->dokter()->save($dokter);

        return redirect()->route('dokter.index')->with('status', 'Dokter ' .  $dokter->nama_lengkap . ' is already updated');
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
        $data = Dokter::where('user_id', $request->get('id'))->first();
        $data->status = '0';
        $data->save();
        return response()->json(array('status' => 'success'), 200);
    }

    public function aktifkan(Request $request)
    {
        $data = Dokter::where('user_id', $request->get('id'))->first();
        $data->status = '1';
        $data->save();
        return response()->json(array('status' => 'success'), 200);
    }
}
