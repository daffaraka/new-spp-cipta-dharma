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
                        @elseif ($pembayaran->status == 'Sedang Diverifikasi')
                            <span class="badge rounded-pill bg-warning">Sedang Diverifikasi</span>
                        @else
                            <span class="badge rounded-pill bg-success">Lunas</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">

                            {{-- @if ($pembayaran->status == 'Lunas' && $pembayaran->isSentKuitansi == 1)
                                <a href="{{ asset('bukti-pelunasan/' . $pembayaran->bukti_pelunasan) }}"
                                    class="btn btn-sm btn-secondary">Kuitansi</a>
                            @else
                                <button disabled class="btn btn-sm btn-secondary">Kuitansi Belum ada</button>
                            @endif --}}


                            @if ($pembayaran->status == 'Belum Lunas')
                                <div class="d-flex">
                                    <a href="{{ route('pelunasan.tagihan', $pembayaran->id) }}"
                                        class="btn btn-success me-3">Bayar</a>
                                </div>
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
                            $('#dataTables tbody').append('<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td>' + value.no_invoice + '</td>' +
                                '<td>' + value.siswa.nama + '-' + value.siswa
                                .kelas + '</td>' +
                                '<td> Rp. ' + value.biaya.nominal.toLocaleString(
                                    'id-ID') + '</td>' +
                                '<td>' + value.biaya.nama_nominal + '</td>' +
                                '<td>' + value.tahun + '</td>' +
                                '<td>' + value.bulan + '</td>' +
                                '<td>' + (value.status == 'Belum Lunas' ?
                                    '<span class="badge rounded-pill bg-danger">Belum Lunas</span>' :
                                    '<span class="badge rounded-pill bg-success">Lunas</span>'
                                ) + '</td>' +
                                '<td>' +
                                '<div class="d-flex gap-1">' +
                                (value.status == 'Lunas' && value.isSentKuitansi ==
                                    1 ?
                                    '<a href="{{ asset("bukti-pelunasan/' + value.bukti_pelunasan + '") }}" class="btn btn-sm btn-secondary">Kuitansi</a>' :
                                    '<button disabled class="btn btn-sm btn-secondary">Kuitansi Belum ada</button>'
                                )
                                '</div>' +
                                '</tr>');
                        });
                    }
                });
            });
        });
    </script>
@endpush
