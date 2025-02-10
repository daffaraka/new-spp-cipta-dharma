<?php

namespace App\Http\Controllers;

use DB;
use App\Models\User;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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


            $data['data_perJenisKelamin'] = User::select('jenis_kelamin', DB::raw('count(*) as total'))->groupBy('jenis_kelamin')->get()->map(function ($item) {
                return [
                    'jenis_kelamin' => $item->jenis_kelamin,
                    'total' => $item->total
                ];
            });


            // $data['data_SppPerBulan'] = Tagihan::with('biaya')->whereStatus('Lunas')->get()->map(function ($item) {
            //     return [
            //         'bulan' => $item->bulan,
            //         'nominal' => $item->biaya->nominal,
            //     ];
            // });


            // dd($data['data_SppPerBulan']);

            // dd($data['data_perJenisKelamin']);

            return view('admin.admin-dashboard', $data);
        } else {

            $data['tagihanBelumLunas'] = Tagihan::whereStatus('Belum Lunas')->where('user_id', $auth_id)->get();


            $data['tagihan_Lunas'] = Tagihan::whereStatus('Lunas')->where('user_id', $auth_id)->take(5)->get();

            // User::with('tagihans')->whereHas('tagihans', function ($tagihan) {
            //     $tagihan->whereStatus('Lunas');
            // })
            //     ->find($auth_id);

            return view('admin.ortu-dashboard', $data);
        }
    }
}
