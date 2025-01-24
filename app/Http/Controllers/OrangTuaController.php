<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use Illuminate\Http\Request;

class OrangTuaController extends Controller
{

    public function pembayaran()
    {
        $data['judul'] = 'Pembayaran';
        $data['pembayarans'] = Tagihan::with(['siswa', 'biaya', 'penerbit', 'melunasi'])->where('user_id', auth()->user()->id)->latest()->get();


        // dd($data['pembayarans']);
        return view('ortu.pembayaran.pembayaran-ortu-index', $data);
    }


    public function filterStatus(Request $request)
    {

        if (empty($request->status)) {
            return response()->json(
                User::with('roles')->role(['Petugas', 'KepalaSekolah'])->latest()->get()
            );
        } else {
            return response()->json(
                User::with('roles')->role(['Petugas', 'KepalaSekolah'])->latest()
                    ->when($request->filled('filter_jk'), function ($query) use ($request) {
                        return $query->where('jenis_kelamin', $request->filter_jk);
                    })
                    ->get()
            );
        }
    }
}
