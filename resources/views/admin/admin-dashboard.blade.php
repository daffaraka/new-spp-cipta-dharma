@extends('admin.admin-layout')
@section('content')
    <div class="row">

        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6">
            <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                <div class="card-header">Total Siswa</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $total_siswa }}</h5>

                    <a href="{{ route('siswa.index') }}" class="btn btn-sm btn-dark mt-3">Halaman Siswa</a>
                </div>


            </div>

        </div>

        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6">
            <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                <div class="card-header">Total Petugas</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $total_petugas }}</h5>
                    <a href="{{ route('petugas.index') }}" class="btn btn-sm btn-dark mt-3">Halaman Petugas</a>

                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6">
            <div class="card text-dark bg-light mb-3" style="max-width: 18rem;">
                <div class="card-header">Total Laki-laki</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $total_laki }}</h5>
                    <a href="{{ route('siswa.index') }}" class="btn btn-sm btn-dark mt-3">Halaman Siswa</a>

                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6">
            <div class="card text-white bg-warning mb-3" style="max-width: 18rem;">
                <div class="card-header">Total Perempuan</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $total_perempuan }}</h5>
                    <a href="{{ route('siswa.index') }}" class="btn btn-sm btn-dark mt-3">Halaman Siswa</a>

                </div>
            </div>
        </div>




    </div>


    <div class="row mt-3">
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Statistik Per Jenis Kelamin
                </div>
                <div class="card-body  mb-5">
                    <canvas id="jenisKelaminChart" width="100%" height="100"></canvas>
                </div>
            </div>
        </div>



        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div class="py-2">
                            <i class="fas fa-chart-area me-1"></i>
                            Statistik Per Tahun
                        </div>
                        <div>
                            <select name="tahun" id="filter_tahun" class="form-control">
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="card-body mb-5" style="height: 400px">
                    <canvas id="dataPerTahun"></canvas>
                </div>
            </div>
        </div>


        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Statistik Per Bulan
                </div>
                <div class="card-body mb-5" style="height: 400">
                    <canvas id="dataPerBulan"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script>
        const ctxJenisKelamin = document.getElementById('jenisKelaminChart').getContext('2d');
        const jenisKelaminChart = new Chart(ctxJenisKelamin, {
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





        var ctx = document.getElementById("dataPerTahun");
        var perTahunLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    @foreach ($data_pembayaranPerTahun as $index => $item)
                        '{{ $index }}',
                    @endforeach
                ],
                datasets: [{
                    label: "Jumlah",
                    lineTension: 0.3,
                    backgroundColor: "rgba(2,117,216,0.2)",
                    borderColor: "rgba(2,117,216,1)",
                    pointRadius: 5,
                    pointBackgroundColor: "rgba(2,117,216,1)",
                    pointBorderColor: "rgba(255,255,255,0.8)",
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(2,117,216,1)",
                    pointHitRadius: 50,
                    pointBorderWidth: 2,
                    data: [
                        @foreach ($data_pembayaranPerTahun as $item)
                            '{{ $item }}',
                        @endforeach
                    ],
                }],
            },
            options: {
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'date'
                        },
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            maxTicksLimit: 5
                        },
                        gridLines: {
                            color: "rgba(0, 0, 0, .125)",
                        }
                    }],
                },
                legend: {
                    display: false
                }
            }
        });


        var ctx = document.getElementById("dataPerBulan");
        var perbulanLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    @foreach ($data_pembayaranPerBulan as $index => $item)
                        '{{ $index }}',
                    @endforeach
                ],
                datasets: [{
                    label: "Rp",
                    lineTension: 0.3,
                    backgroundColor: "rgba(255,205,86,0.2)",
                    borderColor: "rgba(255,205,86,1)",
                    pointRadius: 5,
                    pointBackgroundColor: "rgba(255,205,86,1)",
                    pointBorderColor: "rgba(255,255,255,0.8)",
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(255,205,86,1)",
                    pointHitRadius: 50,
                    pointBorderWidth: 2,
                    data: [
                        @foreach ($data_pembayaranPerBulan as $item)
                            '{{ $item }}',
                        @endforeach
                    ],
                }],
            },
            options: {
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'date'
                        },
                        gridLines: {
                            display: false
                        },
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            maxTicksLimit: 5
                        },
                        gridLines: {
                            color: "rgba(0, 0, 0, .125)",
                        }
                    }],
                },
                legend: {
                    display: false
                }
            }
        });




    </script>
@endpush
