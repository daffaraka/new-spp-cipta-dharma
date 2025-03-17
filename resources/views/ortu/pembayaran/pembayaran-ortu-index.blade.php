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
    <table class="table table-light" id="dataTableOrtu">
        <thead class="thead-light">
            <tr>
                <th class="text-start w-auto" style="white-space: nowrap;">No</th>
                <th class="text-start w-auto">Nama</th>
                <th>No Invoice</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembayarans as $index => $pembayaran)
                <tr>
                    <td class="text-start w-auto" style="white-space: nowrap; width:100px;">{{ $index + 1 }}</td>
                    <td lass="text-start w-auto" style="white-space: nowrap; width:200px;">{{ $pembayaran->siswa->nama }}

                    <td class="text-start w-auto" style="white-space: nowrap; width:200px;">
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-{{ $index }}">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-{{ $index }}" aria-expanded="true"
                                        aria-controls="collapse-{{ $index }}">
                                        {{ $pembayaran->no_invoice }}
                                    </button>
                                </h2>
                                <div id="collapse-{{ $index }}" class="accordion-collapse collapse"
                                    aria-labelledby="heading-{{ $index }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="d-flex flex-row gap-3 mb-2">
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
                                                <span>{{ $pembayaran->no_invoice }}</span>
                                                <span>{{ $pembayaran->siswa->nama }}</span>
                                                <span>{{ $pembayaran->siswa->nis }}</span>
                                                <span>{{ $pembayaran->siswa->angkatan }}</span>
                                                <span>{{ $pembayaran->siswa->kelas }}</span>
                                                <span>{{ \Carbon\Carbon::parse($pembayaran->tanggal_terbit)->format('d-m-Y') }}</span>
                                                <span>{{ 'Rp. ' . number_format($pembayaran->biaya->nominal, 0, ',', '.') }}</span>
                                                <span>
                                                    @if ($pembayaran->status == 'Lunas')
                                                        <span
                                                            class="badge bg-success rounded-pill text-bg-success px-3">Lunas</span>
                                                    @elseif($pembayaran->status == 'Sedang Diverifikasi')
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
                                        <div class="d-inline mt-3">
                                            <a href="{{ route('ortu.pembayaran.show', $pembayaran->id) }}"
                                                class="btn btn-sm btn-warning">Detail</a>
                                            @if ($pembayaran->isSentKuitansi == '1')
                                                <a href="{{ route('tagihan.lihatKuitansi', $pembayaran->id) }}"
                                                    class="btn btn-sm btn-secondary">Lihat Kuitansi</a>
                                            @endif
                                            @if ($pembayaran->status == 'Belum Lunas' || $pembayaran->status == 'Kurang')
                                                <a href="{{ route('pelunasan.tagihan', $pembayaran->id) }}"
                                                    class="btn btn-sm btn-success me-3">Bayar</a>
                                            @endif
                                            {{-- @if ($pembayaran->status == 'Belum Lunas' || $pembayaran->status == 'Kurang') --}}
                                            {{-- @elseif ($pembayaran->status == 'Lebih') --}}
                                            @if ($pembayaran->status == 'Lebih')
                                                <div class="d-flex">
                                                    <a href="#" class="btn btn-success me-3" data-bs-toggle="modal"
                                                        data-bs-target="#buktiModal_{{ $index + 1 }}">Cek Bukti</a>
                                                </div>
                                            @endif
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

    <!-- modal -->
    @foreach ($pembayarans as $index => $pembayaran)
        <div class="modal fade" id="buktiModal_{{ $index + 1 }}" tabindex="-1" aria-labelledby="buktiModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="buktiModalLabel">Bukti Dikembalikan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="buktiImage" src="{{ asset('bukti-pelunasan/' . $pembayaran->bukti_lebih) }}"
                            class="img-fluid" alt="Bukti Transfer">
                    </div>
                </div>
            </div>
        </div>
    @endforeach
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
                    url: "{{ route('ortu.filterStatusPembayaran') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "filter_status": $('#filterStatus').val(),
                    },
                    success: function(data) {
                        $('#dataTableOrtu').DataTable().destroy();
                        $('#dataTableOrtu tbody').empty();

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

                            $('#dataTableOrtu tbody').append('<tr>' +
                                '<td class="text-start w-auto" style="white-space: nowrap; width:100px;">' +
                                (index + 1) + '</td>' +
                                '<td lass="text-start w-auto" style="white-space: nowrap; width:200px;">' +
                                value.siswa.nama +
                                '</td>' +
                                '<td class="text-start w-auto" style="white-space: nowrap; width:200px;">' +
                                '<div class="accordion" id="accordionExample">' +
                                '<div class="accordion-item">' +
                                '<h2 class="accordion-header" id="heading-' +
                                index + '">' +
                                '<button class="accordion-button" type="button" data-bs-toggle="collapse" ' +
                                'data-bs-target="#collapse-' + index +
                                '" aria-expanded="true" ' +
                                'aria-controls="collapse-' + index + '">' +
                                value.no_invoice +
                                '</button>' +
                                '</h2>' +
                                '<div id="collapse-' + index +
                                '" class="accordion-collapse collapse" ' +
                                'aria-labelledby="heading-' + index +
                                '" data-bs-parent="#accordionExample">' +
                                '<div class="accordion-body">' +
                                '<div class="d-flex flex-row gap-3 mb-2">' +
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
                                '<div class="d-inline mt-3">' +
                                '<a href="' + value.routeShow +
                                '" class="btn btn-sm btn-warning">Detail</a>' +
                                (value.isSentKuitansi == '1' ? '<a href="' + value
                                    .routeLihatKuitansi +
                                    '" class="btn btn-sm btn-secondary">Lihat Kuitansi</a>' :
                                    '') +
                                (value.status == 'Belum Lunas' || value.status ==
                                    'Kurang' ? '<a href="' + value.routeBayar +
                                    '" class="btn btn-sm btn-success me-3">Bayar</a>' :
                                    '') +
                                (value.status == 'Lebih' ?
                                    '<div class="d-flex"><a href="#" class="btn btn-success me-3" data-bs-toggle="modal" data-bs-target="#buktiModal_' +
                                    index + '">Cek Bukti</a></div>' : '') +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</td>' +

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
