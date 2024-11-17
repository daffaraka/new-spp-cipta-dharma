@extends('admin.admin-layout')
@section('content')
    <div class="my-3">
        <a href="{{ route('biaya.create') }}" class="btn btn-primary">Tambah Data Biaya Baru</a>

    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="filterTahun">Filter Tahun</label>
            <select id="filterTahun" name="filter_tahun" class="form-control">
                <option value="">Pilih Tahun</option>
                @for ($year = 2020; $year <= date('Y'); $year++)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-4">
            <label for="filterBulan">Filter Bulan</label>
            <select id="filterBulan" class="form-control" name="filter_bulan">
                <option value="">Pilih Bulan</option>
                <option value="1">Januari</option>
                <option value="2">Februari</option>
                <option value="3">Maret</option>
                <option value="4">April</option>
                <option value="5">Mei</option>
                <option value="6">Juni</option>
                <option value="7">Juli</option>
                <option value="8">Agustus</option>
                <option value="9">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>
        </div>
        <div class="col-4">
            <button type="button" class="btn btn-outline-primary mt-4" id="btnFilter">
                Filter
            </button>

        </div>
        <table class="table table-light" id="dataTables">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Nama Biaya</th>
                    <th>Nominal</th>
                    <th>Nama Nominal</th>
                    <th>Tahun </th>
                    <th>Bulan</th>
                    <th>Level</th>
                    <th>Aksi</th> <!-- Kolom untuk aksi -->
                </tr>
            </thead>
            <tbody>
                @foreach ($biayas as $index => $biaya)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $biaya->nama_biaya }}</td>
                        <td>Rp.{{ number_format($biaya->nominal) }}</td>
                        <td>{{ $biaya->nama_nominal }}</td>
                        <td>{{ $biaya->tahun }}</td>
                        <td>{{ $biaya->bulan }}</td>
                        <td>{{ $biaya->level }}</td>
                        {{-- <td>{{ \Carbon\Carbon::parse($biaya->created_at)->isoFormat('HH:mm:ss, dddd, D MMMM Y') }}</td> --}}
                        <td>
                            <a href="{{ route('biaya.show', $biaya->id) }}" class="btn btn-info">Detail</a>
                            <a href="{{ route('biaya.edit', $biaya->id) }}" class="btn btn-warning">Edit</a>

                            <form action="{{ route('biaya.destroy', $biaya->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data barang keluar ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                {{ $biayas->links() }}
            </tbody>
        </table>
    @endsection
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#btnFilter').click(function(e) {
                    $.ajax({
                        url: "{{ route('biaya.filter') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "filter_tahun": $('#filterTahun').val(),
                            "filter_bulan": $('#filterBulan').val()
                        },
                        success: function(data) {
                            $('#dataTables tbody').empty();
                            $.each(data, function(index, value) {
                                $('#dataTables tbody').append('<tr>' +
                                    '<td>' + (index + 1) + '</td>' +
                                    '<td>' + value.nama_biaya + '</td>' +
                                    '<td>' + value.nominal + '</td>' +
                                    '<td>' + value.nama_nominal + '</td>' +
                                    '<td>' + value.tahun + '</td>' +
                                    '<td>' + value.bulan + '</td>' +
                                    '<td>' + value.level + '</td>' +
                                    '<td>' +
                                    '<a href="{{ route('biaya.show', "' + value.id + '") }}" class="btn  btn-info mx-1">Detail</a>' +
                                    '<a href="{{ route('biaya.edit', "' + value.id + '") }}" class="btn btn-warning mx-1">Edit</a>' +

                                    '<form action="{{ route('biaya.destroy', "' + value.id + '") }}" method="POST" style="display:inline;">' +
                                    '@csrf' +
                                    '@method('DELETE')' +
                                    '<button type="submit" class="btn btn-danger my-1" onclick="return confirm(\'Apakah Anda yakin ingin menghapus data biaya ini?\')">Hapus</button>' +
                                    '</form>' +
                                    '</td>' +
                                    '</tr>');
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
