<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LaporanPetugasController extends Controller
{
    public function index()
    {
        $data['judul'] = 'Laporan Data Petugas';
        $data['petugas'] = User::role(['Petugas','KepalaSekolah'])->latest()->get();

        return view('admin.petugas.petugas-index',$data);
    }


    public function create()
    {
        return view('admin.petugas.petugas-create');
    }


    public function store(Request $request)
    {
        $this->validate($request,[
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


        return redirect()->route('siswa.index')->with('success','Data siswa baru telah ditambahkan');
    }


    public function show(User $petuga)
    {


        return view('admin.petugas.petugas-show',compact('petuga'));
    }


    public function edit(User $petugas)
    {
        return view('admin.petugas.petugas-edit',compact('siswa'));
    }
    // public function update(Request $request, User $petugas)
    // {
    //     $this->validate($request,[
    //         'nama' => 'required',
    //         'nis' => 'required',
    //         'nisn' => 'required',
    //         'email' => 'required',
    //         'password' => 'required',
    //         'tanggal_lahir' => 'required',
    //         'nama_wali' => 'required',
    //         'alamat' => 'required',
    //         'no_telp' => 'required',
    //         'angkatan' => 'required',
    //         'kelas' => 'required',
    //         'jenis_kelamin' => 'required',
    //     ]);


    //     $siswa->update(
    //         [
    //             'nama' => $request->nama,
    //             'nis' => $request->nis,
    //             'nisn' => $request->nisn,
    //             'email' => $request->email,
    //             'password' => bcrypt($request->password),
    //             'tanggal_lahir' => $request->tanggal_lahir,
    //             'nama_wali' => $request->nama_wali,
    //             'alamat' => $request->alamat,
    //             'no_telp' => $request->no_telp,
    //             'angkatan' => $request->angkatan,
    //             'kelas' => $request->kelas,
    //             'jenis_kelamin' => $request->jenis_kelamin,
    //         ]
    //     );


    //     return redirect()->route('siswa.index')->with('success','Data siswa telah diupdate');

    // }


    public function destroy(User $petugas)
    {
        $petugas->delete();
        return redirect()->route('siswa.index')->with('success','Data siswa telah dihapus');
    }
}
