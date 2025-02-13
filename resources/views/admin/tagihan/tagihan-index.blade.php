@extends('admin.admin-layout')
@section('content')
    <div class="d-flex justify-content-between my-3">
        <div class="">
            <a href="{{ route('tagihan.create') }}" class="btn btn-primary">Tambah Data Tagihan</a>

        </div>

        <div>
            <a href="{{ route('siswa.export') }}" class="btn btn-outline-success disabled" id="btnExport">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <a href="#" class="btn btn-warning disabled" id="btnImport" data-bs-toggle="modal"
                data-bs-target="#importModal">
                <i class="fas fa-file-import"></i> Import Excel
            </a>
            {{-- <a href="{{ route('siswa.print') }}" class="btn btn-outline-dark" id="btnPrint">
                <i class="fas fa-print"></i> Print
            </a> --}}

        </div>
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



    <div class="table-responsive">
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
                        <td>{{ $tagihan->siswa->nis ?? '-' }}</td>
                        <td>{{ 'Rp. ' . number_format($tagihan->biaya->nominal, 0, ',', '.') }}</td>
                        <td>{{ $tagihan->bulan }}</td>
                        <td>{{ $tagihan->tahun }}</td>
                        <td>
                            @if ($tagihan->status == 'Belum Lunas')
                                <span class="badge rounded-pill bg-danger">Belum Lunas</span>
                            @elseif ($tagihan->status == 'Sedang Diverifikasi')
                                <span class="badge rounded-pill bg-warning">Sedang Diverifikasi</span>
                            @else
                                <span class="badge rounded-pill bg-success">Lunas</span>
                            @endif
                        </td>


                        <td>
                            {{-- <a href="{{ route('tagihan.kirim', $tagihan->id) }}" class="btn btn-outline-secondary">Kirim Invoice</a> --}}
                            <button class="btn btn-block btn-info my-1 btnDetailTagihan" data-bs-toggle="modal"
                                data-bs-target="#detailModal" data-id="{{ $tagihan->id }}">Detail</button>

                            <a href="{{ route('tagihan.edit', $tagihan->id) }}" class="btn btn-warning">Edit</a>

                            <form action="{{ route('tagihan.destroy', $tagihan->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data tagihan keluar ini?')">Hapus</button>
                            </form>


                            @if ($tagihan->status == 'Lunas')
                                <a href="{{route('tagihan.sendInvoice',['tagihan' => $tagihan->id])}}"
                                    class="btn btn-dark mx-1 {{ $tagihan->status == 'Belum Lunas' ? 'disabled' : '' }}">Kirim
                                    Invoice</a>
                            @endif

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>


    <div id="detailModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="my-modal-title">Detail Tagihan</h5>
                    <button class="close" data-bs-dismiss="modal" aria-label="Close">
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
                        <br>
                        <a href="" id="detail-bukti-pelunasan-link" target="_blank"> Click to view
                        </a>
                    </div>
                    <div class="form-group mb-3">
                        <label for="detail-status">Status</label>
                        <input type="text" id="detail-status" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="detail-user-penerbit">User Penerbit</label>
                        <input type="text" id="detail-user-penerbit" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="detail-user-melunasi">User Melunasi</label>
                        <input type="text" id="detail-user-melunasi" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="detail-biaya">Biaya</label>
                        <input type="text" id="detail-biaya" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="detail-user-id">User</label>
                        <input type="text" id="detail-user" class="form-control" readonly>
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

        <!-- Modal -->
        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
            aria-hidden="true">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">
                            Import Data Tagihan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" class="form-control" accept=".xls,.xlsx,.csv">
                            <button type="submit" class="btn btn-primary mt-3">Import</button>
                        </form> --}}
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
                                    '<div class="d-flex gap-1">' +
                                    '<button class="btn btn-block btn-info my-1 btnDetailTagihan" data-id="' +
                                    value.id +
                                    '" data-bs-toggle="modal" data-bs-target="#detailModal">Detail</button>' +
                                    '<a href="/tagihan/' + value.id +
                                    '/edit" class="btn btn-warning my-1">Edit</a>' +
                                    '<form action="/tagihan/' + value.id +
                                    '" method="POST" style="display:inline;">' +
                                    '@csrf' +
                                    '@method('DELETE')' +
                                    '<button type="submit" class="btn btn-danger my-1" onclick="return confirm(\'Apakah Anda yakin ingin menghapus data tagihan keluar ini?\')">Hapus</button>' +
                                    (value.status == 'Belum Lunas' ?
                                        '<a href="/tagihan/kirim/' + value.id +
                                        '" class="btn btn-dark">Kirim Invoice</a>' :
                                        '<a href="" class="btn btn-dark disabled">Kirim Invoice</a>'
                                        ) +
                                    '</form>' +
                                    '</div>' +
                                    '</td>' +
                                    '</tr>');
                            });
                        }
                    });
                });
            });


            $(document).on('click', '.btnDetailTagihan', function(e) {
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
                        $('#detail-bukti-pelunasan-link').attr('href', response.bukti_pelunasan ?
                            "{{ asset('bukti-pelunasan') }}/" + response.bukti_pelunasan : '');
                        $('#detail-status').val(response.status);
                        $('#detail-user-penerbit').val(response.penerbit ? response.penerbit
                            .nama : '-');
                        $('#detail-user-melunasi').val(response.melunasi ? response.melunasi
                            .nama : '-');
                        $('#detail-biaya').val(response.biaya ? response.biaya.nama_biaya : '-');
                        $('#detail-user').val(response.user ? response.siswa.nama : '-');
                        $('#detail-bulan').val(response.bulan);
                        $('#detail-tahun').val(response.tahun);
                    }
                });
            });
        </script>
    @endpush
