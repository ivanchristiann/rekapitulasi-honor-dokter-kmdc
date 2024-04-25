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
        Add New Admin
    </div>
</div>
@endsection
@section('content')
<form method="POST" action="{{route('admin.update', $admin->id)}}">
    @csrf
    @method("PUT")
    <div class="form-group">
        <label>Nama Lengkap</label>
        <input type="text" name="namaAdmin" class="form-control" id="namaAdmin" value='{{$admin->nama_lengkap}}' required>

        <label>Email</label>
        <input type="text" name="emailAdmin" class="form-control" id="emailAdmin" value='{{$user->email}}' required>

        <label>username</label>
        <input type="text" name="usernameAdmin" class="form-control" id="usernameAdmin" value='{{$user->username}}' required>

        <label>nomor telepon</label>
        <input type="number" name="nomorTeleponAdmin" class="form-control" id="nomorTeleponAdmin" value='{{$user->nomor_telp}}' required>

    </div>
    <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Submit</button>
</form>

@endsection
