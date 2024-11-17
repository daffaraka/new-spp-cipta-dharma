@extends('admin.admin-layout')
@section('content')
    <form action="{{ route('siswa.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="">Nama Siswa</label>
            <input type="text" name="nama" class="form-control" required autocomplete="">
        </div>

        <div class="mb-3">
            <label for="">NIS</label>
            <input type="text" name="nis" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="">NISN</label>
            <input type="text" name="nisn" class="form-control" required>
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

        <div class="mb-3">
            <label for="">Agama</label>
            <select name="agama" id="" class="form-control" required>
                <option value="Islam">Islam</option>
                <option value="Kristen">Kristen</option>
                <option value="Katolik">Katolik</option>
                <option value="Hindu">Hindu</option>
                <option value="Budha">Budha</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="">No Telfon</label>
            <input type="number" name="no_telp" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="">Nama Wali</label>
            <input type="text" name="nama_wali" class="form-control" required>
        </div>


        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
