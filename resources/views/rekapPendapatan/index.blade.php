<?php
use App\Models\Dokter;
?>
<style>
    tr{
        white-space: nowrap;
    }
</style>
@extends('layout.sneat')
@section('menu')
<div class="portlet-title">
    <div style="display: inline-block; margin: 15px; font-size: 25px; font-weight: bold;">
        List Rekap Pendapatan
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

    @if (str_contains(Auth::user()->role, 'superadmin') || str_contains(Auth::user()->role, 'admin'))
        <div style="float: left; margin-left: 10px;">
            <label for="bulanan" style="float: left; margin-top: 7px; margin-right: 7px;">Dokter:</label>
            <select class="form-select" aria-label="Default select example" name="dokter" id="dokter" style="width: 150px; margin-bottom: 10px;">
            <?php
                echo "<option value='-'" . (request()->segment(4) == '-' ? "selected>All</option>" : "'>All</option>");
                foreach ($dokter as $d) {
                    if($d->id == request()->segment(4)) {
                        echo "<option value=\"$d->id\" selected>$d->nama_lengkap</option>";
                    }else{
                        echo "<option value=\"$d->id\">$d->nama_lengkap</option>";
                    }
                }
            ?>
            </select>
        </div>
    @else
        @php
            $dokter = Dokter::where('user_id',Auth::user()->id)->first();
        @endphp
        <input type="hidden" value={{$dokter->id}} name="dokter" id="dokter">
    @endif

    <div style="float: right; margin-top: 7px; margin-right: 7px;" id="printContainer">
        <form action="{{url('print/feedokter')}}">
            <input type="hidden" value="{{$total[0]->dokter_fee}}" name="totalFeeDokter">
            <input type="hidden" value={{request()->segment(4)}}  name="idDokter">
            <input type="hidden" value={{request()->segment(2)}} name="bulan">
            <input type="hidden" value={{request()->segment(3)}} name="tahun">
            @if (str_contains(Auth::user()->role, 'superadmin') || str_contains(Auth::user()->role, 'admin' || $request()->segment(2) != '-'))
                <button class="btn btn-info btn-sm" id="btnCetak"><i class="bx bx-printer"></i>Cetak</button>
            @endif
        </form>
    </div>
</div>
<div class="table-responsive">
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
                @for ($i = 0; $i < 7; $i++)
                    <td></td>
                @endfor
                <td>Total</td>
                <td><strong>{{ App\Http\Controllers\JenisTindakanController::rupiah($t->total)}}</strong></td>
                <td><strong>{{ App\Http\Controllers\JenisTindakanController::rupiah($t->biaya_bahan)}}</strong></td>
                <td><strong>{{ App\Http\Controllers\JenisTindakanController::rupiah($t->sharing)}}</strong></td>
                <td><strong>{{ App\Http\Controllers\JenisTindakanController::rupiah($t->rsia_fee)}}</strong></td>
                <td><strong>{{ App\Http\Controllers\JenisTindakanController::rupiah($t->dokter_fee)}}</strong></td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
    <button id="downloadButton" class="btn btn-primary">Download CSV</button>
    <button id="downloadExcelButton" class="btn btn-success">Download Excel</button>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
<script>
    $(document).ready(function () {
        $('#tindakanPasien').DataTable();
        var dokter = $("#dokter").val();
    });
    $("#bulan, #dokter,#tahun").on('change', function() {
        var bulanSelected = $("#bulan").val();
        var tahunSelected = $("#tahun").val();
        var dokter = $("#dokter").val();
        window.location.href = '/rekapPendapatan/' + bulanSelected + '/' + tahunSelected+ '/' + dokter;
    });

    var dokter = $("#dokter").val();
    if(dokter == '-'){
        document.getElementById("btnCetak").disabled = true;
    }

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



