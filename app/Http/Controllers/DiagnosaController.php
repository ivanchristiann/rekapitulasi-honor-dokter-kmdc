<?php

namespace App\Http\Controllers;

use App\Models\Diagnosa;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DiagnosaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if (Auth::check()) {
            $diagnosaNonAktif = Diagnosa::all()->where('status', '0');
            $diagnosaAktif = Diagnosa::all()->where('status', '1');

            return view('diagnosa.index', compact('diagnosaNonAktif', 'diagnosaAktif'));
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
            return view('diagnosa.create');
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
        try {
            $diagnosa = new Diagnosa();
            $diagnosa->kode_diagnosa = $request->get('kodeDiagnosa');
            $diagnosa->nama_diagnosa = $request->get('namaDiagnosa');
            $diagnosa->created_at = now("Asia/Bangkok");
            $diagnosa->updated_at = now("Asia/Bangkok");
            $diagnosa->save();

            return redirect()->route('diagnosa.index')->with('status', 'New Diagnosa ' .  $diagnosa->nama_diagnosa . ' is already inserted');
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode === 1062) {
                Session::flash('alert', 'Add new diagnosa gagal !!. Kode diagnosa sudah pernah digunakan');
                return redirect()->back();
            } else {
                echo "Terjadi kesalahan saat menyimpan data diagnosa.";
            }
        }
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
            $diagnosa = Diagnosa::find($id);
            return view('diagnosa.edit', compact('diagnosa'));
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
        $diagnosa = Diagnosa::find($id);
        $diagnosa->kode_diagnosa = $request->get('kodeDiagnosa');
        $diagnosa->nama_diagnosa = $request->get('namaDiagnosa');
        $diagnosa->updated_at = now("Asia/Bangkok");
        $diagnosa->save();

        return redirect()->route('diagnosa.index')->with('status', 'Diagnosa ' .  $diagnosa->nama_diagnosa . ' is already updated');
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
        $data = Diagnosa::find($request->get('id'));
        $data->status = '0';
        $data->save();
        return response()->json(array('status' => 'success'), 200);
    }

    public function aktifkan(Request $request)
    {
        $data = Diagnosa::find($request->get('id'));
        $data->status = '1';
        $data->save();
        return response()->json(array('status' => 'success'), 200);
    }

    public function CheckKodeDiagnosa($kode)
    {
        $diagnosa = Diagnosa::where('kode_diagnosa', $kode)->get();
        if ($diagnosa != null) {
            return true;
        } else {
            return false;
        }
    }
}
