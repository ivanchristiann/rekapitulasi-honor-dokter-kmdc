@extends('layout.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" id="nama" required value="{{ old('nama') }}">

                            <label>Sigkatan</label>
                            <input type="text" name="namaSingkatan" class="form-control" id="namaSingkatan" required value="{{ old('namaSingkatan') }}">
                    
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" id="email" required value="{{ old('email') }}">
                    
                            <label>Nomor Telepon</label>
                            <input type="number" name="nomorTelepon" class="form-control" id="nomorTelepon" required value="{{ old('nomorTelepon') }}">
                    
                            <label>username</label>
                            <input type="text" name="username" class="form-control" id="username" required value="{{ old('username') }}">
                    
                            <label>Daftar Sebagai</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="role" id="role" value="admin" checked>
                                <label class="form-check-label">Admin</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="role" id="role" value="dokter">
                                <label class="form-check-label">Dokter</label>
                            </div>

                            <label>Password</label>
                            <input type="password" name="password" class="form-control" id="password" required value="{{ old('password') }}">
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                            <label>Confirm Password</label>
                            <input type="password" name="confirmPassword" class="form-control" id="confirmPassword" required value="{{ old('confirmPassword') }}">
                            @error('confirmPassword')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
