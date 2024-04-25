@extends('layout.sneat')

@section('menu')
<div class="portlet-title">
    <div style="display: inline-block; margin: 15px; font-size: 25px; font-weight: bold;">
        List Dokter
    </div>

    @if(str_contains(Auth::user()->role, 'superadmin'))
    <div style="float: right; margin: 15px;">
        <a href="{{url('dokter/create')}}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add</a>
    </div>
    @endif

</div>
@endsection
@section('content')

@if (session('status'))
<div class="alert alert-success">{{session('status')}}</div>
@endif

<div style="margin: 15px; font-size: 20px;">
    <strong>List Dokter Aktif</strong>
</div>
<div class="table-responsive">
    <table id="dokterAktif" class="table table-striped" style="width:100%">
        <thead class="table-border-bottom-0">
            @if (count($dokterAktif) == 0)
            <tr>
                <td class="text-center" colspan="8">Tidak ada Dokter yang terdata</td>
            </tr>
            @else
            <tr>
                <th>#</th>
                <th>Nama Lengkap</th>
                <th>Singkatan</th>
                <th>Status</th>
                <th>Email</th>
                <th>Username</th>
                <th>No Telepon</th>
                <th>Last Login</th>
                @if(str_contains(Auth::user()->role, 'superadmin'))
                    <th>Edit</th>
                    <th>Non Aktifkan</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($dokterAktif as $dokterAktif)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $dokterAktif->nama_lengkap }}</td>
                <td>{{ $dokterAktif->kode_nama_dokter }}</td>
                <td>{{ $dokterAktif->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                <td>{{ $dokterAktif->email }}</td>
                <td>{{ $dokterAktif->username }}</td>
                <td>{{ $dokterAktif->nomor_telp }}</td>
                <td>{{ $dokterAktif->last_login }}</td>

                @if(str_contains(Auth::user()->role, 'superadmin'))
                    <td class="text-center"><a href="{{ route('dokter.edit', $dokterAktif->user_id) }}"
                            class="btn btn-sm btn-primary"><i class='bx bx-edit-alt'></i></a>
                    </td>
                    <td class="text-center"><button onclick="nonaktifkan({{ $dokterAktif->user_id }})"
                            class="btn btn-sm btn-danger"><i class='bx bx-power-off'></i></button>
                    </td>
                @endif
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>
<br><br>
<div>
    <div style="margin: 15px; font-size: 20px;">
        <strong>List Dokter Nonaktif</strong>
    </div>
    <table id="dokterNonAktif" class="table table-striped" style="width:100%">
        <thead>
            @if (count($dokterNonaktif) == 0)
            <tr>
                <td class="text-center" colspan="8">Tidak ada Dokter yang terdata</td>
            </tr>
            @else
            <tr>
                <th>ID</th>
                <th>Nama Lengkap</th>
                <th>Singkatan</th>
                <th>Status</th>
                <th>Email</th>
                <th>Username</th>
                <th>No Telepon</th>
                <th>Last Login</th>
                @if(str_contains(Auth::user()->role, 'superadmin'))
                    <th>Edit</th>
                    <th>Aktifkan</th>
                @endif
            </tr>
            @endif
        </thead>
    <tbody>
        @foreach ($dokterNonaktif as $dokterNonAktif)
        <tr>
            <td>{{ $dokterNonAktif->id }}</td>
            <td>{{ $dokterNonAktif->nama_lengkap }}</td>
            <td>{{ $dokterNonAktif->kode_nama_dokter }}</td>
            <td>{{ $dokterNonAktif->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
            <td>{{ $dokterNonAktif->email }}</td>
            <td>{{ $dokterNonAktif->username }}</td>
            <td>{{ $dokterNonAktif->nomor_telp }}</td>
            <td>{{ $dokterNonAktif->last_login }}</td>
            @if(str_contains(Auth::user()->role, 'superadmin'))
                <td class="text-center"><a href="{{ route('dokter.edit', $dokterNonAktif->user_id) }}"
                        class="btn btn-sm btn-primary"><i class='bx bx-edit-alt'></i></a>
                </td>
                <td class="text-center"><button onclick="aktifkan({{ $dokterNonAktif->user_id }})"
                        class="btn btn-sm btn-success"><i class='bx bx-power-off'></i></button>
                </td>
            @endif
        </tr>
        @endforeach

    </tbody>
    </table>
</div>
@endsection


@section('script')
<script>
    $(document).ready(function () {
        $('#dokterAktif').DataTable({
            "scrollX": true
        });
        $('#dokterNonAktif').DataTable({
            "scrollX": true
        });
    });

    function nonaktifkan(id) {
        $.ajax({
            type: 'POST',
            url: "{{ route('dokter.nonaktifkan') }}",
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
            url: "{{ route('dokter.aktifkan')}}",
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
@endsection
