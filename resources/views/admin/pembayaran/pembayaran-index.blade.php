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
                <th>Nama Biaya</th>
                <th>Siswa</th>
                <th>Status</th>
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
                    <td>{{ $pembayaran->biaya->nama_biaya }}</td>
                    <td>{{ $pembayaran->siswa->nama }}</td>
                    <td>
                        @if ($pembayaran->status == 'Belum Lunas')
                            <span class="badge rounded-pill bg-danger">Belum Lunas</span>
                        @else
                            <span class="badge rounded-pill bg-success">Lunas</span>
                        @endif
                    </td>
                    <td>{{ $pembayaran->tanggal_terbit }}</td>
                    <td>{{ $pembayaran->tanggal_lunas ?? '-' }}</td>
                    <td>{{ $pembayaran->penerbit->nama ?? '-' }}</td>
                    <td>{{ $pembayaran->melunasi->nama ?? '-' }}</td>

                    <td>{{ \Carbon\Carbon::parse($pembayaran->created_at)->isoFormat('HH:mm:ss, dddd, D MMMM Y') }}</td>
                    <td>
                        <a href="{{ route('tagihan.edit', $pembayaran->id) }}" class="btn btn-warning">Edit</a>

                        <form action="{{ route('tagihan.destroy', $pembayaran->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus data tagihan keluar ini?')">Hapus</button>
                        </form>
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
                                '<td>' + value.biaya.no_invoice + '</td>' +
                                '<td>' + value.biaya.nama_invoice + '</td>' +
                                '<td>' + value.siswa.nama + '</td>' +
                                '<td>' + (value.status === 'Belum Lunas' ?
                                    '<span class="badge rounded-pill bg-danger">Belum Lunas</span>' :
                                    '<span class="badge rounded-pill bg-success">Lunas</span>'
                                ) + '</td>' +
                                '<td>' + value.tanggal_terbit + '</td>' +
                                '<td>' + (value.tanggal_lunas || '-') + '</td>' +
                                '<td>' + (value.penerbit.nama || '-') + '</td>' +
                                '<td>' + (value.melunasi.nama || '-') + '</td>' +
                                '<td>' + value.created_at + '</td>' +
                                '<td>' +
                                '<div class="d-grid">' +
                                '<a href="{{ route('tagihan.edit', "' + value.id + '") }}" class="btn btn-warning my-1">Edit</a>' +
                                '<form action="{{ route('tagihan.destroy', "' + value.id + '") }}" method="POST" style="display:inline;">' +
                                '@csrf' +
                                '@method('DELETE')' +
                                '<button type="submit" class="btn btn-danger my-1" onclick="return confirm(\'Apakah Anda yakin ingin menghapus data tagihan keluar ini?\')">Hapus</button>' +
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