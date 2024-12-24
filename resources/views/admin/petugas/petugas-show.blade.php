@extends('admin.admin-layout')
@section('content')
    <div class="mb-3">
        <label for="">Nama petugas</label>
        <input type="text" name="nama" class="form-control" value="{{ $laporan_spp->nama }}" readonly>
    </div>

    <div class="mb-3">
        <label for="">Email</label>
        <input type="email" name="email" class="form-control" value="{{ $laporan_spp->email }}" readonly>
    </div>

    <div class="mb-3">
        <label for="">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="form-control" value="{{ $laporan_spp->tanggal_lahir }}" readonly>
    </div>

    <div class="mb-3">
        <label for="">Jenis Kelamin</label>
        <input type="text" name="jenis_kelamin" class="form-control" value="{{ $laporan_spp->jenis_kelamin }}" readonly>
    </div>

    <div class="mb-3">
        <label for="">Alamat</label>
        <input type="text" name="alamat" class="form-control" value="{{ $laporan_spp->alamat }}" readonly>
    </div>

    <div class="mb-3">
        <label for="">Agama</label>
        <input type="text" name="agama" class="form-control" value="{{ $laporan_spp->agama }}" readonly>
    </div>

    <div class="mb-3">
        <label for="">No Telfon</label>
        <input type="number" name="no_telp" class="form-control" value="{{ $laporan_spp->no_telp }}" readonly>
    </div>

    <div class="mb-3">
        <label for="">Nama Wali</label>
        <input type="text" name="nama_wali" class="form-control" value="{{ $laporan_spp->nama_wali }}" readonly>
    </div>

    <a href="{{ url()->previous() }}" class="btn btn-primary">Kembali</a>
@endsection
