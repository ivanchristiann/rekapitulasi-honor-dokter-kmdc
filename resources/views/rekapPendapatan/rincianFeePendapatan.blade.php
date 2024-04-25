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
            <p style="text-align: center; font-weight: bold;">RINCIAN SHARED FEE PENDAPATAN</p>
        </div>
        <table class="table w-auto text-start">
            <tbody class="table-border-bottom-0">
                <tr style="white-space: nowrap;">
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Nomor Rekam Medis</th>
                    <th>Dokter Gigi</th>
                    <th>Nama Pasien</th>
                    <th>Diagnosa</th>
                    <th>Tindakan</th>
                    <th>Jumlah Tindakan</th>
                    <th>Total</th>
                    <th>BHP</th>
                    <th>Sharing</th>
                    <th>RSIA FEE</th>
                    <th>Dokter FEE</th>
                </tr>
            </thead>
            <tbody>
                @if (count($dataTindakan) == 0)
                <tr>
                    <td class="text-center" colspan="8">Tidak ada data rekap pendapatan yang terdata</td>
                </tr>
                @else
                @foreach ($dataTindakan as $dt)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $dt->tanggal_kunjungan }}</td>
                    <td>{{ $dt->nomorRekamMedis }}</td>
                    <td>{{ $dt->namaDokter }}</td>
                    <td>{{ $dt->nama_lengkap }}</td>
                    <td>{{ $dt->kode_diagnosa }}</td>
                    <td>{{ $dt->nama_tindakan }}</td>
                    <td>{{ $dt->jumlahTindakan}}</td>
                    <td>{{ App\Http\Controllers\JenisTindakanController::rupiah($dt->total) }}</td>
                    <td>{{ App\Http\Controllers\JenisTindakanController::rupiah($dt->biaya_bahan) }}</td>
                    <td>{{ App\Http\Controllers\JenisTindakanController::rupiah($dt->Sharing) }}</td>
                    <td>{{ App\Http\Controllers\JenisTindakanController::rupiah($dt->FeeRSIA) }}</td>
                    <td>{{ App\Http\Controllers\JenisTindakanController::rupiah($dt->FeeDokter) }}</td>
                </tr>
                @endforeach
    
                @foreach ($total as $t)
                <tr style="white-space: nowrap;">
                    @for ($i = 0; $i < 8; $i++)
                        <td></td>
                    @endfor
                    <td><strong>{{ App\Http\Controllers\JenisTindakanController::rupiah($t->total)}}</strong></td>
                    <td><strong>{{ App\Http\Controllers\JenisTindakanController::rupiah($t->biaya_bahan)}}</strong></td>
                    <td><strong>{{ App\Http\Controllers\JenisTindakanController::rupiah($t->sharing)}}</strong></td>
                    <td><strong>{{ App\Http\Controllers\JenisTindakanController::rupiah($t->rsia_fee)}}</strong></td>
                    <td style=" color: red;"><strong>{{ App\Http\Controllers\JenisTindakanController::rupiah($t->dokter_fee)}}</strong></td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</body>

</html>