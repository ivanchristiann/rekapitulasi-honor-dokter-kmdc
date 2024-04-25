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
<form method="POST" action="{{route('admin.store')}}">
    @csrf
    <div class="form-group">
        <label>Nama Lengkap</label>
        <input type="text" name="namaAdmin" class="form-control" id="namaAdmin" required value="{{ old('namaAdmin') }}">

        <label>Email</label>
        <input type="email" name="emailAdmin" class="form-control" id="emailAdmin" required value="{{ old('emailAdmin') }}">

        <label>username</label>
        <input type="text" name="usernameAdmin" class="form-control" id="usernameAdmin" required value="{{ old('usernameAdmin') }}">

        <label>nomor telepon</label>
        <input type="number" name="nomorTeleponAdmin" class="form-control" id="nomorTeleponAdmin" required value="{{ old('nomorTeleponAdmin') }}">

        <label>Password</label>
        <input type="password" name="password" class="form-control" id="password" required value="{{ old('password') }}">
        @error('password')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Submit</button>
</form>

@endsection
