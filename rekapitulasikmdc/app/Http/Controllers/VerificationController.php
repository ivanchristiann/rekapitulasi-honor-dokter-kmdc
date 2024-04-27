<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         if (Auth::check()) {
             $dokterVerification = Dokter::join('users', 'dokters.user_id', '=', 'users.id')
                ->select('dokters.*', 'users.*')
                ->where('role', 'dokter')
                ->where('users.verification', 'Process')
                ->get();
            $adminVerification = Admin::join('users', 'admins.user_id', '=', 'users.id')
                ->select('admins.*', 'users.*')
                ->where('role', 'admin')
                ->where('users.verification', 'Process')
                ->get();
            return view('verification.index', compact('dokterVerification', 'adminVerification'));
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    public function terima(Request $request)
    {
        $data = User::where('id', $request->get('id'))->first();
        $data->verification = 'verification';
        $data->save();
        
        if($data->role === 'dokter'){
            $dokter = Dokter::where('user_id', $request->get('id'))->first();
            $dokter->status = '1';
            $dokter->save();
        }else{
            $admin = Admin::where('user_id', $request->get('id'))->first();
            $admin->status = '1';
            $admin->save();
        }
        return response()->json(array('status' => 'success'), 200);
    }

    public function tolak(Request $request)
    {
        $data = User::where('id', $request->get('id'))->first();
        $data->verification = 'Failed';
        $data->save();
        return response()->json(array('status' => 'success'), 200);
    }
}
