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

    <table class="table table-light" id="dataTables">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Nama petugas</th>
                <th>NIP</th>
                {{-- <th>Jabatan</th> --}}
                <th>Username</th>
                <th>Email</th>
                <th>Jenis Kelamin</th>
                <th>Alamat</th>
                <th>No Telfon</th>
                <th>Role</th>
                <th>Status</th>
                <th>Aksi</th> <!-- Kolom untuk aksi -->
            </tr>
        </thead>
        <tbody>
            @foreach ($petugas as $index => $petugas)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $petugas->nama ?? '-'}}</td>
                    <td>{{ $petugas->nip ?? '-'}}</td>
                    {{-- <td>{{ $petugas->jabatan->nama_jabatan ?? '-' }}</td> --}}
                    <td>{{ $petugas->username }}</td>
                    <td>{{ $petugas->email }}</td>
                    <td>{{ $petugas->jenis_kelamin ?? '-' }}</td>
                    <td>{{ $petugas->alamat ?? '-' }}</td>
                    <td>{{ $petugas->no_telp ?? '-' }}</td>
                    <td>
                        <ul>
                            @foreach ($petugas->roles as $role)
                                <li>{{ $role->name }}</li>
                            @endforeach
                        </ul>


                    </td>
                    <td></td>
                    <td>
                        <div class="d-grid">
                            <a href="{{ route('petugas.show', ['petugas' => $petugas->id]) }}"
                                class="btn btn-block btn-info my-1">Detail</a>
                        </div>

                    </td>
                </tr>
            @endforeach


        </tbody>
    </table>
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
                            '<td>' + value.nama + '</td>' +
                            '<td>' + (value.nip ? value.nip : '-') + '</td>' +
                            // '<td>' + (value.jabatan ? value.jabatan.nama_jabatan : '-') + '</td>' +
                            '<td>' + (value.username  ? value.username : '-')+ '</td>' +
                            '<td>' + value.email + '</td>' +
                            '<td>' + (value.jenis_kelamin ? value.jenis_kelamin : '-') + '</td>' +
                            '<td>' + (value.alamat ? value.alamat : '-') + '</td>' +
                            '<td>' + (value.no_telp ? value.no_telp : '-') + '</td>' +
                            '<td>' +
                            '<ul>' +
                            value.roles.map(role => '<li>' + role.name + '</li>').join('') +
                            '</ul>' +
                            '</td>' +
                            '<td></td>' +
                            '<td>' +
                            '<div class="d-grid">' +
                            '<a href="/petugas/' + value.id +
                            '" class="btn btn-block btn-info my-1">Detail</a>' +
                            '</div>' +
                            '</td>' +
                            '</tr>');
                    });

                }
            });
        });
    </script>
@endpush
