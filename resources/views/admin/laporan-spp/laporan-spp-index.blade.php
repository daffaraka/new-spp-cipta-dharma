@extends('admin.admin-layout')
@section('content')
    <div class="row mb-3">
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
                <th>No Invoice</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Total Bayar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporan_spp as $index => $spp)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $spp->no_invoice }}</td>
                    <td>{{ $spp->siswa->nis }}</td>
                    <td>{{ $spp->siswa->nama }}</td>
                    <td>{{ $spp->siswa->kelas }}</td>
                    <td>{{ $spp->bulan }}</td>
                    <td>{{ $spp->tahun }}</td>
                    <td>{{ 'Rp. ' . number_format($spp->total_bayar, 0, ',', '.') }}</td>
                    <td>
                        <div class="d-grid">
                            <a href="{{ route('laporanSpp.show', ['spp' => $spp->id]) }}"
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
                                '<a href="{{ route('laporanPetugas.show', ['petugas' => ' + value.id + ']) }}" class="btn btn-block btn-info my-1">Detail</a>' +
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
