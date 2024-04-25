<!DOCTYPE html>
<html>

<head>
    <title>TANDA TERIMA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
      width: 100%;
      border-collapse: collapse;
    }

    
    
        .container {
            width: 3508px;
            margin: 0 auto;
        }

        .info {
            margin-bottom: 10px;
            margin-top: 5px;
        }

        td{
            white-space: nowrap;
            max-width: 10px;
        }
        th, td {
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color:khaki;
        }
        
    </style>
</head>

<body>
    <div class="container">
        <div class="info">
            <p style="text-align: center; font-weight: bold;">RINCIAN SHARED FEE RSIA KENDANGSARI MERR</p>
        </div>
        <table class="table w-auto text-start">
            <tbody class="table-border-bottom-0">
                <tr style="white-space: nowrap;">
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>NRM</th>
                    <th>Dokter</th>
                    <th>Pasien</th>
                    <th>Diagnosa</th>
                    <th>Tindakan</th>
                    <th>Jumlah</th>
                    <th>Tarif</th>
                    <th>BHP</th>
                    <th>Sharing</th>
                    <th>RSIA Fee</th>
                    <th>THP Dokter</th>
                </tr>
                @if (count($rekapFeeRSIA) == 0)
                <tr>
                    <td class="text-center" colspan="8">Tidak ada data rekapan Fee RSIA yang terdata</td>
                </tr>
                @else
                @foreach ($rekapFeeRSIA as $rekapFee)
                <tr  style="white-space: nowrap;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $rekapFee->tanggal }}</td>
                    <td>{{ $rekapFee->nomorRekamMedis }}</td>
                    <td>{{ $rekapFee->dokter}}</td>
                    <td>{{ $rekapFee->pasien}}</td>
                    <td>{{ $rekapFee->diagnosa}}</td>
                    <td>{{ $rekapFee->tindakan}}</td>
                    <td>{{ $rekapFee->jumlahTindakan}}</td>
                    <td>{{ App\Http\Controllers\JenisTindakanController::rupiah($rekapFee->tarif)}}</td>
                    <td>{{ App\Http\Controllers\JenisTindakanController::rupiah($rekapFee->BHP)}}</td>
                    <td>{{ App\Http\Controllers\JenisTindakanController::rupiah($rekapFee->sharing)}}</td>
                    <td>{{ App\Http\Controllers\JenisTindakanController::rupiah($rekapFee->RSIAFee)}}</td>
                    <td>{{ App\Http\Controllers\JenisTindakanController::rupiah($rekapFee->THPDRG)}}</td>
                </tr>
                @endforeach
                @foreach ($total as $t)
                <tr  style="white-space: nowrap;">
                    @for ($i = 0; $i <= 7; $i++)
                        <td></td>
                    @endfor
                    <td><strong>{{ App\Http\Controllers\JenisTindakanController::rupiah($t->total)}}</strong></td>
                    <td><strong>{{ App\Http\Controllers\JenisTindakanController::rupiah($t->biaya_bahan)}}</strong></td>
                    <td><strong>{{ App\Http\Controllers\JenisTindakanController::rupiah($t->sharing)}}</strong></td>
                    <td><strong>{{ App\Http\Controllers\JenisTindakanController::rupiah($t->rsia_fee)}}</strong></td>
                    <td><strong>{{ App\Http\Controllers\JenisTindakanController::rupiah($t->dokter_fee)}}</strong></td>
                </tr>
                @endforeach
                @endif
        </table>
    </div>
</body>

</html>