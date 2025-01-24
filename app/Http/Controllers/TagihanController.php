<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Biaya;
use App\Models\Siswa;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class TagihanController extends Controller
{

    public function index()
    {
        $data['judul'] = 'Tagihan';
        $data['tagihans'] = Tagihan::with(['siswa', 'biaya', 'penerbit', 'melunasi'])->latest()->paginate(10);
        $data['kelas'] = User::role('SiswaOrangTua')->select('id', 'kelas')->get()->unique();
        return view('admin.tagihan.tagihan-index', $data);
    }

    public function create()
    {
        $data['judul'] = 'Tambah Tagihan';
        $data['siswas'] = User::role('SiswaOrangTua')->get();
        $data['biayas'] = Biaya::select('id', 'nama_biaya', 'nominal')->get();

        return view('admin.tagihan.tagihan-create', $data);
    }

    public function store(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'user_id' => 'required',
            'biaya_id' => 'required',
        ], [
            'user_id.required' => 'Siswa harus dipilih',
            'biaya_id.required' => 'Biaya harus dipilih',
        ]);


        $tagihan = new Tagihan();
        $tagihan->no_invoice = $request->no_invoice;
        $tagihan->keterangan = $request->keterangan;
        $tagihan->user_id = $request->user_id;
        $tagihan->biaya_id = $request->biaya_id;
        $tagihan->bulan = $request->bulan;
        $tagihan->tahun = $request->tahun ?? date('Y');
        $tagihan->tanggal_terbit = $request->tanggal_terbit ?? Carbon::now();
        $tagihan->tanggal_lunas = $request->tanggal_lunas;
        $tagihan->biaya_id = $request->biaya_id;
        $tagihan->user_penerbit_id = auth()->user()->id;

        $tagihan->save();

        return to_route('tagihan.index')->with('success', 'Tagihan baru ditambahkan');
    }

    public function show(Tagihan $tagihan)
    {
        $data['judul'] = 'Edit Data Tagihan';
        $data['tagihan'] = $tagihan->with('siswa', 'biaya', 'penerbit')->first();

        return view('admin.tagihan.tagihan-show', $data);
    }

    public function edit(Tagihan $tagihan)
    {
        $data['judul'] = 'Edit Data Tagihan';
        $data['siswas'] = User::select('id', 'nama')->get();
        $data['biayas'] = Biaya::select('id', 'nama_biaya', 'nominal')->get();
        $data['tagihan'] = $tagihan;
        return view('admin.tagihan.tagihan-edit', $data);
    }

    public function update(Request $request, Tagihan $tagihan)
    {
        $request->validate([
            'user_id' => 'required',
            'biaya_id' => 'required',
        ], [
            'user_id.required' => 'Siswa harus dipilih',
            'biaya_id.required' => 'Biaya harus dipilih',
        ]);


        $tagihan->nama_tagihan = $request->nama_tagihan;
        $tagihan->user_id = $request->user_id;
        $tagihan->biaya_id = $request->biaya_id;
        $tagihan->tanggal_terbit = $request->tanggal_terbit;
        $tagihan->tanggal_lunas = $request->tanggal_lunas;
        $tagihan->biaya_id = $request->biaya_id;
        $tagihan->status = $request->status;
        $tagihan->user_penerbit_id = auth()->user()->id;

        return to_route('tagihan.index')->with('success', 'Tagihan telah diperbarui');
    }

    public function destroy(Tagihan $tagihan)
    {
        $tagihan->delete();
        return to_route('tagihan.index')->with('success', 'Tagihan telah dihapus');
    }


    public function filter(Request $request)
    {
        // dd($request->all());
        if (empty($request->filter_tahun) && empty($request->filter_bulan) && empty($request->filter_angkatan) && empty($request->filter_kelas)) {
            return Tagihan::with(['siswa', 'biaya', 'penerbit', 'melunasi'])->get();
        } else {
            return response()->json(
                Tagihan::with(['biaya', 'siswa', 'penerbit', 'melunasi'])
                    ->when(!empty($request->filter_tahun), function ($query) use ($request) {
                        $query->whereTahun($request->filter_tahun);
                    })
                    ->when(!empty($request->filter_bulan), function ($query) use ($request) {
                        $query->whereBulan($request->filter_bulan);
                    })->when($request->filter_angkatan != null, function ($query) use ($request) {
                        return $query->whereHas('siswa', function ($query) use ($request) {
                            $query->where('angkatan', $request->filter_angkatan);
                        });
                    })
                    ->when($request->filter_kelas != null, function ($query) use ($request) {
                        return $query->whereHas('siswa', function ($query) use ($request) {
                            $query->where('kelas', $request->filter_kelas);
                        });
                    })
                    ->get()
            );
        }
    }
}
