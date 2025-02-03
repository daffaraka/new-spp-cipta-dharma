@extends('admin.admin-layout')
@section('content')
    <div class="row mb-3">

        <div class="col-2">
            <label for="filterTahun">Filter Tahun</label>
            <select id="filterTahun" name="filter_tahun" class="form-control">
                <option value="">Pilih Tahun</option>
                @for ($year = 2020; $year <= date('Y'); $year++)
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
        <div class="col-2">
            <button type="submit" class="btn btn-outline-primary mt-4" id="btnFilter">
                Filter
            </button>

        </div>

        <div class="col-6">
            <div class="d-flex justify-content-end my-3">
                <div>
                    <a href="{{ route('laporanSpp.export') }}" class="btn btn-outline-success" id="btnExport">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                    {{-- <a href="{{ route('laporanSpp.import') }}" class="btn btn-warning" id="btnImport" data-bs-toggle="modal"
                        data-bs-target="#importModal">
                        <i class="fas fa-file-import"></i> Import Excel
                    </a> --}}
                    <a href="{{ route('laporanSpp.print') }}" class="btn btn-outline-dark" id="btnPrint">
                        <i class="fas fa-print"></i> Print
                    </a>

                </div>


            </div>
        </div>
    </div>
    <table class="table table-light" id="dataTables">
        <thead class="thead-light">
            <tr>
                <th>No</th>
                <th>No Invoice</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Total Bayar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporan_spp as $index => $spp)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $spp->no_invoice  ?? '-'}}</td>
                    <td>{{ $spp->siswa->nis ?? '-' }}</td>
                    <td>{{ $spp->siswa->nama ?? '-' }}</td>
                    <td>{{ $spp->siswa->kelas ?? '-' }}</td>
                    <td>{{ $spp->bulan ?? '-' }}</td>
                    <td>{{ $spp->tahun  ?? '-'}}</td>
                    <td>{{ 'Rp. ' . number_format($spp->total_bayar, 0, ',', '.') }}</td>
                    <td>
                        <div class="d-grid">
                            <a href="{{ route('laporanSpp.show', ['laporan_spp' => $spp->id]) }}"
                                class="btn btn-block btn-info my-1">Detail</a>
                        </div>

                    </td>
                </tr>
            @endforeach


        </tbody>
    </table>


    <div id="importModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="my-modal-title">Import Data SPP</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('laporanSpp.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" class="form-control" accept=".xls,.xlsx,.csv">
                        <button type="submit" class="btn btn-primary mt-3">Import</button>
                    </form>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

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
                    url: "{{ route('laporanPetugas.filter') }}",
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
                                '<td>' + value.nama + '</td>' +
                                '<td>' + value.email + '</td>' +
                                '<td>' + value.no_telp + '</td>' +
                                '<td>' + value.roles.map(role => role.name).join(
                                    ', ') + '</td>' +
                                '<td>' +
                                '<div class="d-grid">' +
                                '<a href="/laporan-petugas/' + value.id +
                                '" class="btn btn-block btn-info my-1">Detail</a>' +
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
