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
        Add New Diagnosa
    </div> 
</div>
@if(Session::has('alert'))
    <div class="alert alert-danger">
        {{ Session::get('alert') }}
    </div>
@endif
@endsection
@section('content')
<form method="POST" action="{{route('diagnosa.store')}}">
    @csrf
    <div class="form-group">
        <label>Kode Diagnosa</label>
        <input type="text" name="kodeDiagnosa" class="form-control" id="kodeDiagnosa" required>

        <label>Nama Diagnosa</label>
        <input type="text" name="namaDiagnosa" class="form-control" id="namaDiagnosa" required>
    </div>
    <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Submit</button>
</form>

@endsection