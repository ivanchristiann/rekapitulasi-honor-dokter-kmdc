<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
@extends('layout.sneat')

@section('menu')
<div class="portlet-title">
    <div style="display: inline-block; margin: 15px; font-size: 25px; font-weight: bold;">
        List Rekapan Fee RSIA
    </div>
</div>
@endsection

@section('content')

 @if (session('status'))
<div class="alert alert-success">{{session('status')}}</div>
@endif

<div>
    <div style="float: left;">
        <label for="bulanan" style="float: left; margin-top: 7px; margin-right: 7px;">Bulan:</label>
        <select class="form-select" aria-label="Default select example" name="bulan" id="bulan" style="width: 150px; margin-bottom: 10px;">
        <?php
        $selected = false;
        echo "<option value='-'" . (request()->segment(2)  == '-' ? ' selected' : '') . ">All</option>";
        for ($month = 1; $month <= 12; $month++) {
            if ($month == request()->segment(2) || ($month == date('m') && !$selected && request()->segment(2)  != '-')) {
                echo "<option value=\"$month\" selected>" . date('F', mktime(0, 0, 0, $month, 1)) . "</option>";
                $selected = true;
            } 
            else{
                echo "<option value=\"$month\">" . date('F', mktime(0, 0, 0, $month, 1)). "</option>";
            }
        }
        ?>
        </select>
    </div>

    <div style="float: left;">
        <label for="tahun" style="float: left; margin-top: 7px; margin-right: 7px;">Tahun:</label>
        <select class="form-select" aria-label="Default select example" name="tahun" id="tahun" style="width: 150px; margin-bottom: 10px;">
        <?php
        $selected = false;
        for ($year = 2023; $year <= date('Y'); $year++) {
            // $optionValue = sprintf('%02d-%04d', $month, $year);
            if (($year == request()->segment(3)) || ($year == date('Y') && !$selected)) {
                echo "<option value=\"$year\" selected>" . $year . "</option>";
                $selected = true;
            } else {
                echo "<option value=\"$year\">" . $year . "</option>";
            }
        }
        ?>
        </select>
    </div>

    <div style="float: right; margin-top: 7px; margin-right: 7px;">
        @if (count($rekapFeeRSIA) != 0 )
        <form action="{{url('print/feersia')}}">
            <input type="hidden" value="{{$total[0]->rsia_fee}}" name="rekapFeeRSIA">
            <input type="hidden" value="<?=request()->segment(2)?>" name="bulanFeeRSIA">
            <input type="hidden" value="<?=request()->segment(3)?>" name="tahunFeeRSIA">

            <button class="btn btn-info btn-sm"><i class="bx bx-printer"></i>Cetak</button>
        </form>
        @else
        <button class="btn btn-info btn-sm" id="showPopup"><i class="bx bx-printer"></i>Cetak</button>
        <script>
            document.getElementById('showPopup').addEventListener('click', function() {
                alert('Tidak ada data rekapan Fee RSIA yang terdata.');
            });
        </script>
        @endif
    </div>
</div>

<div class="table-responsive">
    <table class="table w-auto text-start">
        <tbody class="table-border-bottom-0">
            <tr style="white-space: nowrap;">
                <th>#</th>
                <th>Tanggal</th>
                <th>Nomor Rekam Medis</th>
                <th>Dokter</th>
                <th>Pasien</th>
                <th>Diagnosa</th>
                <th>Tindakan</th>
                <th>Jumlah Tindakan</th>
                <th>Tarif</th>
                <th>Bahan Habis Pakai (BHP)</th>
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
    <button id="downloadButton" class="btn btn-primary">Download CSV</button>
    <button id="downloadExcelButton" class="btn btn-success">Download Excel</button>
</div>

@endsection

@section('script')
<script>
    $("#bulan, #tahun").on('change', function() {
        var bulanSelected = $("#bulan").val();
        var tahunSelected = $("#tahun").val();
        window.location.href = '/rekapfeersia/' + bulanSelected + '/' + tahunSelected ;

    });
    
    document.getElementById('downloadButton').addEventListener('click', function() {
    var table = document.querySelector('.table');

    var bulanSelected = $("#bulan option:selected").text();
    var tahunSelected = $("#tahun").val();

    bulanSelected = bulanSelected.replace(/[^a-z0-9]/gi, '_');

    var fileName = 'data_' + bulanSelected + '_' + tahunSelected + '.csv';

    var csvHeader = [];
    var headers = table.querySelectorAll('th');
    headers.forEach(function(header) {
        csvHeader.push(header.textContent);
    });

    var csvData = [];
    var rows = table.querySelectorAll('tbody tr');
    rows.forEach(function(row) {
        var rowData = [];
        var cells = row.querySelectorAll('td');
        cells.forEach(function(cell) {
            rowData.push('"' + cell.textContent + '"');
        });
        csvData.push(rowData.join(','));
    });

    var csvContent = csvHeader.join(',') + '\n' + csvData.join('\n');

    var blob = new Blob([csvContent], { type: 'text/csv' });

    var url = window.URL.createObjectURL(blob);

    var a = document.createElement('a');
    a.href = url;
    a.download = fileName;
    document.body.appendChild(a);

    a.click();
    document.body.removeChild(a);
});


    document.getElementById('downloadExcelButton').addEventListener('click', function() {
    var table = document.querySelector('.table');

    var bulanSelected = $("#bulan option:selected").text();
    var tahunSelected = $("#tahun").val();

    bulanSelected = bulanSelected.replace(/[^a-z0-9]/gi, '_');

    var fileName = 'data_' + bulanSelected + '_' + tahunSelected + '.xlsx';

    var workbook = XLSX.utils.book_new();

    var wsData = [];

    var headerRow = [];
    var headers = table.querySelectorAll('th');
    headers.forEach(function(header) {
        headerRow.push(header.textContent);
    });
    wsData.push(headerRow);

    var rows = table.querySelectorAll('tbody tr');
    rows.forEach(function(row) {
        var rowData = [];
        var cells = row.querySelectorAll('td');
        cells.forEach(function(cell) {
            rowData.push(cell.textContent);
        });
        wsData.push(rowData);
    });

    var worksheet = XLSX.utils.aoa_to_sheet(wsData);
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Data Tabel');

    XLSX.writeFile(workbook, fileName);
    });
</script>

@endsection

