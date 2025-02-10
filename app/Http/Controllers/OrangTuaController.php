<?php

namespace App\Http\Controllers;

use App\Models\User;
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



    public function riwayatPembayaran()
    {
        $data['judul'] = 'Riwayat Pembayaran';
        $data['riwayats'] = Tagihan::with(['siswa', 'biaya', 'penerbit', 'melunasi'])->where('user_id', auth()->user()->id)->latest()->get();


        // dd($data['pembayarans']);
        return view('ortu.pembayaran.riwayat-pembayaran', $data);
    }


    public function filterStatusPembayaran(Request $request)
    {

        if (empty($request->filter_status)) {
            return response()->json(
                Tagihan::with(['siswa', 'biaya', 'penerbit', 'melunasi'])->where('user_id', auth()->user()->id)->latest()->get()
            );
        } else {
            return response()->json(
                Tagihan::with(['siswa', 'biaya', 'penerbit', 'melunasi'])->where('user_id', auth()->user()->id)
                    ->when($request->filled('filter_status'), function ($query) use ($request) {
                        return $query->where('status', $request->filter_status);
                    })
                    ->get()
            );
        }
    }


    public function filterRiwayatPembayaran(Request $request)
    {

        if (empty($request->filter_tahun) && empty($request->filter_bulan)) {
            return response()->json(
                Tagihan::with(['siswa', 'biaya', 'penerbit', 'melunasi'])->where('user_id', auth()->user()->id)->latest()->get()
            );
        } else {
            return response()->json(
                Tagihan::with(['biaya', 'siswa', 'penerbit', 'melunasi'])
                    ->when($request->filled('filter_tahun'), function ($query) use ($request) {
                        $query->whereYear('created_at', $request->filter_tahun);
                    })
                    ->when($request->filled('filter_bulan'), function ($query) use ($request) {
                        $query->whereMonth('created_at', $request->filter_bulan);
                    })
                    ->where('user_id', auth()->user()->id)->latest()
                    ->get()
            );
        }
    }
}
