@extends('admin.admin-layout')
@section('content')
    <div class="my-3">
        <a href="{{ route('petugas.create') }}" class="btn btn-primary">Tambah petugas</a>
    </div>
    <table class="table table-light" id="dataTables">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Nama petugas</th>
                <th>NIP</th>
                <th>Jabatan</th>
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
                    <td>{{ $petugas->nama }}</td>
                    <td>{{ $petugas->nip }}</td>
                    <td>{{ $petugas->jabatan->nama_jabatan ?? '-' }}</td>
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
