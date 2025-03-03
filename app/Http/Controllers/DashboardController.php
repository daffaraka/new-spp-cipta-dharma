<?php

namespace App\Http\Controllers;

use DB;
use App\Models\User;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $auth_id = Auth::user()->id;


        if (Auth::user()->hasRole(['KepalaSekolah', 'Petugas'])) {

            $data['total_siswa'] = User::role('SiswaOrangTua')->count();
            $data['total_petugas'] = User::role(['KepalaSekolah', 'Petugas'])->count();
            $data['total_laki'] = User::where('jenis_kelamin', 'Laki-laki')->count();
            $data['total_perempuan'] = User::where('jenis_kelamin', 'Perempuan')->count();


            $data['select_tahun'] = Tagihan::distinct()->orderBy('tahun', 'desc')->pluck('tahun')->toArray();
            $data['select_bulan'] = Tagihan::distinct()->orderBy('bulan', 'desc')->pluck('bulan')->toArray();
            // dd($data['select_tahun']);


            $data['data_perJenisKelamin'] = User::select('jenis_kelamin', DB::raw('count(*) as total'))->groupBy('jenis_kelamin')->get()->map(function ($item) {
                return [
                    'jenis_kelamin' => $item->jenis_kelamin,
                    'total' => $item->total
                ];
            });

            $data['data_pembayaranPerTahun'] = Tagihan::with('biaya')->whereStatus('Lunas')->get()->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->tanggal_terbit)->format('Y');
            })->map(function ($item) {
                return $item->sum(function ($tagihan) {
                    return $tagihan->biaya->nominal;
                });
            });


            // dd($data['data_pembayaranPerTahun']);


            $data['data_pembayaranPerBulan'] = Tagihan::with('biaya')->whereStatus('Lunas')->orderBy('tanggal_terbit', 'asc')->get()->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->tanggal_terbit)->format('M');
            })->map(function ($item) {
                return $item->sum(function ($tagihan) {
                    return $tagihan->biaya->nominal;
                });
            });

            // $data['data_pembayaranPerBulan'] = Tagihan::with('biaya')->whereStatus('Lunas')->get()->groupBy(function ($item) {
            //     return \Carbon\Carbon::parse($item->tanggal_terbit)->format('M');
            // })->map(function ($item) {
            //     return $item->sum(function ($tagihan) {
            //         return $tagihan->biaya->nominal;
            //     });
            // });



            // dd($data['data_pembayaranPerBulan']);


            // dd( $data['data_pembayaranPerbulan']);

            // dd($data['data_SppPerBulan']);

            // dd($data['data_perJenisKelamin']);

            return view('admin.admin-dashboard', $data);
        } else {

            $data['tagihanBelumLunas'] = Tagihan::whereStatus('Belum Lunas')->where('user_id', $auth_id)->get();


            // dd($data);
            $data['tagihan_Lunas'] = Tagihan::whereStatus('Lunas')->where('user_id', $auth_id)->get();

            // User::with('tagihans')->whereHas('tagihans', function ($tagihan) {
            //     $tagihan->whereStatus('Lunas');
            // })
            //     ->find($auth_id);

            return view('admin.ortu-dashboard', $data);
        }
    }

    public function filterData(Request $request)
    {

        if($request->has('filter_tahun')) {
            if (empty($request->filter_tahun)) {
                $data['data_pembayaranPerTahun'] = Tagihan::with('biaya')->whereStatus('Lunas')->get()->groupBy(function ($item) {
                    return \Carbon\Carbon::parse($item->tanggal_terbit)->format('Y');
                })->map(function ($item) {
                    return $item->sum(function ($tagihan) {
                        return $tagihan->biaya->nominal;
                    });
                });
            } else {
                $data['data_pembayaranPerTahun'] = Tagihan::with('biaya')->whereStatus('Lunas')->where('tanggal_terbit', $request->filter_tahun)->get()->groupBy(function ($item) {
                    return \Carbon\Carbon::parse($item->tanggal_terbit)->format('M');
                })->map(function ($item) {
                    return $item->sum(function ($tagihan) {
                        return $tagihan->biaya->nominal;
                    });
                });
                return response()->json($data['data_pembayaranPerTahun']);
            }
        }


        if($request->has('filter_bulan')) {
            if (empty($request->filter_bulan)) {
                $data['data_pembayaranPerBulan'] = Tagihan::with('biaya')->whereStatus('Lunas')->get()->groupBy(function ($item) {
                    return \Carbon\Carbon::parse($item->tanggal_terbit)->format('m');
                })->map(function ($item) {
                    return $item->sum(function ($tagihan) {
                        return $tagihan->biaya->nominal;
                    });
                });
            } else {
                $data['data_pembayaranPerBulan'] = Tagihan::with('biaya')->whereStatus('Lunas')->where('tanggal_terbit', $request->filter_bulan)->get()->groupBy(function ($item) {
                    return \Carbon\Carbon::parse($item->tanggal_terbit)->format('d');
                })->map(function ($item) {
                    return $item->sum(function ($tagihan) {
                        return $tagihan->biaya->nominal;
                    });
                });
                return response()->json($data['data_pembayaranPerBulan']);
            }
        }

    }
}
