<style>
    tr{
        white-space: nowrap;
    }

</style>
@extends('layout.sneat')

@section('menu')
<div class="portlet-title">
    <div style="display: inline-block; margin: 15px; font-size: 25px; font-weight: bold;">
        List Tindakan Pasien
    </div>
    @if (str_contains(Auth::user()->role, 'superadmin') || str_contains(Auth::user()->role, 'admin'))
    <div style="float: right; margin: 15px;">
        <a href="{{url('tindakanPasien/create')}}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add</a>
    </div>
    @endif
</div>
@endsection

@section('content')

@if (session('status'))
<div class="alert alert-success">{{session('status')}}</div>
@endif

<div style="margin: 15px; font-size: 20px;">
    <strong>List Tindakan Pasien</strong>
</div>

<div>
    <label for="bulanan" style="float: left; margin-top: 7px; margin-right: 7px;">Bulanan:</label>
    <select class="form-select" aria-label="Default select example" name="bulan" id="bulan" style="width: 200px; margin-bottom: 10px;">
    <?php
    $selected = false;
    for ($year = 2023; $year <= date('Y'); $year++) {
        $endMonth = ($year == date('Y')) ? date('m') : 12;
        for ($month = 1; $month <= $endMonth; $month++) {
            $optionValue = sprintf('%02d-%04d', $month, $year);

            if (($month == request()->segment(2) && $year == request()->segment(3)) || ($month == date('m') && $year == date('Y') && !$selected)) {
                echo "<option value=\"$optionValue\" selected>" . date('F Y', strtotime("$year-$month-01")). "</option>";
                $selected = true;
            } else {
                echo "<option value=\"$optionValue\">" . date('F Y', strtotime("$year-$month-01")). "</option>";
            }

        }
      }
    ?>
    </select>
</div>

<div class="table-responsive">
    <table id="tindakanPasien" class="table table-striped" style="width:100%">
        <thead class="table-border-bottom-0">
            @if (count($dataTindakan) == 0)
                <tr>
                    <td class="text-center" colspan="8">Tidak ada data tindakan yang terdata</td>
                </tr>
            @else
            <tr>
                <th>#</th>
                <th>Tahun</th>
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
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $count = count($dataTindakan);
            @endphp
            {{-- @dd($dataTindakan); --}}
            @for ($i = 0; $i < $count; $i++)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $dataTindakan[$i]->tahun }}</td>
                    <td>{{ $dataTindakan[$i]->tanggal_kunjungan }}</td>
                    <td>{{ $dataTindakan[$i]->nomorRekamMedis }}</td>
                    <td>{{ $dataTindakan[$i]->namaDokter }}</td>
                    <td>{{ $dataTindakan[$i]->nama_lengkap }}</td>
                    <td>{{ $dataTindakan[$i]->kode_diagnosa }}</td>
                    <td>{{ $dataTindakan[$i]->nama_tindakan }}</td>
                    <td>{{ $dataTindakan[$i]->jumlahTindakan}}</td>
                    <td>{{ App\Http\Controllers\JenisTindakanController::rupiah($dataTindakan[$i]->total) }}</td>
                    <td>{{ App\Http\Controllers\JenisTindakanController::rupiah($dataTindakan[$i]->biaya_bahan) }}</td>
                    <td>{{ App\Http\Controllers\JenisTindakanController::rupiah($dataTindakan[$i]->Sharing) }}</td>
                    <td>{{ App\Http\Controllers\JenisTindakanController::rupiah($dataTindakan[$i]->FeeRSIA) }}</td>
                    <td>{{ App\Http\Controllers\JenisTindakanController::rupiah($dataTindakan[$i]->FeeDokter) }}</td>
                    @if($i === 0)
                        <td>
                            <form method="POST" action="{{route('tindakanPasien.destroy', $dataTindakan[$i]->pasien_id)}}">
                                @csrf
                                @method('DELETE')
                                <input type="submit" value="Hapus" class="btn btn-danger" style="margin-bottom: -20px; margin-top: -5px;" onclick="return confirm('Apakah anda yakin ingin menghapus tindakan a/n {{$dataTindakan[$i]->namaPasien}} pada tanggal {{ $dataTindakan[$i]->tanggal_kunjungan }}?')">
                            </form>
                        </td>
                    @elseif ($dataTindakan[$i]->pasien_id !== $dataTindakan[$i-1]->pasien_id)
                        <td>
                            <form method="POST" action="{{route('tindakanPasien.destroy', $dataTindakan[$i]->pasien_id)}}">
                                @csrf
                                @method('DELETE')
                                <input type="submit" value="Hapus" class="btn btn-danger" style="margin-bottom: -20px; margin-top: -5px;" onclick="return confirm('Apakah anda yakin ingin menghapus tindakan a/n {{$dataTindakan[$i]->namaPasien}} pada tanggal {{ $dataTindakan[$i]->tanggal_kunjungan }}?')">
                            </form>  
                        </td>
                    @else
                    <td></td>
                    @endif
                </tr>
            @endfor
            @endif
        </tbody>
    </table>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        $('#tindakanPasien').DataTable();
    });

    $("#bulan").on('change', function() {
        var bulanSelected = $("#bulan").val().split('-');
        window.location.href = '/tindakanPasien/' + bulanSelected[0] + '/' + bulanSelected[1] ;
    });
</script>

@endsection



