<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Exports\SiswaExport;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpParser\Node\Stmt\Return_;
use Maatwebsite\Excel\Facades\Excel;

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

        // dd($request->all());
        $this->validate($request, [
            'nama' => 'required',
            'nis' => 'required|unique:users',
            'nisn' => 'required|unique:users',
            'email' => 'required|unique:users',
            'nama_wali' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
            'angkatan' => 'required',
            'kelas' => 'required',
            'jenis_kelamin' => 'required',
            'id_telegram' => 'required',
        ]);


        $user =  User::create(
            [
                'nama' => $request->nama,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'nama_wali' => $request->nama_wali,
                'alamat' => $request->alamat,
                'no_telp' => $request->no_telp,
                'email' => $request->email,
                'angkatan' => $request->angkatan,
                'kelas' => $request->kelas,
                'jenis_kelamin' => $request->jenis_kelamin,
                'id_telegram' => $request->id_telegram,
            ]
        );

        $user->assignRole('SiswaOrangTua');


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


    public function export()
    {
        $tgl = date('d-m-Y_H-i-s');
        return Excel::download(new SiswaExport, 'siswa_'.$tgl.'.xlsx');
    }


    public function import()
    {
        Excel::import(new SiswaImport, request()->file('file'));

    return redirect()->back()->with('success', 'Data siswa baru telah ditambahkan');
    }


    public function print()
    {
        $siswas = User::role('SiswaOrangTua')->latest()->get();
        $pdf = PDF::loadview('admin.pdf.siswa-pdf', compact('siswas'))
            ->setPaper('a4', 'landscape');
        $tgl = date('d-m-Y_H-i`-s');
        return $pdf->stream('siswa'.$tgl.'.pdf');
    }
}
