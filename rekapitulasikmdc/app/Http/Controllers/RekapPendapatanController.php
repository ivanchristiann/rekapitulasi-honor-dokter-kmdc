<?php

namespace App\Http\Controllers;

use App\Models\Dokter as ModelsDokter;
use App\Models\JenisTindakanPasien;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Dokter;

class RekapPendapatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataRekapPendapatan($bulan, $tahun, $dokterSelected)
    {
         if (Auth::check()) {
            $dokter = ModelsDokter::select('id', 'nama_lengkap', 'kode_nama_dokter')->where('status', "1")->get();
            $dataTindakan = DB::table('jenis_tindakan_pasiens AS jtp')
                ->selectRaw("YEAR(jtp.tanggal_kunjungan) AS tahun, DATE_FORMAT(jtp.tanggal_kunjungan, '%d %M %Y') as 'tanggal_kunjungan', d.kode_nama_dokter AS 'namaDokter',
                p.nama_lengkap, dg.kode_diagnosa, jt.nama_tindakan, jtp.jumlah_tindakan as jumlahTindakan, jtp.biaya_tindakan AS total, jtp.biaya_bahan, 
                CEILING(jtp.biaya_tindakan - jtp.biaya_bahan) AS Sharing, ((feersia/100) * (jtp.biaya_tindakan - jtp.biaya_bahan)) as FeeRSIA,
                ((feedokter/100) * (jtp.biaya_tindakan - jtp.biaya_bahan)) as FeeDokter , nomor_rekam_medis as nomorRekamMedis")
                ->join('dokters AS d', 'd.id', '=', 'jtp.dokter_id')
                ->join('pasiens AS p', 'p.id', '=', 'jtp.pasien_id')
                ->join('diagnosas AS dg', 'dg.id', '=', 'jtp.diagnosa_id')
                ->join('jenis_tindakans AS jt', 'jt.id', '=', 'jtp.jenis_tindakan_id')
                ->join('fees', 'fees.id', '=', 'jtp.fees_id')
                ->where(function ($query) use ($dokterSelected) {
                    if ($dokterSelected != '-') {
                        $query->where('jtp.dokter_id', $dokterSelected);
                    }
                })
                ->when($bulan != '-', function ($query) use ($bulan) {
                    $query->whereRaw("MONTH(tanggal_kunjungan) = $bulan");
                })
                ->whereRaw("YEAR(jtp.tanggal_kunjungan) = $tahun")
                ->get();
    
    
            $total = JenisTindakanPasien::join('jenis_tindakans as jt', 'jt.id', '=', 'jenis_tindakan_pasiens.jenis_tindakan_id')
                ->join('fees', 'fees.id', '=', 'jenis_tindakan_pasiens.fees_id')
                ->select(
                    DB::raw('SUM(jenis_tindakan_pasiens.biaya_tindakan) AS total'),
                    DB::raw('SUM(jenis_tindakan_pasiens.biaya_bahan) AS biaya_bahan'),
                    DB::raw('SUM(jenis_tindakan_pasiens.biaya_tindakan - jenis_tindakan_pasiens.biaya_bahan) AS sharing'),
                    DB::raw('SUM((feersia/100) * (jenis_tindakan_pasiens.biaya_tindakan - jenis_tindakan_pasiens.biaya_bahan)) AS rsia_fee'),
                    DB::raw('SUM((feedokter/100) * (jenis_tindakan_pasiens.biaya_tindakan - jenis_tindakan_pasiens.biaya_bahan)) AS dokter_fee')
                )
                ->where(function ($query) use ($dokterSelected) {
                    if ($dokterSelected != '-') {
                        $query->where('dokter_id', $dokterSelected);
                    }
                })
                ->when($bulan != '-', function ($query) use ($bulan) {
                    $query->whereRaw("MONTH(tanggal_kunjungan) = $bulan");
                })
                ->whereRaw("YEAR(tanggal_kunjungan) = $tahun")
                ->get();
            return ['dataTindakan' => $dataTindakan, 'dokter' => $dokter, 'total' => $total];
        } else {
            return redirect('/login');
        }
    }
    public function index($bulan = null, $tahun = null, $dokterSelected = '-')
    {
         if (Auth::check()) {
            if ($bulan == null && $tahun == null) {
                $bulan = Carbon::now()->month;
                $tahun = Carbon::now()->year;
            }
    
            $dataRekap = $this->getDataRekapPendapatan($bulan, $tahun, $dokterSelected);
            $dataTindakan = $dataRekap['dataTindakan'];
            $dokter = $dataRekap['dokter'];
            $total = $dataRekap['total'];
    
            return view('rekapPendapatan.index', compact('dataTindakan', 'dokter', 'total'));
        } else {
            return redirect('/login');
        }
    }

    public function printPdf(Request $request)
    {
        $totalFee = $request->totalFeeDokter;
        $dokterSelect = DB::table('dokters')->where('id', $request->idDokter)->first();
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $terbilang = $this->terbilang($totalFee);

        $namaPemimpin = DB::table('settings')->where('name', 'Nama pemimpin')->value('value');

        $mpdf = new Mpdf();
        $html = view('rekapPendapatan.pdf', compact('totalFee', 'terbilang', 'dokterSelect', 'bulan', 'tahun', 'namaPemimpin'))->render();
        $mpdf->WriteHTML($html);
        $tanggal = date("dmY");

        $dataRekap = $this->getDataRekapPendapatan($bulan, $tahun, $request->idDokter);
        $dataTindakan = $dataRekap['dataTindakan'];
        $dokter = $dataRekap['dokter'];
        $total = $dataRekap['total'];

        $mpdf->AddPage('L', '', '', '', '', 5, 5, 5, 5);
        $mpdf->SetAutoPageBreak(true, 10);
        $rincian = view('rekapPendapatan.rincianFeePendapatan', compact('dataTindakan', 'dokter', 'total'))->render();
        $mpdf->WriteHTML($rincian);



        $mpdf->Output($tanggal . 'kwitansi' . Str::upper($dokterSelect->kode_nama_dokter) . '.pdf', 'D');
    }
    public function terbilang($angka)
    {
        $angka = abs($angka);
        $huruf = [
            "", "SATU", "DUA", "TIGA", "EMPAT", "LIMA", "ENAM", "TUJUH", "DELAPAN", "SEMBILAN", "SEPULUH", "SEBELAS"
        ];
        $terbilang = "";
        if ($angka < 12) {
            $terbilang = " " . $huruf[$angka];
        } elseif ($angka < 20) {
            $terbilang = $this->terbilang($angka - 10) . " BELAS";
        } elseif ($angka < 100) {
            $terbilang = $this->terbilang($angka / 10) . " PULUH" . $this->terbilang($angka % 10);
        } elseif ($angka < 200) {
            $terbilang = " SERATUS" . $this->terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $terbilang = $this->terbilang($angka / 100) . " RATUS" . $this->terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $terbilang = " SERIBU" . $this->terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $terbilang = $this->terbilang($angka / 1000) . " RIBU" . $this->terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $terbilang = $this->terbilang($angka / 1000000) . " JUTA" . $this->terbilang($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            $terbilang = $this->terbilang($angka / 1000000000) . " MILYAR" . $this->terbilang(fmod($angka, 1000000000));
        } elseif ($angka < 1000000000000000) {
            $terbilang = $this->terbilang($angka / 1000000000000) . " TRILIUN" . $this->terbilang(fmod($angka, 1000000000000));
        }
        return $terbilang;
    }
}
