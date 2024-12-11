<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LaporanSiswaController extends Controller
{
    public function index()
    {
        $data['judul'] = 'Laporan Data Siswa';
        $data['laporan_siswa'] = User::role(['SiswaOrangTua'])->withCount('menerbitkan')->latest()->get();

        // dd($data);
        return view('admin.laporan-siswa.laporan-siswa-index', $data);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required',
            'nis' => 'required|unique:users',
            'nisn' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required',
            'tanggal_lahir' => 'required',
            'nama_wali' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
            'angkatan' => 'required',
            'kelas' => 'required',
            'jenis_kelamin' => 'required',
        ]);


        User::create(
            [
                'nama' => $request->nama,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'tanggal_lahir' => $request->tanggal_lahir,
                'nama_wali' => $request->nama_wali,
                'alamat' => $request->alamat,
                'no_telp' => $request->no_telp,
                'angkatan' => $request->angkatan,
                'kelas' => $request->kelas,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]
        );


        return redirect()->route('siswa.index')->with('success', 'Data siswa baru telah ditambahkan');
    }


    public function show(User $petuga)
    {


        return view('admin.petugas.petugas-show', compact('petuga'));
    }


    public function edit(User $petugas)
    {
        return view('admin.petugas.petugas-edit', compact('siswa'));
    }


    public function destroy(User $petugas)
    {
        $petugas->delete();
        return redirect()->route('siswa.index')->with('success', 'Data siswa telah dihapus');
    }

    public function filter(Request $request)
    {
        // dd($request->all());
        if (empty($request->filter_tahun) && empty($request->filter_bulan) && empty($request->filter_angkatan) && empty($request->filter_kelas)) {
            return User::role(['SiswaOrangTua'])->latest()->get();
        } else {
            return response()->json(
                User::role(['SiswaOrangTua'])
                    ->when(!empty($request->filter_tahun), function ($query) use ($request) {
                        $query->whereYear('created_at', $request->filter_tahun);
                    })
                    ->when(!empty($request->filter_bulan), function ($query) use ($request) {
                        $query->whereMonth('created_at', $request->filter_bulan);
                    })->when($request->filter_angkatan != null, function ($query) use ($request) {
                        return $query->whereHas('siswa', function ($query) use ($request) {
                            $query->where('angkatan', $request->filter_angkatan);
                        });
                    })
                    ->get()
            );
        }
    }
}
