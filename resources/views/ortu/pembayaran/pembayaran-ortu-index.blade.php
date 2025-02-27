@extends('admin.admin-layout')
@section('content')
    <div class="row mb-3">
        <div class="col-md-2">
            <label for="filterStatus">Filter Status</label>
            <select id="filterStatus" name="filter_status" class="form-control">
                <option value="">Pilih Status</option>
                <option value="Lunas">Lunas</option>
                <option value="Sedang Diverifikasi">Sedang Diverifikasi</option>
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
                <th>NIS</th>
                <th>Nominal</th>
                <th>Keterangan</th>
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
                    <td>{{ $pembayaran->siswa->nis }} </td>

                    <td>{{ 'Rp. ' . number_format($pembayaran->biaya->nominal, 0, ',', '.') }}</td>
                    <td>{{ $pembayaran->biaya->nama_nominal }}</td>
                    <td>{{ $pembayaran->tahun }}</td>
                    <td>{{ $pembayaran->bulan }}</td>
                    <td>
                        @if ($pembayaran->status == 'Belum Lunas')
                            <span class="badge rounded-pill bg-danger">Belum Lunas</span>
                        @elseif ($pembayaran->status == 'Sedang Diverifikasi')
                            <span class="badge rounded-pill bg-warning">Sedang Diverifikasi</span>
                        @else
                            <span class="badge rounded-pill bg-success">Lunas</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">

                            <a href="{{ route('ortu.pembayaran.show', $pembayaran->id) }}"
                                class="btn btn-sm btn-warning">Detail</a>

                            @if ($pembayaran->isSentKuitansi == '1')
                                <a href="{{ route('tagihan.lihatKuitansi', $pembayaran->id) }}"
                                    class="btn btn-sm btn-secondary">Lihat Kuitansi</a>
                            @endif

                            @if ($pembayaran->status == 'Belum Lunas')
                                <a href="{{ route('pelunasan.tagihan', $pembayaran->id) }}"
                                    class="btn btn-sm btn-success me-3">Bayar</a>
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
                    url: "{{ route('ortu.filterStatusPembayaran') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "filter_status": $('#filterStatus').val(),
                    },
                    success: function(data) {
                        $('#dataTables tbody').empty();
                        $.each(data, function(index, value) {
                            var actionButtons = '';

                            // Perlu menggunakan kondisi JavaScript, bukan sintaks Blade
                            if (value.isSentKuitansi == '1') {
                                actionButtons += '<a href="' +
                                    "{{ route('tagihan.lihatKuitansi', '') }}/" + value
                                    .id +
                                    '" class="btn btn-sm btn-secondary">Lihat Kuitansi</a>';
                            }

                            if (value.status == 'Belum Lunas') {
                                actionButtons += '<a href="' +
                                    "{{ route('pelunasan.tagihan', '') }}/" + value
                                    .id +
                                    '" class="btn btn-sm btn-success me-3">Bayar</a>';
                            }

                            $('#dataTables tbody').append('<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td>' + value.no_invoice + '</td>' +
                                '<td>' + value.siswa.nama + '-' + value.siswa
                                .kelas + '</td>' +
                                '<td>' + value.siswa.nis + '</td>' +

                                '<td> Rp. ' + value.biaya.nominal.toLocaleString(
                                    'id-ID') + '</td>' +
                                '<td>' + value.biaya.nama_nominal + '</td>' +
                                '<td>' + value.tahun + '</td>' +
                                '<td>' + value.bulan + '</td>' +
                                '<td>' +
                                (value.status == 'Belum Lunas' ?
                                    '<span class="badge rounded-pill bg-danger">Belum Lunas</span>' :
                                    (value.status == 'Sedang Diverifikasi' ?
                                        '<span class="badge rounded-pill bg-warning">Sedang Diverifikasi</span>' :
                                        '<span class="badge rounded-pill bg-success">Lunas</span>'
                                    )
                                ) + '</td>' +
                                '<td>' +
                                '<div class="d-flex gap-1">' +
                                '<a href="ortu/pembayaran/' + value.id + '" class="btn btn-sm btn-warning">Detail</a>' +
                                actionButtons +
                                '</div> </td>' +
                                '</tr>');
                        });
                    }
                });
            });
        });
    </script>
@endpush
