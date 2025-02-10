@extends('admin.admin-layout')
@section('content')
    <div class="row">

        <div class="col-12">
            <div class="card shadow border border-3">
                <div class="card-body p-4">
                    <h5 class="card-title">Tagihan yang belum anda bayarkan</h5>
                    @if ($tagihanBelumLunas != null)
                        <div class="row">
                            <table class="table table-light" id="dataTables">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Invoice</th>
                                        <th>Nama Siswa</th>
                                        <th>Nominal</th>
                                        <th>Nama Nominal</th>
                                        <th>Tahun</th>
                                        <th>Bulan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tagihanBelumLunas as $index => $tagihan)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $tagihan->no_invoice }}</td>
                                            <td>{{ $tagihan->siswa->nama }}</td>
                                            <td>{{ 'Rp. ' . number_format($tagihan->biaya->nominal, 0, ',', '.') }}</td>
                                            <td>{{ $tagihan->biaya->nama_biaya }}</td>
                                            <td>{{ $tagihan->tahun }}</td>
                                            <td>{{ $tagihan->bulan }}</td>
                                            <td>{{ $tagihan->status }}</td>
                                            <td>
                                                <a href="{{ route('pelunasan.tagihan', $tagihan->id) }}"
                                                    class="btn btn-info">Lunasi</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>


                        </div>
                    @else
                        <h5 class="card-title">Tidak ada tagihan</h5>
                    @endif


                </div>
            </div>

        </div>


    </div>

    <div class="mt-5"></div>
    <hr>
    <div class="col-12 my-5">
        <div class="card shadow border border-3">
            <div class="card-body p-4">
                <h5 class="card-title">Riwayat Pelunasan Terbaru</h5>
                @if ($tagihan_Lunas != null)
                    <table class="table table-light" id="dataTables">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Invoice</th>
                                <th>Nama Siswa</th>
                                <th>Nominal</th>
                                <th>Nama Nominal</th>
                                <th>Tahun</th>
                                <th>Bulan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tagihan_Lunas as $index => $tagihan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $tagihan->no_invoice }}</td>
                                    <td>{{ $tagihan->siswa->nama }}</td>
                                    <td>{{ 'Rp. ' . number_format($tagihan->biaya->nominal, 0, ',', '.') }}</td>
                                    <td>{{ $tagihan->biaya->nama_biaya }}</td>
                                    <td>{{ $tagihan->tahun }}</td>
                                    <td>{{ $tagihan->bulan }}</td>
                                    <td>{{ $tagihan->status }}</td>
                                    <td>
                                        <a href="{{ route('pelunasan.detailTagihan', $tagihan->id) }}"
                                            class="btn btn-primary">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>




    </div>


@endsection
