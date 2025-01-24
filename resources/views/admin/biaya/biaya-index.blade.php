@extends('admin.admin-layout')
@section('content')
    <div class="my-3">
        <a href="{{ route('biaya.create') }}" class="btn btn-primary">Tambah Data Biaya Baru</a>

    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="filterTahun">Filter Tahun</label>
            <select id="filterTahun" name="filter_tahun" class="form-control">
                <option value="">Pilih Tahun</option>
                @for ($year = 2020; $year <= date('Y'); $year++)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-4">
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
            <button type="button" class="btn btn-outline-primary mt-4" id="btnFilter">
                Filter
            </button>

        </div>
        <table class="table table-light" id="dataTables">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Nama Biaya</th>
                    <th>Nominal</th>
                    <th>Nama Nominal</th>
                    <th>Tahun </th>
                    <th>Bulan</th>
                    <th>Level</th>
                    <th>Aksi</th> <!-- Kolom untuk aksi -->
                </tr>
            </thead>
            <tbody>
                @foreach ($biayas as $index => $biaya)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $biaya->nama_biaya }}</td>
                        <td>Rp.{{ number_format($biaya->nominal) }}</td>
                        <td>{{ $biaya->nama_nominal }}</td>
                        <td>{{ $biaya->tahun }}</td>
                        <td>{{ $biaya->bulan }}</td>
                        <td>{{ $biaya->level }}</td>
                        {{-- <td>{{ \Carbon\Carbon::parse($biaya->created_at)->isoFormat('HH:mm:ss, dddd, D MMMM Y') }}</td> --}}
                        <td>
                            <button class="btn btn-block btn-info my-1" data-bs-toggle="modal" data-bs-target="#detailModal"
                                id="btnDetailBiaya" data-id="{{ $biaya->id }}">Detail</button>
                                <a href="{{ route('biaya.edit', $biaya->id) }}" class="btn btn-warning">Edit</a>

                            <form action="{{ route('biaya.destroy', $biaya->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data barang keluar ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                {{ $biayas->links() }}
            </tbody>
        </table>


        <div id="detailModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title">Detail Biaya</h5>
                        <button class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="detail-nama-biaya">Nama Biaya</label>
                            <input type="text" id="detail-nama-biaya" class="form-control" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label for="detail-nominal">Nominal</label>
                            <input type="text" id="detail-nominal" class="form-control" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label for="detail-nama-nominal">Nama Nominal</label>
                            <input type="text" id="detail-nama-nominal" class="form-control" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label for="detail-tahun">Tahun</label>
                            <input type="text" id="detail-tahun" class="form-control" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label for="detail-bulan">Bulan</label>
                            <input type="text" id="detail-bulan" class="form-control" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label for="detail-level">Level</label>
                            <input type="text" id="detail-level" class="form-control" readonly>
                        </div>
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
                    $.ajax({
                        url: "{{ route('biaya.filter') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "filter_tahun": $('#filterTahun').val(),
                            "filter_bulan": $('#filterBulan').val()
                        },
                        success: function(data) {
                            $('#dataTables tbody').empty();
                            $.each(data, function(index, value) {
                                $('#dataTables tbody').append('<tr>' +
                                    '<td>' + (index + 1) + '</td>' +
                                    '<td>' + value.nama_biaya + '</td>' +
                                    '<td>' + 'Rp. ' + value.nominal.toLocaleString(
                                        'id-ID') + '</td>' +
                                    '<td>' + value.nama_nominal + '</td>' +
                                    '<td>' + value.tahun + '</td>' +
                                    '<td>' + value.bulan + '</td>' +
                                    '<td>' + value.level + '</td>' +
                                    '<td>' +
                                    '<a href="biaya/' + value.id +
                                    '" class="btn  btn-info mx-1">Detail</a>' +
                                    '<a href="biaya/' + value.id +
                                    '/edit" class="btn btn-warning mx-1">Edit</a>' +

                                    '<form action="biaya/' + value.id +
                                    '" method="POST" style="display:inline;">' +
                                    '@csrf' +
                                    '@method('DELETE')' +
                                    '<button type="submit" class="btn btn-danger my-1" onclick="return confirm(\'Apakah Anda yakin ingin menghapus data biaya ini?\')">Hapus</button>' +
                                    '</form>' +
                                    '</td>' +
                                    '</tr>');
                            });
                        }
                    });
                });



                $('#btnDetailBiaya').click(function(e) {
                    var dataId = $(this).data('id');

                    $.ajax({
                        type: "GET",
                        url: "{{ route('biaya.show', ['biaya' => ':id']) }}".replace(':id', dataId),
                        data: {
                            "id": dataId
                        },
                        dataType: "json",
                        success: function(response) {
                            $('#detail-nama-biaya').val(response.nama_biaya);
                            $('#detail-nominal').val(response.nominal.toLocaleString('id-ID'));
                            $('#detail-nama-nominal').val(response.nama_nominal);
                            $('#detail-tahun').val(response.tahun);
                            $('#detail-bulan').val(response.bulan);
                            $('#detail-level').val(response.level);
                        }
                    });
                });
            });
        </script>
    @endpush
