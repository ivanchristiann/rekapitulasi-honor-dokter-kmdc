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
<form method="POST" action="{{route('dokter.store')}}">
    @csrf
    <div class="form-group">
        <label>Nama Lengkap</label>
        <input type="text" name="namaDokter" class="form-control" id="namaDokter" required value="{{ old('namaDokter') }}">

        <label>Email</label>
        <input type="email" name="emailDokter" class="form-control" id="emailDokter" required value="{{ old('emailDokter') }}">

        <label>username</label>
        <input type="text" name="usernameDokter" class="form-control" id="usernameDokter" required value="{{ old('usernameDokter') }}">

        <label>nomor telepon</label>
        <input type="number" name="nomorTeleponDokter" class="form-control" id="nomorTeleponDokter" required value="{{ old('nomorTeleponDokter') }}">

        <label>Password</label>
        <input type="password" name="password" class="form-control" id="password" required value="{{ old('password') }}">
        @error('password')
            <div class="text-danger">{{ $message }}</div>
        @enderror

        <label>Singkatan</label>
        <input type="text" name="singkatan" class="form-control" id="singkatan" required value="{{ old('singkatan') }}">
    </div>
    <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Submit</button>
</form>
@endsection
