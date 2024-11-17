@extends('admin.admin-layout')
@section('content')
    {{-- <div class="my-3">
        <a href="{{ route('petugas.create') }}" class="btn btn-primary">Tambah petugas</a>
    </div> --}}
    <table class="table table-light" id="dataTables">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Nama petugas</th>
                <th>Jabatan</th>
                {{-- <th>Tanggal Lahir</th>
                <th>Nama Wali</th>
                <th>Alamat</th> --}}
                <th>Email</th>
                <th>No Telfon</th>
                {{-- <th>Angkatan</th>
                <th>Kelas</th> --}}

                <th>Aksi</th> <!-- Kolom untuk aksi -->
            </tr>
        </thead>
        <tbody>
            @foreach ($petugas as $index => $petugas)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $petugas->nama }}</td>
                    <td>
                        <ul>
                            @foreach ($petugas->roles as $role)
                                <li>{{$role->name}}</li>
                            @endforeach
                        </ul>


                    </td>
                    {{-- <td>{{ $petugas->tanggal_lahir }}</td>
                    <td>{{ $petugas->nama_wali }}</td>
                    <td>{{ $petugas->alamat }}</td> --}}
                    <td>{{ $petugas->no_telp }}</td>
                    <td>{{ $petugas->email }}</td>
                    {{-- <td>{{ $petugas->angkatan }}</td>
                    <td>{{ $petugas->kelas }}</td> --}}
                    <td>
                        <div class="d-grid">
                            <a href="{{ route('petugas.show', ['petuga'=>$petugas->id]) }}" class="btn btn-block btn-info my-1">Detail</a>
                        </div>

                    </td>
                </tr>
            @endforeach


        </tbody>
    </table>
@endsection
