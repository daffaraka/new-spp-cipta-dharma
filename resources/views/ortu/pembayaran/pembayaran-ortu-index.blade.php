@extends('admin.admin-layout')
@section('content')
    <div class="row mb-3">
        <div class="col-md-2">
            <label for="filterStatus">Filter Status</label>
            <select id="filterStatus" name="filter_angkatan" class="form-control">
                <option value="">Pilih Status</option>
                <option value="Lunas">Lunas</option>
                <option value="Belum Lunas">Belum Lunas</option>

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
                <th>Nama Siswa</th>
                <th>Nominal</th>
                <th>Nama Nominal</th>
                <th>Tahun</th>
                <th>Bulan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembayarans as $index => $pembayaran)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td> {{ $pembayaran->no_invoice }}</td>
                    <td>{{ $pembayaran->siswa->nama }} - <b>{{ $pembayaran->siswa->kelas }} </b></td>
                    <td>{{ 'Rp. ' . number_format($pembayaran->biaya->nominal, 0, ',', '.') }}</td>
                    <td>{{ $pembayaran->biaya->nama_nominal }}</td>
                    <td>{{ $pembayaran->tahun }}</td>
                    <td>{{ $pembayaran->bulan }}</td>
                    <td>
                        @if ($pembayaran->status == 'Belum Lunas')
                            <span class="badge rounded-pill bg-danger">Belum Lunas</span>
                        @else
                            <span class="badge rounded-pill bg-success">Lunas</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">

                            @if ($pembayaran->status == 'Lunas' && $pembayaran->isSentKuitansi == 1)
                                <a href="{{ asset('bukti-pelunasan/' . $pembayaran->bukti_pelunasan) }}"
                                    class="btn btn-sm btn-secondary">Kuitansi</a>
                            @else
                                <button disabled class="btn btn-sm btn-secondary">Kuitansi Belum ada</button>
                            @endif

                            @if ($pembayaran->status == 'Belum Lunas')
                                <a href="{{ route('pembayaran.verifikasi', $pembayaran->id) }}"
                                    class="btn btn-sm btn-info">Verifikasi</a>
                            @else
                                <span class="btn btn-sm btn-success">Lunas</span>
                            @endif
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
                                '<td>' + value.keterangan + '</td>' +
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
