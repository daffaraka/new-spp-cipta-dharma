@extends('admin.admin-layout')
@section('content')
    <div class="row">

        <div class="col-12">

            <h5 class="card-title">Tagihan yang belum anda bayarkan</h5>
            @if ($tagihanBelumLunas != null)
                <div class="row">
                    @foreach ($tagihanBelumLunas->tagihans as $tagihan)
                        <div class="col-xl-4 col-md-12 col-sm-12">
                            <div class="card my-3">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $tagihan->nama_invoice }} - <span
                                            class="badge p-1 px-2 rounded-pill bg-danger">{{ $tagihan->status }}</span> </h5>
                                    <p class="card-text">{{ $tagihan->tanggal_terbit }}</p>

                                    <div class="d-flex">
                                        <a href="{{ route('pelunasan.tagihan', $tagihan->id) }}"
                                            class="btn btn-success me-3">Lunasi</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tidak ada tagihan</h5>
                    </div>
                </div>
            @endif

        </div>


    </div>

    <div class="mt-5"></div>
    <hr>
    <div class="col-12 mt-5">

        <h5 class="card-title">Riwayat Pelunasan Terbaru</h5>
        @if ($tagihan_Lunas != null)
            <div class="row">
                @foreach ($tagihan_Lunas as $tagihan)
                    <div class="col-xl-4 col-md-12 col-sm-12">
                        <div class="card my-3">
                            <div class="card-body">
                                <h5 class="card-title">{{ $tagihan->nama_invoice }} - <span
                                        class="badge p-1 px-2 rounded-pill bg-success">{{ $tagihan->status }}</span> </h5>
                                <p class="card-text">{{ $tagihan->tanggal_terbit }}</p>

                                <div class="d-flex">
                                    <a href="{{ route('pelunasan.detailTagihan', $tagihan->id) }}"
                                        class="btn btn-primary me-3">Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif


    </div>


@endsection
