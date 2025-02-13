@extends('admin.admin-layout')
@section('content')
    <div class="row mb-3">

        <div class="">
            <button class="btn btn-dark mb-5">Data yang disajikan adalah data siswa pada bulan ini</button>

        </div>
        <div class="col-md-2">
            <label for="filterAngkatan">Filter Angkatan</label>
            <select id="filterAngkatan" name="filter_angkatan" class="form-control">
                <option value="">Pilih Angkatan</option>
                @for ($year = 2020; $year <= date('Y'); $year++)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-2">
            <label for="filterKelas">Filter Kelas</label>
            <select id="filterKelas" class="form-control" name="filter_kelas">
                <option value="">Pilih Kelas</option>
                @foreach (range(1, 6) as $number)
                    @foreach (range('A', 'D') as $letter)
                        <option value="{{ $number . $letter }}">{{ $number . $letter }}</option>
                    @endforeach
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label for="filterStatus">Filter Status</label>
            <select id="filterStatus" class="form-control" name="filter_status">
                <option value="">Pilih Status</option>
                <option value="Belum Lunas">Belum Lunas</option>
                <option value="Lunas">Lunas</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="filterTahun">Filter Tahun</label>
            <select id="filterTahun" name="filter_tahun" class="form-control">
                <option value="">Pilih Tahun</option>
                @for ($year = 2020; $year <= date('Y'); $year++)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-2">
            <label for="filterBulan">Filter Bulan</label>
            <select id="filterBulan" class="form-control" name="filter_bulan">
                <option value="">Pilih Bulan</option>
                <option value="Januari">Januari</option>
                <option value="Februari">Februari</option>
                <option value="Maret">Maret</option>
                <option value="April">April</option>
                <option value="Mei">Mei</option>
                <option value="Juni">Juni</option>
                <option value="Juli">Juli</option>
                <option value="Agustus">Agustus</option>
                <option value="September">September</option>
                <option value="Oktober">Oktober</option>
                <option value="November">November</option>
                <option value="Desember">Desember</option>
            </select>
        </div>
        <div class="col-2">
            <button class="btn btn-outline-primary mt-4" id="btnFilter">
                Filter
            </button>

        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-light" id="dataTables">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>No Invoice</th>
                    <th>Nama Siswa</th>
                    <th>NIS</th>
                    <th>Angkatan</th>
                    <th>Kelas</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporan_siswa as $index => $siswa)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $siswa->no_invoice }}</td>
                        <td>{{ $siswa->siswa->nama }}</td>
                        <td>{{ $siswa->siswa->nis }}</td>
                        <td>{{ $siswa->siswa->angkatan }}</td>
                        <td>{{ $siswa->siswa->kelas }}</td>
                        <td>
                            @if ($siswa->status == 'Belum Lunas')
                                <button class="btn btn-danger">Belum Lunas</button>
                            @else
                                <button class="btn btn-success">Lunas</button>
                            @endif
                        </td>
                        <td>
                            <div class="d-grid">
                                <a href="{{ route('laporanSiswa.show', ['laporan_siswa' => $siswa->id]) }}"
                                    class="btn btn-block btn-info my-1">Detail</a>
                            </div>

                        </td>
                    </tr>
                @endforeach


            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#btnFilter').click(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('laporanSiswa.filter') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "filter_angkatan": $('#filterAngkatan').val(),
                        "filter_kelas": $('#filterKelas').val(),
                        "filter_status": $('#filterStatus').val(),
                        "filter_tahun": $('#filterTahun').val(),
                        "filter_bulan": $('#filterBulan').val(),
                    },
                    success: function(data) {
                        $('#dataTables tbody').empty();
                        $.each(data, function(index, value) {
                            $('#dataTables tbody').append('<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td>' + value.no_invoice + '</td>' +
                                '<td>' + value.siswa.nama + '</td>' +
                                '<td>' + value.siswa.nis + '</td>' +
                                '<td>' + value.siswa.angkatan + '</td>' +
                                '<td>' + value.siswa.kelas + '</td>' +
                                '<td>' + (value.status == 'Belum Lunas' ?
                                    '<button class="btn btn-danger">Belum Lunas</button>' :
                                    '<button class="btn btn-success">Lunas</button>'
                                ) + '</td>' +
                                '<td> <div class="d-grid">' +
                                '<a href="/laporan-siswa/' + value.id +
                                '" class="btn btn-block btn-info my-1">Detail</a>' +
                                '</div>' +
                                '</td>' +
                                '</tr>');
                        });
                    }
                });
            });
        });
    </script>
@endpush
