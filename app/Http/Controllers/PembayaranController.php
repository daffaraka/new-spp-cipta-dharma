<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Biaya;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        $data['judul'] = 'Pembayaran';
        $data['pembayarans'] = Tagihan::with(['siswa', 'biaya', 'penerbit', 'melunasi'])->whereNotNull('bukti_pelunasan')->latest()->get();
        $data['kelas'] = User::role('SiswaOrangTua')->select('id', 'kelas')->get()->unique();

        return view('admin.pembayaran.pembayaran-index', $data);
    }

    public function show(Tagihan $pembayaran)
    {
        $data['judul'] = 'Detil Data Pembayaran';
        $data['pembayaran'] = $pembayaran;
        return view('admin.pembayaran.pembayaran-edit', $data);
    }

    public function edit(Tagihan $pembayaran)
    {
        $data['judul'] = 'Edit Data Pembayaran';
        $data['siswas'] = User::select('id', 'nama')->get();
        $data['biayas'] = Biaya::select('id', 'nama_biaya', 'nominal')->get();
        $data['pembayaran'] = $pembayaran;
        return view('admin.pembayaran.pembayaran-edit', $data);
    }


    public function destroy(Tagihan $pembayaran)
    {
        $pembayaran->delete();
        return to_route('pembayaran.index')->with('success', 'pembayaran telah dihapus');
    }


    public function verifikasi($id)
    {
        $pembayaran = Tagihan::find($id);
        $pembayaran->status = 'Lunas';
        $pembayaran->melunasi_id = auth()->user()->id;
        $pembayaran->save();

        return to_route('pembayaran.index')->with('success', 'pembayaran telah diverifikasi');
    }

    public function kirimKuitansi($id)
    {
        $pembayaran = Tagihan::find($id);
        $pembayaran->melunasi_id = auth()->user()->id;
        $pembayaran->save();

        return to_route('pembayaran.index')->with('success', 'pembayaran telah diverifikasi');
    }


    public function filter(Request $request)
    {
        // dd($request->all());
        if (empty($request->filter_tahun) && empty($request->filter_bulan) && empty($request->filter_angkatan) && empty($request->filter_kelas)) {
            return Tagihan::with(['siswa', 'biaya', 'penerbit', 'melunasi'])
                ->whereNotNull('bukti_pelunasan')->get();
        } else {
            return response()->json(
                Tagihan::with(['biaya', 'siswa', 'penerbit', 'melunasi'])
                    ->whereNotNull('bukti_pelunasan')
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
