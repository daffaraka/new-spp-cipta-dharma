@extends('admin.admin-layout')
@section('content')
    <div class="row">
        <div class="col">
            <div class="mb-3">
                <label for="">Bukti Pelunasan</label>
                {{-- @if ($pembayaran->bukti_pelunasan == null)
                    <h3>Orang tua belum melakukan pembayaran</h3> --}}
                {{-- @else
                    <img src="{{ asset('bukti-pelunasan/' . $pembayaran->bukti_pelunasan) }}" id="preview" width="100%"
                        alt="" class="img-thumbnail shadow">
                @endif --}}

                {{--  --}}
                @if ($pembayaran->bukti_pelunasan == null)
                    <h3>Orang tua belum melakukan pembayaran</h3>
                @elseif ($pembayaran->bukti_pelunasan !== null && $pembayaran->nominal == null)
                    <img src="{{ asset('bukti-pelunasan/' . $pembayaran->bukti_pelunasan) }}" id="preview" width="100%"
                        alt="" class="img-thumbnail shadow">
                @else
                    @foreach (json_decode($pembayaran->bukti_pelunasan, true) as $file)
                        <img src="{{ asset('bukti-pelunasan/' . $file) }}" id="preview" width="100%"
                        alt="" class="img-thumbnail shadow">
                    @endforeach
                @endif
                {{--  --}}

            </div>
        </div>

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

            <div class="mb-3">
                <label for="">No Invoice</label>
                <input type="text" name="no_invoice" class="form-control" value="{{ $pembayaran->no_invoice }}" readonly>
            </div>

            <div class="mb-3">
                <label for="">Nama Invoice</label>
                <input type="text" name="nama_tagihan" class="form-control" value="{{ $pembayaran->keterangan }}"
                    readonly>
            </div>

            <div class="mb-3">
                <label for="">NIS</label>
                <input type="text" name="nis" class="form-control" value="{{ $pembayaran->siswa->nis }}" readonly>
            </div>

            <div class="mb-3">
                <label for="">Siswa</label>
                <input type="text" name="nama_siswa" class="form-control"
                    value="{{ $pembayaran->siswa->nama }} - Kelas {{ $pembayaran->siswa->kelas }}" readonly>
            </div>

            <div class="mb-3">
                <label for="">Nominal</label>
                <input type="text" name="nominal" class="form-control"
                    value="Rp.{{ number_format($pembayaran->biaya->nominal) }}" readonly>
            </div>

            <div class="mb-3">
                <label for="">Bulan</label>
                <input type="text" name="bulan" class="form-control" value="{{ $pembayaran->bulan }}" readonly>
            </div>

            <div class="mb-3">
                <label for="">Tahun</label>
                <input type="text" name="tahun" class="form-control" value="{{ $pembayaran->tahun }}" readonly>
            </div>

            <div class="mb-3">
                <label for="">Tanggal Terbit</label>
                <input type="date" name="tanggal_terbit" class="form-control"
                    value="{{ $pembayaran->tanggal_terbit ?? date('Y-m-d') }}" readonly>
            </div>

            <div class="mb-3">
                <label for="">Tanggal Dilunasi</label>
                <input type="date" name="tanggal_lunas" class="form-control" value="{{ $pembayaran->tanggal_lunas }}"
                    readonly>
            </div>

            <div class="mb-3">
                <label for="">Status Pelunasan</label>
                <input type="text" name="status" class="form-control" value="{{ $pembayaran->status }}" readonly>
            </div>

            <div class="mb-3">
                <label for="">Admin Penerbit</label>
                <input type="text" name="admin_penerbit" class="form-control"
                    value="{{ $pembayaran->penerbit->nama ?? 'Belum ada' }}" readonly>
            </div>

            <div class="mb-3">
                <label for="">Admin Pelunasan</label>
                <input type="text" name="admin_pelunasan" class="form-control"
                    value="{{ $pembayaran->melunasi->nama ?? 'Belum ada' }}" readonly>
            </div>
        </div>
    </div>
@endsection
