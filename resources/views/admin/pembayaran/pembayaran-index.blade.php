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
                @foreach ($kelas as $k)
                    <option value="{{ $k->kelas }}">{{ $k->kelas }}</option>
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
                <th>#</th>
                <th>No Invoice</th>
                <th>Bukti</th>
                <th>Nama Invoice</th>
                <th>Siswa</th>
                <th>Nominal</th>
                <th>Nama Nominal</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Tanggal Terbit</th>
                <th>Tanggal Lunas</th>
                <th>Admin Penerbit</th>
                <th>Admin Melunasi</th>
                <th>Tanggal ditambahkan</th>
                <th>Aksi</th> <!-- Kolom untuk aksi -->
            </tr>
        </thead>
        <tbody>
            @foreach ($pembayarans as $index => $pembayaran)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td> {{ $pembayaran->no_invoice }}</td>
                    <td> <img src="{{ asset($pembayaran->bukti_pelunasan) }}" alt="{{ $pembayaran->bukti_pelunasan }}"></td>
                    <td>{{ $pembayaran->nama_invoice }}</td>
                    <td>{{ $pembayaran->siswa->nama }} - <b>{{ $pembayaran->siswa->kelas }} </b></td>
                    <td>{{ 'Rp. ' . number_format($pembayaran->biaya->nominal, 0, ',', '.') }}</td>
                    <td>{{ $pembayaran->biaya->nama_nominal }}</td>
                    {{-- <td>
                        @if ($pembayaran->status == 'Belum Lunas')
                            <span class="badge rounded-pill bg-danger">Belum Lunas</span>
                        @else
                            <span class="badge rounded-pill bg-success">Lunas</span>
                        @endif
                    </td> --}}
                    <td>
                        {{ $pembayaran->bulan }}
                    </td>
                    <td>
                        {{ $pembayaran->tahun }}

                    </td>
                    <td>{{ $pembayaran->tanggal_terbit }}</td>
                    <td>{{ $pembayaran->tanggal_lunas ?? '-' }}</td>
                    <td>{{ $pembayaran->penerbit->nama ?? '-' }}</td>
                    <td>{{ $pembayaran->melunasi->nama ?? '-' }}</td>

                    <td>{{ \Carbon\Carbon::parse($pembayaran->created_at)->isoFormat('HH:mm:ss, dddd, D MMMM Y') }}</td>

                    <td>
                        <a href="{{ route('pembayaran.show', $pembayaran->id) }}" class="btn btn-sm btn-warning">Detail</a>
                        @if ($pembayaran->status == 'Belum Lunas')
                            <a href="{{ route('pembayaran.verifikasi', $pembayaran->id) }}"
                                class="btn btn-sm btn-info">Verifikasi</a>
                        @else
                            <span class="btn btn-sm btn-success">Lunas</span>
                        @endif


                        <a href="{{ asset('bukti-pelunasan/' . $pembayaran->bukti_pelunasan) }}"
                            class="btn btn-sm btn-secondary">Kuitansi</a>

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
                                '<td>' + value.nama_invoice + '</td>' +
                                '<td>' + value.siswa.nama + '-' + value.siswa
                                .kelas + '</td>' +
                                '<td> Rp. ' + value.biaya.nominal + '</td>' +
                                '<td>' + value.biaya.nama_nominal + '</td>' +
                                '<td>' + value.bulan + '</td>' +
                                '<td>' + value.tahun + '</td>' +
                                '<td>' + value.tanggal_terbit + '</td>' +
                                '<td>' + (value.tanggal_lunas || '-') + '</td>' +
                                '<td>' + (value.penerbit ? value.penerbit.nama :
                                    '-') + '</td>' +
                                '<td>' + (value.melunasi ? value.melunasi.nama :
                                    '-') + '</td>' +
                                '<td>' + (value.created_at ? new Date(value
                                        .created_at).toLocaleDateString('id-ID') :
                                    '-') + '</td>' +
                                '<td>' +
                                '<a href="/pembayaran/' + value.id +
                                '" class="btn btn-info">Detail</a>' +
                                '<div class="d-grid">' +
                                '<a href="/pembayaran/' + value.id +
                                '/edit" class="btn btn-warning my-1">Edit</a>' +
                                '<form action="/pembayaran/' + value.id +
                                '" method="POST" style="display:inline;">' +
                                '@csrf' +
                                '@method('DELETE')' +
                                '<button type="submit" class="btn btn-danger my-1" onclick="return confirm(\'Apakah Anda yakin ingin menghapus data pembayaran keluar ini?\')">Hapus</button>' +
                                '</form>' +
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
