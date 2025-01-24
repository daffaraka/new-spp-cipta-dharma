@extends('admin.admin-layout')
@section('content')
    <div class="row">
        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6">
            <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                <div class="card-header">Total Siswa</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $total_siswa }}</h5>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6">
            <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                <div class="card-header">Total Petugas</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $total_petugas }}</h5>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6">
            <div class="card text-dark bg-light mb-3" style="max-width: 18rem;">
                <div class="card-header">Total Laki-laki</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $total_laki }}</h5>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6">
            <div class="card text-white bg-warning mb-3" style="max-width: 18rem;">
                <div class="card-header">Total Perempuan</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $total_perempuan }}</h5>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="col-xl-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-area me-1"></i>
                        Statistik Per Jenis Kelamin
                    </div>
                    <div class="card-body" style="height: 400px">
                        <canvas id="jenisKelaminChart" ></canvas>
                    </div>
                </div>
            </div>

        </div>



    </div>
@endsection


@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script>
        const ctxJenisKelamin = document.getElementById('jenisKelaminChart').getContext('2d');
        const jenisKelaminChart = new Chart(ctxJenisKelamin,     {
            type: 'pie',
            data: {
                labels: [
                    @foreach ($data_perJenisKelamin as $item)
                        '{{ $item['jenis_kelamin'] }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Jumlah Siswa ',
                    data: [
                        @foreach ($data_perJenisKelamin as $item)
                            {{ $item['total'] }},
                        @endforeach
                    ],
                    backgroundColor: [
                        @foreach ($data_perJenisKelamin as $item)
                            '{{ $item['jenis_kelamin'] == 'Laki-laki' ? 'rgba(54, 162, 235, 0.2)' : 'rgba(255, 99, 132, 0.2)' }}',
                        @endforeach
                    ],
                    borderColor: [
                        @foreach ($data_perJenisKelamin as $item)
                            '{{ $item['jenis_kelamin'] == 'Laki-laki' ? 'rgba(54, 162, 235, 1)' : 'rgba(255, 99, 132, 1)' }}',
                        @endforeach
                    ],
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
@endpush
