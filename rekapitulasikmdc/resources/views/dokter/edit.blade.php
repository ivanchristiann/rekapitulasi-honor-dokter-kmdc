<style>
    label{
        margin-top: 15px;
        margin-bottom: 10px;
        color: black;
    }
</style>
@extends('layout.sneat')

@section('menu')
<div class="portlet-title">
    <div style="display: inline-block; margin: 15px; font-size: 25px; font-weight: bold;">
        Add New Dokter
    </div>
</div>
@endsection
@section('content')
<form method="POST" action="{{route('dokter.update', $user->id)}}">
    @csrf
    @method("PUT")
    <div class="form-group">
        <label>Nama Lengkap</label>
        <input type="text" name="namaDokter" class="form-control" id="namaDokter" value='{{$dokter->nama_lengkap}}' required>

        <label>Email</label>
        <input type="text" name="emailDokter" class="form-control" id="emailDokter" value= '{{$user->email}}' required>

        <label>username</label>
        <input type="text" name="usernameDokter" class="form-control" id="usernameDokter" value= '{{$user->username}}' required>

        <label>nomor telepon</label>
        <input type="number" name="nomorTeleponDokter" class="form-control" id="nomorTeleponDokter" value= '{{$user->nomor_telp}}' required>

        <label>Singkatan</label>
        <input type="text" name="singkatan" class="form-control" id="singkatan" value='{{$dokter->kode_nama_dokter}}' required>
    </div>
    <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Submit</button>
</form>

@endsection
