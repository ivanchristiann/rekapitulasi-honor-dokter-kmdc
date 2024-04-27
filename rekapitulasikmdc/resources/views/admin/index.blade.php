@extends('layout.sneat')

@section('menu')
<div class="portlet-title">
    <div style="display: inline-block; margin: 15px; font-size: 25px; font-weight: bold;">
        List Admin
    </div>

    @if(str_contains(Auth::user()->role, 'superadmin'))
    <div style="float: right; margin: 15px;">
        <a href="{{url('admin/create')}}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add</a>
    </div>
    @endif

</div>
@endsection

@section('content')

@if (session('status'))
<div class="alert alert-success">{{session('status')}}</div>
@endif

<div style="margin: 15px; font-size: 20px;">
    <strong>List Admin Aktif</strong>
</div>
<div class="table-responsive">
    <table id="adminAktif" class="table table-striped" style="width:100%">
        <thead class="table-border-bottom-0">
            @if (count($adminAktif) == 0)
            <tr>
                <td class="text-center" colspan="8">Tidak ada Admin yang terdata</td>
            </tr>
            @else
            <tr>
                <th>ID</th>
                <th>Nama Lengkap</th>
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
            @foreach ($adminAktif as $adminAktif)
            <tr>
                <td>{{ $adminAktif->id }}</td>
                <td>{{ $adminAktif->nama_lengkap }}</td>
                <td>{{ $adminAktif->email }}</td>
                <td>{{ $adminAktif->username }}</td>
                <td>{{ $adminAktif->nomor_telp }}</td>
                <td>{{ $adminAktif->last_login }}</td>

                @if(str_contains(Auth::user()->role, 'superadmin'))
                    <td class="text-center"><a href="{{ route('admin.edit', $adminAktif->id) }}"
                            class="btn btn-sm btn-primary"><i class='bx bx-edit-alt'></i></a>
                    </td>
                    <td class="text-center"><button onclick="nonaktifkan({{ $adminAktif->id }})"
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
        <strong>List Admin Nonaktif</strong>
    </div>
    <table id="adminNonAktif" class="table table-striped" style="width:100%">
        <thead>
        @if (count($adminNonaktif) == 0)
        <tr>
            <td class="text-center" colspan="8">Tidak ada Admin yang terdata</td>
        </tr>
        @else
        <tr>
            <th>ID</th>
            <th>Nama Lengkap</th>
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
        @foreach ($adminNonaktif as $adminNonaktif)
        <tr>
            <td>{{ $adminNonaktif->id }}</td>
            <td>{{ $adminNonaktif->nama_lengkap }}</td>
            <td>{{ $adminNonaktif->email }}</td>
            <td>{{ $adminNonaktif->username }}</td>
            <td>{{ $adminNonaktif->nomor_telp }}</td>
            <td>{{ $adminNonaktif->last_login }}</td>
            @if(str_contains(Auth::user()->role, 'superadmin'))
                <td class="text-center"><a href="{{ route('admin.edit', $adminNonaktif->id) }}"
                        class="btn btn-sm btn-primary"><i class='bx bx-edit-alt'></i></a>
                </td>
                <td class="text-center"><button onclick="aktifkan({{ $adminNonaktif->id }})"
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
        $('#adminAktif').DataTable({
            "scrollX": true
        });
        $('#adminNonAktif').DataTable({
            "scrollX": true
        });
    });

    function nonaktifkan(id) {
        $.ajax({
            type: 'POST',
            url: "{{ route('admin.nonaktifkan') }}",
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
            url: "{{ route('admin.aktifkan')}}",
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
