<?php

namespace App\Http\Controllers;

use App\Exports\LaporanSPPExport;
use App\Imports\LaporanSPPImport;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanSPPController extends Controller
{
    public function index()
    {
        $data['judul'] = 'Laporan Data SPP';
        $data['laporan_spp'] = Tagihan::with('siswa')->latest()->get();

        // dd($data);
        return view('admin.laporan-spp.laporan-spp-index', $data);
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




        return redirect()->route('siswa.index')->with('success', 'Data siswa baru telah ditambahkan');
    }


    public function show(Tagihan $laporan_spp)
    {
        return view('admin.petugas.petugas-show', compact('laporan_spp'));
    }


    public function edit(Tagihan $laporan_spp)
    {
        return view('admin.petugas.petugas-edit', compact('siswa'));
    }


    public function destroy(Tagihan $laporan_spp)
    {
        $laporan_spp->delete();
        return redirect()->route('laporanPetugas.index')->with('success', 'Data spp telah dihapus');
    }

    public function filter(Request $request)
    {
        // dd($request->all());
        if (empty($request->filter_tahun) && empty($request->filter_bulan) && empty($request->filter_angkatan) && empty($request->filter_kelas)) {
            return Tagihan::with('siswa')->latest()->get();
        } else {
            return response()->json(
                Tagihan::with('siswa')
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


    public function export()
    {
        $tgl = date('d-m-Y_H-i-s');
        return Excel::download(new LaporanSPPExport, 'laporan_spp_' . $tgl . '.xlsx');
    }


    public function import(Request $request)
    {
        // dd($request->all());
        Excel::import(new LaporanSPPImport, $request->file('file'));
        return redirect()->back()->with('success', 'Data laporan SPP baru telah ditambahkan');
    }


    public function print()
    {
        $siswas = Tagihan::with('siswa')->latest()->get();
        $pdf = PDF::loadview('admin.pdf.siswa-pdf', compact('siswas'))
            ->setPaper('a4', 'landscape');
        $tgl = date('d-m-Y_H-i`-s');
        return $pdf->stream('siswa'.$tgl.'.pdf');
    }
}
