@extends('admin.admin-layout')
@section('content')
    <div class="row mb-3">
        <div class="col-2">
            <label for="filterTahun">Filter Tahun</label>
            <select id="filterTahun" name="filter_tahun" class="form-control">
                <option value="">Pilih Tahun</option>
                @for ($year = 2015; $year <= date('Y'); $year++)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endfor
            </select>
        </div>
        <div class="col-2">
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
                <th>Tanggal</th>
                <th>Angkatan</th>
                <th>Kelas</th>
                <th>Nama Siswa</th>
                <th>Nominal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($riwayats as $index => $riwayat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $riwayat->created_at }}</td>
                    <td>{{ $riwayat->siswa->angkatan }}</td>
                    <td>{{ $riwayat->siswa->kelas }}</td>
                    <td>{{ $riwayat->siswa->nama }}</td>
                    <td>{{ 'Rp. ' . number_format($riwayat->biaya->nominal, 0, ',', '.') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            @if ($riwayat->status == 'Belum Lunas')
                                <button type="button" class="btn btn-sm btn-danger">Belum Lunas</button>
                            @elseif ($riwayat->status == 'Sedang Diverifikasi')
                                <button type="button" class="btn btn-sm btn-warning">Sedang Diverifikasi</button>
                            @else
                                <button type="button" class="btn btn-sm btn-success">Lunas</button>
                            @endif

                            @if ($riwayat->status == 'Lunas' && $riwayat->isSentKuitansi == 1)
                                <a href="{{ asset('bukti-pelunasan/' . $riwayat->bukti_pelunasan) }}"
                                    class="btn btn-sm btn-secondary">Kuitansi</a>
                            @else
                                <button disabled class="btn btn-sm btn-secondary">Kuitansi Belum ada</button>
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
                    url: "{{ route('ortu.filterRiwayatPembayaran') }}",
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
                                '<td>' + value.created_at + '</td>' +
                                '<td>' + value.siswa.angkatan + '</td>' +
                                '<td>' + value.siswa.kelas + '</td>' +
                                '<td>' + value.siswa.nama + '</td>' +
                                '<td> Rp. ' + value.biaya.nominal.toLocaleString('id-ID') + '</td>' +
                                '<td>' +
                                '<div class="d-flex gap-1">' +
                                (value.status == 'Belum Lunas' ?
                                    '<a href="{{ route("pembayaran.verifikasi", ' + value.id + ') }}" class="btn btn-sm btn-danger">Belum Lunas</a>' :
                                    '<span class="btn btn-sm btn-success">Lunas</span>'
                                ) +
                                (value.status == 'Lunas' && value.isSentKuitansi == 1 ?
                                    '<a href="{{ asset("bukti-pelunasan/' + value.bukti_pelunasan + '") }}" class="btn btn-sm btn-secondary">Kuitansi</a>' :
                                    '<button disabled class="btn btn-sm btn-secondary">Kuitansi Belum ada</button>'
                                ) +
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
