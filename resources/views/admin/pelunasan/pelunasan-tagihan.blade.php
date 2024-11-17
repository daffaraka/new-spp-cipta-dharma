@extends('admin.admin-layout')
@section('content')
    <form action="{{ route('pelunasan.lunasi', $tagihan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-xxl-4 col-xl-4 col-lg-4 col-12">
                <div class="mb-3">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTvX7ghSY75PvK5S-RvhkFxNz88MWEALSBDvA&s"
                        id="preview" width="100%" alt="">

                </div>
                <input type="file" class="form-control" name="bukti_pelunasan" accept="image/*" id="selectImage" required>
            </div>

            <div class="col-xxl-8 col-xl-8 col-lg-8 col-12">
                <div class="mb-3">
                    <label for="">Identitas Siswa</label>
                    <input type="text" value="{{ $tagihan->siswa->name }}" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label for="">Nama Tagihan</label>
                    <input type="text" name="nama_tagihan" class="form-control" value="{{ $tagihan->nama_tagihan }}"
                        readonly>
                </div>

                <div class="mb-3">
                    <label for="">Biaya</label>
                    <input type="text" name="nama_tagihan" class="form-control" value="{{ $tagihan->biaya->nominal }}"
                        readonly>

                </div>





                <div class="mb-3">
                    <label for="">Tanggal Terbit</label>
                    <input type="date" name="tanggal_terbit" class="form-control"
                        value="{{ $tagihan->tanggal_terbit ?? date('Y-m-d') }}" readonly>
                    <label> Jika dikosongi otomatis di isi hari ini </label>
                </div>

                <div class="mb-3">
                    <label for="">Tanggal Lunas</label>
                    <input type="date" name="tanggal_lunas" class="form-control" value="{{ $tagihan->tanggal_lunas }}"
                        readonly>
                    <label> Boleh dikosongi </label>
                </div>


                <div class="mb-3">
                    <label for="">Status Pelunasan</label>
                    <select type="date" name="status" class="form-control" value="{{ $tagihan->status }}"
                        @readonly(true)>
                        <option value="Lunas" {{ $tagihan->status == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="Belum Lunas" {{ $tagihan->status == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas
                        </option>

                    </select>
                </div>

                <button type="submit" class="btn btn-primary my-3">Submit</button>
            </div>
        </div>

    </form>


    <script type="text/javascript">
        selectImage.onchange = evt => {
            preview = document.getElementById('preview');
            preview.style.display = 'block';
            const [file] = selectImage.files
            if (file) {
                preview.src = URL.createObjectURL(file)
            }
        }
    </script>

@endsection
