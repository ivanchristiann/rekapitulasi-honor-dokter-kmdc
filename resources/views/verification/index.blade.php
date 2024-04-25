@extends('layout.sneat')

@section('menu')
<div class="portlet-title">
    <div style="display: inline-block; margin: 15px; font-size: 25px; font-weight: bold;">
        List User Verifikasi
    </div>
</div>
@endsection
@section('content')

<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="confirmationMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="batal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmAction">Konfirmasi</button>
            </div>
        </div>
    </div>
</div>


<div style="margin: 15px; font-size: 20px;">
    <strong>List User Verifikasi</strong>
</div>
<div class="table-responsive">
    <table id="listUserVerification" class="table table-striped" style="width:100%">
        <thead class="table-border-bottom-0">
            @if (count($dokterVerification) != 0 || count($adminVerification) != 0)
            <tr>
                <th>#</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Role</th>
                <th>Nomor Telepon</th>
                <th>Username</th>
                @if(str_contains(Auth::user()->role, 'superadmin'))
                    <th>Verification</th>
                    <th>Tolak</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($dokterVerification as $dokter)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $dokter->nama_lengkap }}</td>
                <td>{{ $dokter->email}}</td>
                <td>{{ $dokter->role }}</td>
                <td>{{ $dokter->nomor_telp}}</td>
                <td>{{ $dokter->username }}</td>

                @if(str_contains(Auth::user()->role, 'superadmin'))
                <td class="text-center"><button onclick="verification({{ $dokter->user_id}},'{{$dokter->nama_lengkap}}')"
                    class="btn btn-sm btn-success"><i class='bx bx-check'></i></a>
                </td>
                <td class="text-center"><button onclick="tolak({{ $dokter->user_id }} ,'{{$dokter->nama_lengkap}}')"
                        class="btn btn-sm btn-danger"><i class='bx bx-x'></i></button>
                </td>
                @endif
            </tr>
            @endforeach
            @foreach ($adminVerification as $admin)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $admin->nama_lengkap }}</td>
                <td>{{ $admin->email}}</td>
                <td>{{ $admin->role }}</td>
                <td>{{ $admin->nomor_telp }}</td>
                <td>{{ $admin->username }}</td>

                @if(str_contains(Auth::user()->role, 'superadmin'))
                    <td class="text-center"><button onclick="verification({{ $admin->user_id}},'{{$admin->nama_lengkap}}')"
                        class="btn btn-sm btn-success"><i class='bx bx-check'></i></a>
                    </td>
                    <td class="text-center"><button onclick="tolak({{ $admin->user_id }},'{{$admin->nama_lengkap}}')"
                            class="btn btn-sm btn-danger"><i class='bx bx-x'></i></button>
                    </td>
                @endif
            </tr>
            @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="8">Tidak ada User yang meminta verifikasi</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection


@section('script')
<script>

    $(document).ready(function () {
        $('#listUserVerification').DataTable({
            "scrollX": true
        });
    });

    function verification(id, nama_lengkap) {
        var confirmButton = document.getElementById('confirmAction');
        confirmButton.classList.remove('btn-danger');
        confirmButton.classList.add('btn-primary');

        document.getElementById('confirmationMessage').textContent = "Apakah Anda yakin ingin memverifikasi " + nama_lengkap + " ?";
        $('#confirmationModal').modal('show');

        document.getElementById('confirmAction').onclick = function () {
            $.ajax({
            type: 'POST',
            url: "{{ route('verification.terima') }}",
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
            $('#confirmationModal').modal('hide');
        };

        document.getElementById('batal').onclick = function () {
            $('#confirmationModal').modal('hide');
        };      

        $('#confirmationModal').modal('show');   
    }

    function tolak(id, nama_lengkap) {
        var confirmButton = document.getElementById('confirmAction');
        confirmButton.classList.remove('btn-primary');
        confirmButton.classList.add('btn-danger');

        document.getElementById('confirmationMessage').textContent = "Apakah Anda yakin ingin menolak verifikasi dari " + nama_lengkap + " ?";
        $('#confirmationModal').modal('show');

        document.getElementById('confirmAction').onclick = function () {
            $.ajax({
            type: 'POST',
            url: "{{ route('verification.tolak') }}",
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
            $('#confirmationModal').modal('hide');
        };

        document.getElementById('batal').onclick = function () {
            $('#confirmationModal').modal('hide');
        };      

        $('#confirmationModal').modal('show');   
    }
</script>
@endsection
