<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $data['total_siswa'] = User::role('SiswaOrangTua')->count();
        $data['total_petugas'] = User::role(['KepalaSekolah', 'Petugas'])->count();
        $data['total_laki'] = User::where('jenis_kelamin', 'Laki-laki')->count();
        $data['total_perempuan'] = User::where('jenis_kelamin', 'Perempuan')->count();

        $auth_id = Auth::user()->id;
        $data['tagihanBelumLunas'] = User::with('tagihans')->whereHas('tagihans', function ($tagihan) {
            $tagihan->whereStatus('Belum Lunas');
        })
            ->find($auth_id);
        // $data = Tagihan::with('siswa.user')->whereStatus('Belum Lunas')->count();
        // ->whereSiswaId($user_id)->whereStatus('Belum lunas')->count();


        // dd($data, $user_id);
        return view('admin.admin-dashboard', $data);
    }
}
