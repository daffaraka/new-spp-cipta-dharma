@extends('admin.admin-layout')
@section('content')
    <div class="my-3">
        <a href="{{ route('petugas.create') }}" class="btn btn-primary">Tambah petugas</a>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="filterAngkatan">Filter Data Petugas</label>
            <select id="filterJK" name="filter_jenis_kelamin" class="form-control">
                <option value="">Pilih Jenis Kelamin</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>

            </select>
        </div>
        <div class="col-4">
            <button class="btn btn-outline-primary mt-4" id="btnFilter">
                Filter
            </button>




        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-light" id="dataTables">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>NIP</th>
                    <th>Jabatan</th>
                    <th>Jenis Kelamin</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th> <!-- Kolom untuk aksi -->
                </tr>
            </thead>
            <tbody>
                @foreach ($petugas as $index => $petugas)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $petugas->nama ?? '-' }}</td>
                        <td>{{ $petugas->nip ?? '-' }}</td>
                        <td>{{ $petugas->jabatan->nama_jabatan ?? '-' }}</td>
                        <td>{{ $petugas->jenis_kelamin ?? '-' }}</td>
                        <td>
                            <ul>
                                @foreach ($petugas->roles as $role)
                                    <li>{{ $role->name }}</li>
                                @endforeach
                            </ul>


                        </td>
                        <td>{{ $petugas->status ?? '-' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('petugas.edit', ['petugas' => $petugas->id]) }}"
                                    class="btn btn-block btn-warning my-1">Edit</a>
                                <form action="{{ route('petugas.destroy', ['petugas' => $petugas->id]) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-block btn-danger my-1"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus petugas ini?')">Hapus</button>
                                </form>

                                <button class="btn btn-block btn-info my-1 btnDetailPetugas" data-bs-toggle="modal"
                                    data-bs-target="#detailModal" data-id = "{{ $petugas->id }}">Detail</button>
                            </div>

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
                    <h5 class="modal-title" id="my-modal-title">Detail Siswa</h5>
                    <button class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="detail-nama">Nama</label>
                        <input type="text" id="detail-nama" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="detail-nip">NIP</label>
                        <input type="text" id="detail-nip" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="detail-jabatan">Jabatan</label>
                        <input type="text" id="detail-jabatan" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="detail-jenis-kelamin">Jenis Kelamin</label>
                        <input type="text" id="detail-jenis-kelamin" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="detail-role">Role</label>
                        <ul id="detail-role-list">

                        </ul>
                    </div>
                    <div class="form-group mb-3">
                        <label for="detail-status">Status</label>
                        <input type="text" id="detail-status" class="form-control" readonly>
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
        $('#btnFilter').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('petugas.filterAgama') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "filter_jk": $('#filterJK').val(),
                },
                success: function(data) {
                    $('#dataTables tbody').empty();
                    $.each(data, function(index, value) {
                        $('#dataTables tbody').append('<tr>' +
                            '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td>' + (value.nama ? value.nama : '-') + '</td>' +
                            '<td>' + (value.nip ? value.nip : '-') + '</td>' +
                            '<td>' + (value.jabatan && value.jabatan.nama_jabatan ? value
                                .jabatan.nama_jabatan : '-') + '</td>' +
                            '<td>' + (value.jenis_kelamin ? value.jenis_kelamin : '-') +
                            '</td>' +
                            '<td>' +
                            '<ul>' +
                            value.roles.map(role => '<li>' + role.name + '</li>').join('') +
                            '</ul>' +
                            '</td>' +
                            '<td>' + (value.status ? value.status : '-') + '</td>' +
                            '<td>' +
                            '<div class="d-flex gap-1">' +
                            '<a href="/petugas/' + value.id +
                            '/edit" class="btn btn-block btn-warning my-1">Edit</a>' +
                            '<form action="/petugas/' + value.id +
                            '" method="POST" style="display:inline;">' +
                            '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
                            '<input type="hidden" name="_method" value="DELETE">' +
                            '<button type="submit" class="btn btn-block btn-danger my-1" onclick="return confirm(\'Apakah Anda yakin ingin menghapus petugas ini?\')">Hapus</button>' +
                            '</form>' +
                            '<a href="/petugas/' + value.id +
                            '" class="btn btn-block btn-info my-1">Detail</a>' +
                            '</div>' +
                            '</td>' +
                            '</tr>');
                    });

                }
            });
        });



        $(document).on('click', '.btnDetailPetugas', function(e) {

            // e.preventDefault();
            var dataId = $(this).data('id');

            $.ajax({
                type: "GET",
                url: "{{ route('petugas.show', ['petugas' => ':id']) }}".replace(':id', dataId),
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": dataId
                },
                dataType: "json",
                success: function(response) {
                    $('#detail-nama').val(response.nama);
                    $('#detail-nip').val(response.nip);
                    $('#detail-jabatan').val(response.jabatan.nama_jabatan);
                    $('#detail-jenis-kelamin').val(response.jenis_kelamin);
                    $('#detail-role-list').empty();
                    response.roles.map(role => {
                        $('#detail-role-list').append('<li>' + role.name + '</li>')
                    });
                    $('#detail-status').val(response.status);
                }
            });
        });
    </script>
@endpush
