@extends('admin.admin-layout')
@section('content')
    <div class="row mb-3">
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
                <th>No</th>
                <th>No Invoice</th>
                <th>NIS</th>
                <th>Siswa</th>
                <th>Nominal</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembayarans as $index => $pembayaran)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pembayaran->no_invoice }}</td>
                    <td>{{ $pembayaran->siswa->nis }}</td>
                    <td>{{ $pembayaran->siswa->nama }} - <b>{{ $pembayaran->siswa->kelas }} </b></td>
                    <td>{{ 'Rp. ' . number_format($pembayaran->biaya->nominal, 0, ',', '.') }}</td>
                    <td>{{ $pembayaran->bulan }}</td>
                    <td>{{ $pembayaran->tahun }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ asset('bukti-pelunasan/' . $pembayaran->bukti_pelunasan) }}"
                                class="btn btn-sm btn-secondary">Kuitansi</a>
                            @if ($pembayaran->status == 'Belum Lunas')
                                <a href="{{ route('pembayaran.verifikasi', $pembayaran->id) }}"
                                    class="btn btn-sm btn-info">Verifikasi</a>
                            @else
                                <span class="btn btn-sm btn-success">Lunas</span>
                            @endif

                            <a href="{{ route('pembayaran.show', $pembayaran->id) }}" class="btn btn-sm btn-warning">Detail</a>
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
                    url: "{{ route('pembayaran.filter') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "filter_tahun": $('#filterTahun').val(),
                        "filter_bulan": $('#filterBulan').val(),
                        "filter_angkatan": $('#filterAngkatan').val(),
                        "filter_kelas": $('#filterKelas').val()
                    },
                    success: function(data) {
                        $('#dataTables tbody').empty();
                        $.each(data, function(index, value) {
                            $('#dataTables tbody').append('<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td>' + value.no_invoice + '</td>' +
                                '<td>' + value.siswa.nis + '</td>' +
                                '<td>' + value.siswa.nama + ' - <b>' + value.siswa.kelas + '</b></td>' +
                                '<td> Rp. ' + value.biaya.nominal.toLocaleString('id-ID') + '</td>' +
                                '<td>' + value.bulan + '</td>' +
                                '<td>' + value.tahun + '</td>' +
                                '<td>' +
                                '<div class="d-flex gap-1">' +
                                '<a href="{{ asset("bukti-pelunasan/' + value.bukti_pelunasan + '") }}" class="btn btn-sm btn-secondary">Kuitansi</a>' +
                                (value.status == 'Belum Lunas' ?
                                    '<a href="{{ route("pembayaran.verifikasi", ' + value.id + ') }}" class="btn btn-sm btn-info">Verifikasi</a>' :
                                    '<span class="btn btn-sm btn-success">Lunas</span>'
                                ) +
                                '<a href="{{ route("pembayaran.show", ' + value.id + ') }}" class="btn btn-sm btn-warning">Detail</a>' +
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
