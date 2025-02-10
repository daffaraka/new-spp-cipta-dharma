@extends('admin.admin-layout')
@section('content')
    <form action="{{ route('tagihan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf



        <div class="mb-3">
            <strong><label for="" class="my-1">Keterangan</label></strong>
            <input type="text" name="keterangan" class="form-control my-1" required>
        </div>

        <div class="mb-3">
            <strong><label for="" class="my-1">Biaya</label></strong>
            <select type="text" name="biaya_id" id="biaya_id" class="form-control my-1" required>
                @foreach ($biayas as $item)
                    <option value="{{ $item->id }}">{{ $item->nama_biaya }} -
                        Rp.{{ number_format($item->nominal) }} </option>
                @endforeach
            </select>
        </div>


        <div class="mb-3">
            <strong><label for="" class="my-1">Ditujukan kepada</label></strong>
            <select type="text" name="user_id" id="user_id" class="form-control my-1" required>
                @foreach ($siswas as $item)
                    <option value="{{ $item->id }}">{{$item->id}} - {{ $item->nama }} - Kelas {{ $item->kelas }} </option>
                @endforeach
            </select>
        </div>


        <div class="mb-3">
            <strong><label for="" class="my-1">Bulan</label></strong>
            <select name="bulan" id="" class="form-control my-1">
                <option value="Januari">Januari</option>
                <option value="Februari">Februari</option>
                <option value="Maret">Maret</option>
                <option value="April">April</option>
                <option value="Mei">Mei</option>
                <option value="Juni">Juni</option>
                <option value="Juli">Juli</option>
                <option value="Agustus">Agustus</option>
                <option value="September">September</option>
                <option value="Oktober">Oktober</option>
                <option value="November">November</option>
                <option value="Desember">Desember</option>
            </select>
        </div>

        <div class="mb-3">
            <strong><label for="" class="my-1">Tahun</label></strong>
            <input type="number" name="tahun" class="form-control my-1" required>
        </div>

        {{-- <div class="mb-3">
            <strong><label for="">Tanggal Terbit</label></strong>
            <input type="date" name="tanggal_terbit" class="form-control">
            <p class="fw-bold"> Jika dikosongi otomatis di isi hari ini </p>
        </div>

        <div class="mb-3">
            <strong><label for="">Tanggal Lunas</label></strong>
            <input type="date" name="tanggal_lunas" class="form-control">
            <label> Boleh dikosongi </label>
        </div>

        <div class="mb-3">
            <strong><label for="">Status Pelunasan</label></strong>
            <select name="status" class="form-control">
                <option value="Belum Lunas">Belum Lunas</option>
                <option value="Lunas">Lunas</option>

            </select>
        </div> --}}

        <button type="submit" class="btn btn-primary my-3">Submit</button>
    </form>
@endsection

@push('scrirpts')
    {{-- <script>
    document.getElementById('barang_id').addEventListener('change', function() {
        var barangId = this.value;

        // Lakukan request ke route untuk mendapatkan detail barang
        fetch('/barang-detail/' + barangId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update form input berdasarkan data barang yang didapatkan
            document.getElementById('stok_sekarang').value = data.stok_sekarang;
            document.getElementById('stok_minimal').value = data.stok_minimal;
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
</script> --}}
@endpush
