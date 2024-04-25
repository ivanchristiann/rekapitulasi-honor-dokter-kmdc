<?php

namespace App\Http\Controllers;

use App\Models\JenisTindakan;
use App\Models\JenisTindakanPasien;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;
use Mpdf\Mpdf;
use PhpParser\Node\Stmt\Echo_;
use Illuminate\Support\Facades\Auth;


class RekapFeeRSIAController extends Controller
{
    public function getDataRekapRSIA($bulan, $tahun)
    {
        if (Auth::check()) {
             $rekapFeeRSIA = JenisTindakanPasien::select(
            DB::raw("MONTHNAME(tanggal_kunjungan) as bulan"),
            DB::raw("YEAR(tanggal_kunjungan) as tahun"),
            DB::raw("DATE_FORMAT(tanggal_kunjungan, '%d %M %Y') as tanggal"),
            "d.kode_nama_dokter as dokter",
            "p.nama_lengkap as pasien",
            "dig.kode_diagnosa as diagnosa",
            "jt.nama_tindakan as tindakan",
            "jenis_tindakan_pasiens.jumlah_tindakan as jumlahTindakan",
            "jenis_tindakan_pasiens.nomor_rekam_medis as nomorRekamMedis",
            DB::raw("jenis_tindakan_pasiens.biaya_tindakan as tarif"),
            "jenis_tindakan_pasiens.biaya_bahan as BHP",
            DB::raw("CEILING(jenis_tindakan_pasiens.biaya_tindakan - jenis_tindakan_pasiens.biaya_bahan) AS sharing",),
            DB::raw("((feersia/100) * (jenis_tindakan_pasiens.biaya_tindakan - jenis_tindakan_pasiens.biaya_bahan)) as RSIAFee"),
            DB::raw("((feedokter/100) * (jenis_tindakan_pasiens.biaya_tindakan - jenis_tindakan_pasiens.biaya_bahan)) as THPDRG")
        )
            ->join('jenis_tindakans as jt', 'jt.id', '=', 'jenis_tindakan_pasiens.jenis_tindakan_id')
            ->join('dokters as d', 'd.id', '=', 'jenis_tindakan_pasiens.dokter_id')
            ->join('diagnosas as dig', 'dig.id', '=', 'jenis_tindakan_pasiens.diagnosa_id')
            ->join('pasiens as p', 'p.id', '=', 'jenis_tindakan_pasiens.pasien_id')
            ->join('fees AS f', 'f.id', '=', 'jenis_tindakan_pasiens.fees_id')
            ->when($bulan != '-', function ($query) use ($bulan) {
                $query->whereRaw("MONTH(tanggal_kunjungan) = $bulan");
            })
            ->whereRaw("YEAR(tanggal_kunjungan) = $tahun")
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
            ->when($bulan != '-', function ($query) use ($bulan) {
                $query->whereRaw("MONTH(tanggal_kunjungan) = $bulan");
            })
            ->whereRaw("YEAR(tanggal_kunjungan) = $tahun")
            ->get();
        return ['rekapFeeRSIA' => $rekapFeeRSIA, 'total' => $total];
        } else {
            return redirect('/login');
        }
     
    }
    public function index($bulan = null, $tahun = null)
    {
         if (Auth::check()) {
            if ($bulan == null && $tahun == null) {
                $bulan = Carbon::now()->month;
                $tahun = Carbon::now()->year;
            }
    
            $dataRekap = $this->getDataRekapRSIA($bulan, $tahun);
            $rekapFeeRSIA = $dataRekap['rekapFeeRSIA'];
            $total = $dataRekap['total'];
    
            return view('rekapFeeRSIA.index', compact('rekapFeeRSIA', 'total'));
        } else {
            return redirect('/login');
        }
    }

    public function printPdf(Request $request)
    {
        $totalFee = $request->rekapFeeRSIA;
        $terbilang = $this->terbilang($totalFee);
        $bulan = $request->bulanFeeRSIA;
        $tahun = $request->tahunFeeRSIA;
        if ($bulan == null && $tahun == null) {
            $bulan = Carbon::now()->month;
            $tahun = Carbon::now()->year;
        }
        $namaPemimpin = DB::table('settings')->where('name', 'Nama pemimpin')->value('value');
        $namaPenerima = DB::table('settings')->where('name', 'Nama penerima Fee Rumah Sakit')->value('value');

        $mpdf = new Mpdf();

        $kuitansi = view('rekapFeeRSIA.pdf', compact('totalFee', 'terbilang', 'bulan', 'tahun', 'namaPemimpin', 'namaPenerima'))->render();
        $mpdf->WriteHTML($kuitansi);

        $dataRekap = $this->getDataRekapRSIA($bulan, $tahun);
        $rekapFeeRSIA = $dataRekap['rekapFeeRSIA'];
        $total = $dataRekap['total'];

        $mpdf->AddPage('L', '', '', '', '', 5, 5, 5, 5);
        $mpdf->SetAutoPageBreak(true, 10);
        $rincian = view('rekapFeeRSIA.rincianFeeRsia', compact('rekapFeeRSIA', 'total'))->render();
        $mpdf->WriteHTML($rincian);

        $tanggal = date("dmY");
        $mpdf->Output($tanggal . ' - KwitansiRSIA - ' . date('F', mktime(0, 0, 0, $bulan, 1)) . '.pdf', 'D');
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
