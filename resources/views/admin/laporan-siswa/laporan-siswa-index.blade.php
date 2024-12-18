@extends('admin.admin-layout')
@section('content')
    <div class="row mb-3">

        <div class="">
            <button class="btn btn-dark mb-5">Data yang disajikan adalah data siswa pada bulan ini</button>

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
                <option value="1">Januari</option>
                <option value="2">Februari</option>
                <option value="3">Maret</option>
                <option value="4">April</option>
                <option value="5">Mei</option>
                <option value="6">Juni</option>
                <option value="7">Juli</option>
                <option value="8">Agustus</option>
                <option value="9">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>
        </div>
        <div class="col-4">
            <button type="submit" class="btn btn-outline-primary mt-4" id="btnFilter">
                Filter
            </button>

        </div>
    </div>
    <table class="table table-light" id="dataTables">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Nama petugas</th>
                <th>Jabatan</th>
                <th>Email</th>
                <th>No Telfon</th>
                <th>NIP</th>
                <th>SPP Terbit</th>
                <th>SPP Dilunasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporan_siswa as $index => $siswa)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $siswa->nama }}</td>
                    <td>
                        <ul>
                            @foreach ($siswa->roles as $role)
                                <li>{{ $role->name }}</li>
                            @endforeach
                        </ul>


                    </td>
                    <td>{{ $siswa->email }}</td>
                    <td>{{ $siswa->no_telp ?? '-' }}</td>
                    <td>{{ $siswa->nip ?? '-'}}</td>
                    <td>{{ $siswa->menerbitkan_count }}</td>
                    <td>{{ $siswa->no_telp ?? '-' }}</td>
                    <td>
                        <div class="d-grid">
                            <a href="{{ route('laporanSiswa.show', ['siswa' => $siswa->id]) }}"
                                class="btn btn-block btn-info my-1">Detail</a>
                        </div>

                    </td>
                </tr>
            @endforeach


        </tbody>
    </table>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#btnFilter').click(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('laporanPetugas.filter') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "filter_tahun": $('#filterTahun').val(),
                        "filter_bulan": $('#filterBulan').val(),
                    },
                    success: function(data) {
                        $('#dataTables tbody').empty();
                        $.each(data, function(index, value) {
                            $('#dataTables tbody').append('<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td>' + value.nama + '</td>' +
                                '<td>' + value.email + '</td>' +
                                '<td>' + value.no_telp + '</td>' +
                                '<td>' + value.roles.map(role => role.name).join(', ') + '</td>' +
                                '<td>' +
                                '<div class="d-grid">' +
                                '<a href="/laporan-petugas/' + value.id + '" class="btn btn-block btn-info my-1">Detail</a>' +
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
