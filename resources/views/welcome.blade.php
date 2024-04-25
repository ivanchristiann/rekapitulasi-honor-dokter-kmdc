@php
    use App\Models\JenisTindakanPasien;

    $startDate = now()->subDays(15);  
    if (str_contains(Auth::user()->role, 'dokter')){
        $data = DB::table('jenis_tindakan_pasiens as jtp')
        ->join('dokters as d', 'd.id', '=', 'jtp.dokter_id')
        ->selectRaw('DATE(jtp.tanggal_kunjungan) as tanggal')
        ->selectRaw('SUM(jtp.biaya_tindakan) as total_biaya')
        ->where('d.user_id', auth()->user()->id) 
        ->where('jtp.tanggal_kunjungan', '>=', $startDate)
        ->groupBy('tanggal')
        ->get();
    }else{
        $data = JenisTindakanPasien::selectRaw('DATE(tanggal_kunjungan) as tanggal')
        ->selectRaw('SUM(biaya_tindakan) as total_biaya')
        ->where('tanggal_kunjungan', '>=', $startDate)
        ->groupBy('tanggal')
        ->get();
    }
@endphp

@extends('layout.sneat')

@section('menu')
<div class="portlet-title">
    <div style="display: inline-block; margin-left: 15px;  margin-top: 15px; font-size: 25px; font-weight: bold;">
        Welcome,  {{auth()->user()->name}}
    </div>
    <div style="margin-left: 15px; font-size: 18px; margin-bottom: 15px;">
        {{ strtoupper(auth()->user()->role) }}
    </div>
</div>
@endsection

@section('content')
<canvas id="grafik-pendapatan" width="100" height="35"></canvas>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<script>
    var ctx = document.getElementById('grafik-pendapatan').getContext('2d');
    var data = <?php echo json_encode($data); ?>;

    var allDates = [];
    var currentDate = new Date();
    for (var i = 0; i < 15; i++) {
        allDates.unshift(new Date(currentDate)); // Tambahkan tanggal ke array
        currentDate.setDate(currentDate.getDate() - 1); // Kurangkan satu hari dari tanggal
    }
    var labels = [];
    var values = [];

    // Iterasi melalui semua tanggal
    for (var i = 0; i < allDates.length; i++) {
        var tanggal = allDates[i];
        var tanggalString = tanggal.toISOString().slice(0, 10); // Format tanggal ke "YYYY-MM-DD"
        
        // Cari data yang cocok dengan tanggal
        var dataItem = data.find(function(item) {
            return item.tanggal === tanggalString;
        });

        // Jika data ditemukan, tambahkan total biaya ke values, jika tidak, tambahkan 0
        if (dataItem) {
            var options = { day: 'numeric', month: 'short', year: 'numeric' };
            labels.push(tanggal.toLocaleDateString('id-ID', options));
            values.push(dataItem.total_biaya);
        } else {
            var options = { day: 'numeric', month: 'short', year: 'numeric' };
            labels.push(tanggal.toLocaleDateString('id-ID', options));
            values.push('0');
        }
    }

    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan Harian',
                data: values,
                backgroundColor: 'rgb(42, 192, 47, 0.2)',
                borderColor: 'rgb(42, 192, 47, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>


@endsection