<?php

namespace App\Http\Controllers;

use App\Models\JenisTindakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Integer;
use Illuminate\Support\Facades\Auth;


class JenisTindakanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         if (Auth::check()) {
            $jenisTindakanAktif = JenisTindakan::all()->where('status', '1');
            $jenisTindakanNonAktif = JenisTindakan::all()->where('status', '0');
            $persentaseFee = DB::table('fees')->orderBy('id', 'desc')->first();
        return view('jenisTindakan.index', compact('jenisTindakanAktif', 'jenisTindakanNonAktif', 'persentaseFee'));
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
            return view('jenisTindakan.create');
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
        $jenisTindakan = new jenisTindakan();
        $jenisTindakan->nama_tindakan = $request->get('nama_tindakan');
        $jenisTindakan->biaya_tindakan = $request->get('biaya_tindakan');
        $jenisTindakan->biaya_bahan = $request->get('biaya_bahan');

        $jenisTindakan->created_at = now("Asia/Bangkok");
        $jenisTindakan->updated_at = now("Asia/Bangkok");

        $jenisTindakan->save();
        return redirect()->route('jenistindakan.index')->with('status', 'New Jenis Tindakan ' .  $jenisTindakan->nama_tindakan . ' is already inserted');
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
            $jenisTindakan = JenisTindakan::find($id);
            return view('jenisTindakan.edit', compact('jenisTindakan'));
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
        $jenisTindakan = jenisTindakan::find($id);

        $jenisTindakan->nama_tindakan = $request->get('nama_tindakan');
        $jenisTindakan->biaya_tindakan = $request->get('biaya_tindakan');
        $jenisTindakan->biaya_bahan = $request->get('biaya_bahan');

        $jenisTindakan->updated_at = now("Asia/Bangkok");

        $jenisTindakan->save();
        return redirect()->route('jenistindakan.index')->with('status', 'Jenis Tindakan ' .  $jenisTindakan->nama_tindakan . ' is already updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(jenisTindakan $jenisTindakan)
    {
        $jenisTindakan = JenisTindakan::find($jenisTindakan->idJenis_tindakan);
        $jenisTindakan->delete();
        return redirect()->route('jenistindakan.index')->with('success', 'Jenis tindakan ' .  $jenisTindakan->nama_tindakan . ' is already deleted');
    }

    public static function rupiah($angka)
    {
        $hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
        return $hasil_rupiah;
    }

    public function nonaktifkan(Request $request)
    {
        $data = JenisTindakan::find($request->get('id'));
        $data->status = '0';
        $data->save();
        return response()->json(array('status' => 'success'), 200);
    }

    public function aktifkan(Request $request)
    {
        $data = JenisTindakan::find($request->get('id'));
        $data->status = '1';
        $data->save();
        return response()->json(array('status' => 'success'), 200);
    }

    public function ubahpersentase(Request $request)
    {
        $feeRSIA = $request->get('feersia');
        $feeDokter = $request->get('feedokter');
        if ($feeDokter + $feeRSIA == 100) {
            $query = "INSERT INTO fees (`feedokter`, `feersia`,`tahun`) VALUES (?,?,?)";
            DB::insert($query, [$feeDokter, $feeRSIA, date('Y')]);
            return redirect()->route('jenistindakan.index')->with('status', 'Fee is updated');
        } else {
            return redirect()->route('jenistindakan.index')->with('failed', 'Fee harus berjumlah 100%');
        }
    }

    public function getBiaya(int $id)
    {
        $biaya = JenisTindakan::find($id, ['biaya_tindakan', 'biaya_bahan']);
        return $biaya;
    }
}
