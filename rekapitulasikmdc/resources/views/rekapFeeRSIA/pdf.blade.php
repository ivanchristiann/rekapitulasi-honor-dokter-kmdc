<!DOCTYPE html>
<html>
<head>
    <title>TANDA TERIMA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 900px;
            margin: 0 auto;
            padding: 15px;
            border: 1px solid black;
        }
        .header {
            text-align: left;
        }
        .header p{
            margin-bottom: -10px;
        }
        .info {
            margin-bottom: 20px;
            margin-top: 30px;
        }
        .info p{
            margin-bottom: -5px;
        }
        .table {
            width: 100%;
        }
        .tableSignature {
            width: 90%;
        }
        .table th, .table td {
            padding: 3px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <p style="font-weight: bold;">KIDDIES & MOMS DENTAL CARE (KMDC)</p>
            <p style="font-weight: bold;">RSIA KENDANGSARI MERR SURABAYA</p>
        </div>
        <div class="info">
            <p style="text-align: center; font-weight: bold;">TANDA TERIMA SHARED FEE RSIA KENDANGSARI MERR</p>
            
        </div>
        <table class="table">
            <tr>
                <th>No Kwitansi</th>
                <th style="width: 5px;">:</th>
                
                <th style="font-weight: normal;">
                    KMDC-{{ $bulan === '-' ? "(Januari-Desember)" : Str::upper(date('M', mktime(0, 0, 0, $bulan, 1))) }}-{{ $tahun }}-RSIA
                </th>
            </tr>
            <tr>
                <th>Tanggal</th>
                <th>:</th>
                <th style="font-weight: normal;">@php echo date("d F Y"); @endphp</th>
            </tr>
            <tr>
                <th>Penerima</th>
                <th>:</th>
                <th style="font-weight: normal;">{{$namaPenerima}}</th>
            </tr>
            <tr>
                <th>Nominal Total (Rp.)</th>
                <th>:</th>
                <td style="font-weight: bold; color:red;">{{App\Http\Controllers\JenisTindakanController::rupiah($totalFee)}}</td>

            </tr>
            <tr>
                <th>Terbilang</th>
                <th>:</th>
                <td>{{$terbilang}}</td>
            </tr>
        </table>
        <div style="margin-top: 30px;">
            <table class="tableSignature">
                <tr>
                    <th style="text-align: center; font-weight: normal;">Yang Membayarkan,</th>
                    <th style="text-align: center; font-weight: normal;">Penerima,</th>
                </tr>
                <tr><th style="height: 70px;"></th></tr>
                <tr>
                    <th style="text-align: center; font-weight: normal;">({{$namaPemimpin}})</th>
                    <th style="text-align: center; font-weight: normal;">({{$namaPenerima}})</th>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
