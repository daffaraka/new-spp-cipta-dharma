@extends('admin.admin-layout')
@section('content')
    <div class="my-3">
        <a href="{{ route('tagihan.create') }}" class="btn btn-primary">Tambah Data Tagihan</a>

    </div>

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
                @for ($year = 2019; $year <= date('Y'); $year++)
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
                <th>Keterangan</th>
                <th>NIS</th>
                <th>Nominal</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Status</th>
                <th>Aksi</th> <!-- Kolom untuk aksi -->
            </tr>
        </thead>
        <tbody>
            @foreach ($tagihans as $index => $tagihan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $tagihan->no_invoice }}</td>
                    <td>{{ $tagihan->keterangan }}</td>
                    <td>{{ $tagihan->siswa->nis }}</td>
                    <td>{{ 'Rp. ' . number_format($tagihan->biaya->nominal, 0, ',', '.') }}</td>
                    <td>{{ $tagihan->bulan }}</td>
                    <td>{{ $tagihan->tahun }}</td>
                    <td>
                        @if ($tagihan->status == 'Belum Lunas')
                            <span class="badge rounded-pill bg-danger">Belum Lunas</span>
                        @else
                            <span class="badge rounded-pill bg-success">Lunas</span>
                        @endif
                    </td>


                    <td>
                        {{-- <a href="{{ route('tagihan.kirim', $tagihan->id) }}" class="btn btn-outline-secondary">Kirim Invoice</a> --}}
                        <button class="btn btn-block btn-info my-1 btnDetailTagihan" data-bs-toggle="modal"
                            data-bs-target="#detailModal" data-id="{{ $tagihan->id }}">Detail</button>

                        <a href="{{ route('tagihan.edit', $tagihan->id) }}" class="btn btn-warning">Edit</a>

                        <form action="{{ route('tagihan.destroy', $tagihan->id) }}" method="POST" style="display:inline;">
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


    <div id="detailModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="my-modal-title">Detail Tagihan</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="detail-no-invoice">No Invoice</label>
                        <input type="text" id="detail-no-invoice" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="detail-keterangan">Keterangan</label>
                        <input type="text" id="detail-keterangan" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="detail-tanggal-terbit">Tanggal Terbit</label>
                        <input type="date" id="detail-tanggal-terbit" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="detail-tanggal-lunas">Tanggal Lunas</label>
                        <input type="date" id="detail-tanggal-lunas" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="detail-bukti-pelunasan">Bukti Pelunasan</label>
                        <input type="text" id="detail-bukti-pelunasan" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="detail-status">Status</label>
                        <input type="text" id="detail-status" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="detail-user-penerbit-id">User Penerbit</label>
                        <input type="text" id="detail-user-penerbit-id" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="detail-user-melunasi-id">User Melunasi</label>
                        <input type="text" id="detail-user-melunasi-id" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="detail-biaya-id">Biaya</label>
                        <input type="text" id="detail-biaya-id" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="detail-user-id">User</label>
                        <input type="text" id="detail-user-id" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="detail-bulan">Bulan</label>
                        <input type="text" id="detail-bulan" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="detail-tahun">Tahun</label>
                        <input type="text" id="detail-tahun" class="form-control" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#btnFilter').click(function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('tagihan.filter') }}",
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
                                    '<td>' + value.siswa.nis + '</td>' +
                                    '<td>' + 'Rp. ' + value.biaya.nominal + '</td>' +
                                    '<td>' + value.bulan + '</td>' +
                                    '<td>' + value.tahun + '</td>' +
                                    '<td>' +
                                    (value.status == 'Belum Lunas' ?
                                        '<span class="badge rounded-pill bg-danger">Belum Lunas</span>' :
                                        '<span class="badge rounded-pill bg-success">Lunas</span>'
                                    ) +
                                    '</td>' +
                                    '<td>' +
                                    '<div class="d-flex">' +
                                    '<a href="/tagihan/' + value.id +
                                    '" class="btn btn-info my-1">Detail</a>' +
                                    '<a href="/tagihan/' + value.id +
                                    '/edit" class="btn btn-warning my-1">Edit</a>' +
                                    '<form action="/tagihan/' + value.id +
                                    '" method="POST" style="display:inline;">' +
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


            $('.btnDetailTagihan').click(function(e) {
                var dataId = $(this).data('id');

                $.ajax({
                    type: "GET",
                    url: "{{ route('tagihan.show', ['tagihan' => ':id']) }}".replace(':id', dataId),
                    data: {
                        "id": dataId
                    },
                    dataType: "json",
                    success: function(response) {
                        $('#detail-no-invoice').val(response.no_invoice);
                        $('#detail-keterangan').val(response.keterangan);
                        $('#detail-tanggal-terbit').val(response.tanggal_terbit);
                        $('#detail-tanggal-lunas').val(response.tanggal_lunas);
                        $('#detail-bukti-pelunasan').val(response.bukti_pelunasan);
                        $('#detail-status').val(response.status);
                        $('#detail-user-penerbit-id').val(response.user_penerbit ? response.user_penerbit
                            .id : '');
                        $('#detail-user-penerbit-nama').val(response.user_penerbit ? response.user_penerbit
                            .nama : '');
                        $('#detail-user-melunasi-id').val(response.user_melunasi ? response.user_melunasi
                            .id : '');
                        $('#detail-user-melunasi-nama').val(response.user_melunasi ? response.user_melunasi
                            .nama : '');
                        $('#detail-biaya-id').val(response.biaya ? response.biaya.id : '');
                        $('#detail-biaya-nama').val(response.biaya ? response.biaya.nama_biaya : '');
                        $('#detail-user-id').val(response.user ? response.user.id : '');
                        $('#detail-user-nama').val(response.user ? response.user.nama : '');
                        $('#detail-bulan').val(response.bulan);
                        $('#detail-tahun').val(response.tahun);
                    }
                });
            });
        </script>
    @endpush
