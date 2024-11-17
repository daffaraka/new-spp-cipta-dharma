@extends('admin.admin-layout')
@section('content')
    <form action="{{ route('siswa.update',$siswa->id) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="mb-3">
            <label for="">Nama Siswa</label>
            <input type="text" name="nama" class="form-control" value="{{ $siswa->nama }}" required>
        </div>

        <div class="mb-3">
            <label for="">NIS</label>
            <input type="text" name="nis" class="form-control" value="{{ $siswa->nis }}" required>
        </div>

        <div class="mb-3">
            <label for="">NISN</label>
            <input type="text" name="nisn" class="form-control" value="{{ $siswa->nisn }}" required>
        </div>

        <div class="mb-3">
            <label for="">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $siswa->email }}" required>
        </div>

        <div class="mb-3">
            <label for="">Password</label>
            <input type="password" name="password" class="form-control" value="{{ $siswa->password }}" required>
        </div>

        <div class="mb-3">
            <label for="">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" class="form-control" value="{{ $siswa->tanggal_lahir }}" required>
        </div>

        <div class="mb-3">
            <label for="">Nama Wali</label>
            <input type="text" name="nama_wali" class="form-control" value="{{ $siswa->nama_wali }}" required>
        </div>

        <div class="mb-3">
            <label for="">Alamat</label>
            <input type="text" name="alamat" class="form-control" value="{{ $siswa->alamat }}" required>
        </div>

        <div class="mb-3">
            <label for="">No Telfon</label>
            <input type="text" name="no_telp" class="form-control" value="{{ $siswa->no_telp }}" required>
        </div>

        <div class="mb-3">
            <label for="">Angkatan</label>
            <input type="number" name="angkatan" class="form-control" value="{{ $siswa->angkatan }}" required>
        </div>
        <div class="mb-3">
            <label for="">Kelas</label>
            <select  name="kelas" class="form-control" required>
                <option value="10A" @if($siswa->kelas == '10A') selected @endif>10A</option>
                <option value="10B" @if($siswa->kelas == '10B') selected @endif>10B</option>
                <option value="10C" @if($siswa->kelas == '10C') selected @endif>10C</option>
                <option value="11A" @if($siswa->kelas == '11A') selected @endif>11A</option>
                <option value="11B" @if($siswa->kelas == '11B') selected @endif>11B</option>
                <option value="11C" @if($siswa->kelas == '11C') selected @endif>11C</option>
                <option value="12A" @if($siswa->kelas == '12A') selected @endif>12A</option>
                <option value="12B" @if($siswa->kelas == '12B') selected @endif>12B</option>
                <option value="12C" @if($siswa->kelas == '12C') selected @endif>12C</option>

            </select>
        </div>
        <div class="mb-3">
            <label for="">Jenis Kelamin</label>
            <select type="text" name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                <option value="Laki-laki" @if($siswa->jenis_kelamin == 'Laki-laki') selected @endif>Laki-laki</option>
                <option value="Perempuan" {{ $siswa->jenis_kelamin == 'Perempuan' ? 'selected' : '' }} >Perempuan</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="">Alamat</label>
            <input type="text" name="alamat" class="form-control" value="{{ $siswa->alamat }}" required>
        </div>

        <div class="mb-3">
            <label for="">Agama</label>
            <select name="agama" class="form-control" required>
                <option value="Islam" @if($siswa->agama == 'Islam') selected @endif>Islam</option>
                <option value="Kristen" @if($siswa->agama == 'Kristen') selected @endif>Kristen</option>
                <option value="Katolik" @if($siswa->agama == 'Katolik') selected @endif>Katolik</option>
                <option value="Hindu" @if($siswa->agama == 'Hindu') selected @endif>Hindu</option>
                <option value="Budha" @if($siswa->agama == 'Budha') selected @endif>Budha</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="">No Telfon</label>
            <input type="number" name="no_telp" class="form-control" value="{{ $siswa->no_telp }}" required>
        </div>


        <div class="mb-3">
            <label for="">Nama Wali</label>
            <input type="text" name="nama_wali" class="form-control" value="{{ $siswa->nama_wali }}" required>
        </div>


        <button type="submit" class="btn btn-primary">Submit</button>
    </form>



@endsection

