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
        $auth_id = Auth::user()->id;


        if (Auth::user()->hasRole(['KepalaSekolah', 'Petugas'])) {
            $data['total_siswa'] = User::role('SiswaOrangTua')->count();
            $data['total_petugas'] = User::role(['KepalaSekolah', 'Petugas'])->count();
            $data['total_laki'] = User::where('jenis_kelamin', 'Laki-laki')->count();
            $data['total_perempuan'] = User::where('jenis_kelamin', 'Perempuan')->count();




            return view('admin.admin-dashboard', $data);
        } else {

            $data['tagihanBelumLunas'] = User::with('tagihans')->whereHas('tagihans', function ($tagihan) {
                $tagihan->whereStatus('Belum Lunas');
            })
                ->find($auth_id);

            $data['tagihan_Lunas'] = Tagihan::whereStatus('Lunas')->where('user_id', $auth_id)->take(5)->get();

            // User::with('tagihans')->whereHas('tagihans', function ($tagihan) {
            //     $tagihan->whereStatus('Lunas');
            // })
            //     ->find($auth_id);

            return view('admin.ortu-dashboard', $data);
        }
    }
}
