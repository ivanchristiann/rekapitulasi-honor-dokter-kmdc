<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
@extends('layout.sneat')

@section('menu')
<div class="portlet-title">
    <div style="display: inline-block; margin: 15px; font-size: 25px; font-weight: bold;">
        List Diagnosa
    </div>
        @if(str_contains(Auth::user()->role, 'superadmin'))
            <div style="float: right; margin: 15px;">
                <a href="{{url('diagnosa/create')}}" class="btn btn-success btn-sm">Add</a>
            </div>
        @endif
</div>
@endsection

@section('content')
@if (session('status'))
<div class="alert alert-success">{{session('status')}}</div>
@endif

<div class="table-responsive text-nowrap">
    <div style="margin: 20px; font-size: 20px;">
        <strong>List Diagnosa Aktif</strong>
    </div>
    <table id="diagnosaAktif" class="table table-striped" style="width:100%">
        <thead>
            @if (count($diagnosaAktif) == 0)
            <tr>
                <td class="text-center" colspan="8">Tidak ada Diagnosa yang Aktif</td>
            </tr>
            @else
            <tr>
                <td><strong>#</strong></td>
                <td><strong>Kode Diagnosa</strong></td>
                <td><strong>Nama</strong></td>
                @if(str_contains(Auth::user()->role, 'superadmin'))
                    <td><strong>Edit</strong></td>
                    <td><strong>Action</strong></td>
                @endif

            </tr>
        </thead>
        <tbody>
            @foreach ($diagnosaAktif as $d)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $d->kode_diagnosa }}</td>
                <td>{{ $d->nama_diagnosa }}</td>
                @if(str_contains(Auth::user()->role, 'superadmin'))
                    <td class="text-center"><a href="{{ route('diagnosa.edit', $d->id) }}" class="btn btn-sm btn-primary"><i
                                class='bx bx-edit-alt'></i></a>
                    </td>
                    <td class="text-center"><button onclick="nonaktifkan({{ $d->id }})" class="btn btn-sm btn-danger"><i
                                class='bx bx-power-off'></i></button>
                    </td>
                @endif
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>

@if(str_contains(Auth::user()->role, 'superadmin'))
<div class="table-responsive text-nowrap">
    <div style="margin: 20px; font-size: 20px;">
        <strong>List Diagnosa Nonaktif</strong>
    </div>
    <table id="diagnosaNonAktif" class="table table-striped" style="width:100%">
        <thead>
            @if (count($diagnosaNonAktif) == 0)
            <tr>
                <td class="text-center" colspan="8">Tidak ada Jenis Diagnosa yang Nonaktif</td>
            </tr>
            @else
            <tr>
                <td><strong>#</strong></td>
                <td><strong>Kode Diagnosa</strong></td>
                <td><strong>Nama</strong></td>
                @if(str_contains(Auth::user()->role, 'superadmin'))
                    <td><strong>Edit</strong></td>
                    <td><strong>Action</strong></td>
                @endif
            </tr>
            @endif
        </thead>
        <tbody>

            @foreach ($diagnosaNonAktif as $d)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $d->kode_diagnosa }}</td>
                <td>{{ $d->nama_diagnosa }}</td>
                @if(str_contains(Auth::user()->role, 'superadmin'))
                    <td class="text-center"><a href="{{ route('diagnosa.edit', $d->id) }}" class="btn btn-sm btn-primary"><i
                                class='bx bx-edit-alt'></i></a>
                    </td>
                    <td class="text-center"><button onclick="aktifkan({{ $d->id }})" class="btn btn-sm btn-success"><i
                                class='bx bx-power-off'></i></button>
                    </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection

@section('script')
<script>
    $(document).ready(function () {
        $('#diagnosaAktif').DataTable({
            "scrollX": true
        });
        $('#diagnosaNonAktif').DataTable({
            "scrollX": true
        });
    });

    function nonaktifkan(id) {
        $.ajax({
            type: 'POST',
            url: "{{ route('diagnosa.nonaktifkan') }}",
            data: {
                '_token': '<?php echo csrf_token(); ?>',
                'id': id,
            },
            success: function (data) {
                if (data['status'] == 'success') {
                    window.location.reload(true);
                }
            }
        });
    }

    function aktifkan(id) {
        $.ajax({
            type: 'POST',
            url: "{{ route('diagnosa.aktifkan')}}",
            data: {
                '_token': '<?php echo csrf_token(); ?>',
                'id': id,
            },
            success: function (data) {
                if (data['status'] == 'success') {
                    window.location.reload(true);
                }
            }
        });
    }

</script>
