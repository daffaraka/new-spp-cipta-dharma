@extends('admin.admin-layout')
@section('content')
    <form action="{{ route('tagihan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf




        <div class="mb-3">
            <label for="">Keterangan</label>
            <input type="text" name="keterangan" class="form-control" value="{{ $tagihan->keterangan }}" required>
        </div>

        <div class="mb-3">
            <label for="">Biaya</label>
            <select type="text" name="biaya_id" id="biaya_id" class="form-control" required>
                @foreach ($biayas as $item)
                    <option value="{{ $item->id }}" {{ $tagihan->biaya_id == $item->id ? 'selected' : '' }}>{{ $item->nama_biaya }} -
                        Rp.{{ number_format($item->nominal) }} </option>
                @endforeach
            </select>
        </div>


        <div class="mb-3">
            <label for="">Siswa</label>
            <select type="text" name="user_id" id="user_id" class="form-control" required>
                @foreach ($siswas as $item)
                    <option value="{{ $item->id }}" {{ $tagihan->user_id == $item->id ? 'selected' : '' }}>{{ $item->nama }} - Kelas {{ $item->kelas }} </option>
                @endforeach
            </select>
        </div>


        <div class="mb-3">
            <label for="">Bulan</label>
            <select name="bulan" id="" class="form-control">
                <option value="Januari" {{ $tagihan->bulan == 'Januari' ? 'selected' : '' }}>Januari</option>
                <option value="Februari" {{ $tagihan->bulan == 'Februari' ? 'selected' : '' }}>Februari</option>
                <option value="Maret" {{ $tagihan->bulan == 'Maret' ? 'selected' : '' }}>Maret</option>
                <option value="April" {{ $tagihan->bulan == 'April' ? 'selected' : '' }}>April</option>
                <option value="Mei" {{ $tagihan->bulan == 'Mei' ? 'selected' : '' }}>Mei</option>
                <option value="Juni" {{ $tagihan->bulan == 'Juni' ? 'selected' : '' }}>Juni</option>
                <option value="Juli" {{ $tagihan->bulan == 'Juli' ? 'selected' : '' }}>Juli</option>
                <option value="Agustus" {{ $tagihan->bulan == 'Agustus' ? 'selected' : '' }}>Agustus</option>
                <option value="September" {{ $tagihan->bulan == 'September' ? 'selected' : '' }}>September</option>
                <option value="Oktober" {{ $tagihan->bulan == 'Oktober' ? 'selected' : '' }}>Oktober</option>
                <option value="November" {{ $tagihan->bulan == 'November' ? 'selected' : '' }}>November</option>
                <option value="Desember" {{ $tagihan->bulan == 'Desember' ? 'selected' : '' }}>Desember</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="">Tahun</label>
            <input type="number" name="tahun" class="form-control" value="{{ $tagihan->tahun }}" required>
        </div>


        <div class="mb-3">
            <label for="">Tanggal Terbit</label>
            <input type="date" name="tanggal_terbit"  class="form-control" value="{{ $tagihan->tanggal_terbit ?? date('Y-m-d') }}">
            <label> Jika dikosongi otomatis di isi hari ini </label>
        </div>

        <div class="mb-3">
            <label for="">Tanggal Lunas</label>
            <input type="date" name="tanggal_lunas"  class="form-control" value="{{ $tagihan->tanggal_lunas }}">
            <label> Boleh dikosongi </label>
        </div>


        <div class="mb-3">
            <label for="">Status Pelunasan</label>
            <select type="date" name="status"  class="form-control" >
                <option value="Belum Lunas" {{ $tagihan->status == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                <option value="Diajukan" {{ $tagihan->status == 'Diajukan' ? 'selected' : '' }}>Diajukan</option>
                <option value="Lunas" {{ $tagihan->status == 'Lunas' ? 'selected' : '' }}>Lunas</option>

            </select>
            <label> Boleh dikosongi </label>
        </div>

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
