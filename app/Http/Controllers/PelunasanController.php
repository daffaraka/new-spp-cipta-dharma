<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use Illuminate\Http\Request;

class PelunasanController extends Controller
{


    public function tagihan($id)
    {
        $data['judul'] = 'Selesaikan tagihan anda';
        $data['tagihan'] =Tagihan::with(['biaya','siswa'])->find($id);


        return view('admin.pelunasan.pelunasan-tagihan',$data);
    }


    public function lunasi(Request $request, $id)
    {

        $user = Tagihan::with('siswa')->find($id);

        $file = $request->file('bukti_pelunasan');
        $fileName = $file->getClientOriginalName();
        $fileSaved = $user->siswa->name.'-'.now()->format('Y-m-d H-i-s').'-'.$fileName;

        $file->move('bukti-pelunasan', $fileSaved);

        $tagihan = Tagihan::find($id);
        // dd($tagihan);
        $tagihan->bukti_pelunasan = $fileSaved;
        $tagihan->status = 'Lunas';
        $tagihan->save();
        return redirect()->route('dashboard')->with('success','Bukti pelunasan sudah dikirim');
    }
}
