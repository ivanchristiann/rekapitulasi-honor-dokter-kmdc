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
        Edit Diagnosa
    </div>
</div>
@endsection
@section('content')
<form method="POST" action="{{route('diagnosa.update', $diagnosa->id)}}">
    @csrf
    @method("PUT")
    <div class="form-group">
        <div class="form-group">
            <label>Kode Diagnosa</label>
            <input type="text" name="kodeDiagnosa" class="form-control" id="kodeDiagnosa" value= '{{$diagnosa->kode_diagnosa}}' required>
    
            <label>Nama Diagnosa</label>
            <input type="text" name="namaDiagnosa" class="form-control" id="namaDiagnosa" value='{{$diagnosa->nama_diagnosa}}' required>
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Submit</button>
    </div>
</form>

@endsection
