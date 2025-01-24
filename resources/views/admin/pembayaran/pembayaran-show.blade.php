@extends('admin.admin-layout')
@section('content')
    <div class="row">
        <div class="col">
            <div class="mb-3">
                <label for="">Bukti Pelunasan</label>
                @if ($pembayaran->bukti_pelunasan == null)
                    <h3>Orang tua belum melakukan pembayaran</h3>
                @else
                    <img src="{{ asset('bukti-pelunasan/' . $pembayaran->bukti_pelunasan) }}" id="preview" width="100%"
                        alt="" class="img-thumbnail shadow">
                @endif


            </div>
        </div>

        <div class="col">
            <div class="mb-3">
                <label for="">Nama Tagihan</label>
                <input type="text" name="nama_tagihan" class="form-control" value="{{ $pembayaran->keterangan }}"
                    readonly>
            </div>

            <div class="mb-3">
                <label for="">Biaya</label>
                <input type="text" name="biaya_id" class="form-control"
                    value="{{ $pembayaran->biaya->nama_biaya }} - Rp.{{ number_format($pembayaran->biaya->nominal) }}"
                    readonly>
            </div>

            <div class="mb-3">
                <label for="">Siswa</label>
                <input type="text" name="user_id" class="form-control"
                    value="{{ $pembayaran->siswa->nama }} - Kelas {{ $pembayaran->siswa->kelas }}" readonly>
            </div>

            <div class="mb-3">
                <label for="">Tanggal Terbit</label>
                <input type="date" name="tanggal_terbit" class="form-control"
                    value="{{ $pembayaran->tanggal_terbit ?? date('Y-m-d') }}" readonly>
            </div>

            <div class="mb-3">
                <label for="">Tanggal Lunas</label>
                <input type="date" name="tanggal_lunas" class="form-control" value="{{ $pembayaran->tanggal_lunas }}"
                    readonly>
            </div>

            <div class="mb-3">
                <label for="">Status Pelunasan</label>
                <input type="text" name="status" class="form-control" value="{{ $pembayaran->status }}" readonly>
            </div>
        </div>
    </div>
@endsection
