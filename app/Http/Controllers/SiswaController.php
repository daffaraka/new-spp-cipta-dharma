<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SiswaController extends Controller
{

    public function index()
    {
        $data['judul'] = 'Data Siswa';
        $data['siswas'] = User::role('SiswaOrangTua')->latest()->get();

        return view('admin.siswa.siswa-index', $data);
    }


    public function create()
    {
        return view('admin.siswa.siswa-create');
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
                'email' => $request->email,
                'angkatan' => $request->angkatan,
                'kelas' => $request->kelas,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]
        );


        return redirect()->route('siswa.index')->with('success', 'Data siswa baru telah ditambahkan');
    }



    public function show($id)
    {
        $user = User::find($id);

        return response()->json($user);
    }

    public function edit(User $siswa)
    {
        return view('admin.siswa.siswa-edit', compact('siswa'));
    }
    public function update(Request $request, User $siswa)
    {
        $this->validate($request, [
            'nama' => 'required',
            'nis' => 'required',
            'nisn' => 'required',
            'email' => 'required',
            'password' => 'required',
            'tanggal_lahir' => 'required',
            'nama_wali' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
            'angkatan' => 'required',
            'kelas' => 'required',
            'jenis_kelamin' => 'required',
        ]);


        $siswa->update(
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
                'email' => $request->email,
                'angkatan' => $request->angkatan,
                'kelas' => $request->kelas,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]
        );


        return redirect()->route('siswa.index')->with('success', 'Data siswa telah diupdate');
    }


    public function destroy(User $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Data siswa telah dihapus');
    }


    public function filter(Request $request)
    {
        // dd($request->all());
        return response()->json(
            User::role('SiswaOrangTua')->when($request->filter_angkatan != null, function ($query) use ($request) {
                return $query->where('angkatan', $request->filter_angkatan);
            })

                ->when($request->filter_kelas != null, function ($query) use ($request) {
                    return $query->where('kelas', $request->filter_kelas);
                })
                ->get()
        );
    }
}
