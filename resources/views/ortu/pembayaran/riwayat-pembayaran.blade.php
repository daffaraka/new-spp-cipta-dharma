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
    <div class="table-responsive">
        <table class="table table-light" id="dataTableOrtu">
            <thead class="thead-light">
                <tr>
                    <th class="d-flex justify-content-start w-auto" style="white-space: nowrap;">No</th>
                    <th class="text-start w-auto" style="white-space: nowrap;">Nama</th>
                    <th>No Invoice</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($riwayats as $index => $riwayat)
                    <tr>
                        <td class="text-start w-auto" style="white-space: nowrap; width:100px;">{{ $index + 1 }}</td>
                        <td lass="text-start w-auto" style="white-space: nowrap; width:200px;">{{ $riwayat->siswa->nama }}
                        </td>
                        <td>

                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading-{{ $index }}">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse-{{ $index }}" aria-expanded="true"
                                            aria-controls="collapse-{{ $index }}">
                                            {{ $riwayat->no_invoice }}
                                        </button>
                                    </h2>
                                    <div id="collapse-{{ $index }}" class="accordion-collapse collapse"
                                        aria-labelledby="heading-{{ $index }}" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="d-flex flex-row gap-3">
                                                <div class="d-flex flex-column text-start">
                                                    <span class="fw-bold">No Invoice:</span>
                                                    <span class="fw-bold">Nama Siswa:</span>
                                                    <span class="fw-bold">NIS:</span>
                                                    <span class="fw-bold">Angkatan:</span>
                                                    <span class="fw-bold">Kelas:</span>
                                                    <span class="fw-bold">Tanggal:</span>
                                                    <span class="fw-bold">Nominal:</span>
                                                    <span class="fw-bold">Status:</span>

                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span>{{ $riwayat->no_invoice }}</span>
                                                    <span>{{ $riwayat->siswa->nama }}</span>
                                                    <span>{{ $riwayat->siswa->nis }}</span>
                                                    <span>{{ $riwayat->siswa->angkatan }}</span>
                                                    <span>{{ $riwayat->siswa->kelas }}</span>
                                                    <span>{{ \Carbon\Carbon::parse($riwayat->tanggal_terbit)->format('d-m-Y') }}</span>
                                                    <span>{{ 'Rp. ' . number_format($riwayat->biaya->nominal, 0, ',', '.') }}</span>
                                                    <span>
                                                        @if ($riwayat->status == 'Lunas')
                                                            <span
                                                                class="badge bg-success rounded-pill text-bg-success px-3">Lunas</span>
                                                        @elseif($riwayat->status == 'Sedang Diverifikasi')
                                                            <span
                                                                class="badge bg-warning  rounded-pill text-bg-warning px-3">Diproses</span>
                                                        @else
                                                            <span
                                                                class="badge bg-danger  rounded-pill text-bg-danger px-3">Belum
                                                                Lunas</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @foreach ($riwayats as $index => $riwayat)
        <div class="modal fade" id="buktiModal_{{ $index + 1 }}" tabindex="-1" aria-labelledby="buktiModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="buktiModalLabel">Bukti Dikembalikan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="buktiImage" src="{{ asset('bukti-pelunasan/' . $riwayat->bukti_lebih) }}"
                            class="img-fluid" alt="Bukti Transfer">
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    {{--  --}}
@endsection
@push('scripts')
    <script>

        $(document).ready(function() {
            $('#dataTableOrtu').DataTable({
            "autoWidth": false, // Matikan auto width
            "columnDefs": [{
                    "width": "1%",
                    "targets": 0
                } // Atur width kolom pertama
            ]
        });

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
                        $('#dataTableOrtu').DataTable().destroy();
                        $('#dataTableOrtu tbody').empty();
                        $.each(data, function(index, value) {
                            $('#dataTableOrtu tbody').append('<tr>' +
                                '<td class="text-start w-auto" style="white-space: nowrap; width:100px;">' +
                                (index + 1) + '</td>' +
                                '<td class="text-start w-auto" style="white-space: nowrap; width:200px;">' +
                                value.siswa.nama + '</td>' +
                                '<td>' +
                                '<div class="accordion" id="accordionExample">' +
                                '<div class="accordion-item">' +
                                '<h2 class="accordion-header" id="heading-' +
                                index + '">' +
                                '<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-' +
                                index +
                                '" aria-expanded="true" aria-controls="collapse-' +
                                index + '">' +
                                value.no_invoice +
                                '</button>' +
                                '</h2>' +
                                '<div id="collapse-' + index +
                                '" class="accordion-collapse collapse" aria-labelledby="heading-' +
                                index + '" data-bs-parent="#accordionExample">' +
                                '<div class="accordion-body">' +
                                '<div class="d-flex flex-row gap-3">' +
                                '<div class="d-flex flex-column text-start">' +
                                '<span class="fw-bold">No Invoice:</span>' +
                                '<span class="fw-bold">Nama Siswa:</span>' +
                                '<span class="fw-bold">NIS:</span>' +
                                '<span class="fw-bold">Angkatan:</span>' +
                                '<span class="fw-bold">Kelas:</span>' +
                                '<span class="fw-bold">Tanggal:</span>' +
                                '<span class="fw-bold">Nominal:</span>' +
                                '<span class="fw-bold">Status:</span>' +
                                '</div>' +
                                '<div class="d-flex flex-column">' +
                                '<span>' + value.no_invoice + '</span>' +
                                '<span>' + value.siswa.nama + '</span>' +
                                '<span>' + value.siswa.nis + '</span>' +
                                '<span>' + value.siswa.angkatan + '</span>' +
                                '<span>' + value.siswa.kelas + '</span>' +
                                '<span>' + value.tanggal_terbit + '</span>' +
                                '<span> Rp. ' + value.biaya.nominal.toLocaleString(
                                    'id-ID') + '</span>' +
                                '<span>' +
                                (value.status == 'Lunas' ?
                                    '<span class="badge bg-success rounded-pill text-bg-success px-3">Lunas</span>' :
                                    (value.status == 'Sedang Diverifikasi' ?
                                        '<span class="badge bg-warning  rounded-pill text-bg-warning px-3">Diproses</span>' :
                                        '<span class="badge bg-danger  rounded-pill text-bg-danger px-3">Belum Lunas</span>'
                                    )
                                ) +
                                '</span>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</tr>');
                        });


                        $('#dataTableOrtu').DataTable({
                            "autoWidth": false, // Matikan auto width
                            "columnDefs": [{
                                    "width": "1%",
                                    "targets": 0
                                } // Atur width kolom pertama
                            ],
                            "paging": true,
                            "lengthMenu": [10, 25, 50, 100], // Pilihan entries per page
                            "pageLength": 10, // Default 10 entries per page
                            "ordering": false, // Nonaktifkan sorting jika tidak diperlukan
                            "searching": true, // Aktifkan fitur pencarian
                            "info": true, // Tampilkan informasi jumlah data
                            "language": {
                                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                                "zeroRecords": "Tidak ada data ditemukan",
                                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                                "infoEmpty": "Tidak ada data tersedia",
                                "infoFiltered": "(disaring dari _MAX_ total data)",
                                "search": "Cari:",
                                "paginate": {
                                    "first": "<<",
                                    "last": ">>",
                                    "next": ">",
                                    "previous": "<"
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
