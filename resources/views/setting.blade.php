@extends('layout.sneat')

@section('menu')
<div class="portlet-title">
    <div style="display: inline-block; margin: 15px; font-size: 25px; font-weight: bold;">
        Setting Kuitansi
    </div>
</div>
@endsection

@section('content')
<table id="setting" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <td>#</td>
            <td>Nama</td>
            <td>Value</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($settings as $set)
        <tr>
            <td class="editable">{{ $loop->iteration }}</td>
            <td id="td_name_{{ $set->id}}">{{ $set->name }}</td>
            <td class="editable" id="td_value_{{ $set->id}}">{{ $set->value }}</td>
        </tr>
        @endforeach
    </thead>
</table>

@endsection

@section('script')
<script src="{{asset('../assets/plugins/jquery.editable.min.js')}}" type="text/javascript"></script>
<script>
    $('.editable').editable({
        closeOnEnter:true,
        callback:function(data){
            if(data.content){
                var s_id=data.$el[0].id
                var name=s_id.split('_')[1]
                var id=s_id.split('_')[2]

                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.saveData') }}",
                    data:{'_token':'<?php echo csrf_token() ?>',
                        'id':id,
                        'name' : name,
                        'value':data.content
                },
                    success: function(data){
                    // alert(data.msg)
                }
                });
            }
        }
    });

</script>
@endsection
