@extends('admin.admin-layout')
@section('content')
    <form action="{{ route('petugas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="">Nama petugas</label>
            <input type="text" name="nama" class="form-control" required autocomplete="">
        </div>

        <div class="mb-3">
            <label for="">Jabatan petugas</label>
            <select name="roles" class="form-control" required>
                <option value="">Pilih Jabatan</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{$role->name}}</option>
                @endforeach

            </select>
        </div>

        <div class="mb-3">
            <label for="">NIP</label>
            <input type="number" name="nip" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="">Jenis Kelamin</label>
            <select type="text" name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="">Alamat</label>
            <input type="text" name="alamat" class="form-control" required>
        </div>

        {{-- <div class="mb-3">
            <label for="">Agama</label>
            <select name="agama" id="" class="form-control" required>
                <option value="Islam">Islam</option>
                <option value="Kristen">Kristen</option>
                <option value="Katolik">Katolik</option>
                <option value="Hindu">Hindu</option>
                <option value="Budha">Budha</option>
            </select>
        </div> --}}

        <div class="mb-3">
            <label for="">No Telfon</label>
            <input type="number" name="no_telp" class="form-control" required>
        </div>


        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
