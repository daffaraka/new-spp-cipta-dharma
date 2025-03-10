<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Biaya;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PembayaranController extends Controller
{
    public function index()
    {
        $data['judul'] = 'Pembayaran';
        $data['pembayarans'] = Tagihan::with(['siswa', 'biaya', 'penerbit', 'melunasi'])->whereNotNull('bukti_pelunasan')->latest()->get();

        // $data['kelas'] = User::role('SiswaOrangTua')->select('id', 'kelas')->get()->unique();


        // dd($data['pembayarans']);
        return view('admin.pembayaran.pembayaran-index', $data);
    }

    public function show(Tagihan $pembayaran)
    {
        $data['judul'] = 'Detil Data Pembayaran';
        $data['pembayaran'] = $pembayaran;
        return view('admin.pembayaran.pembayaran-show', $data);
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


    // tambahan a
    // lebih
    public function lebih($id, Request $request)
    {
        $data_tagihan = Tagihan::with(['siswa', 'biaya', 'penerbit', 'melunasi'])->where('id', $id)->first();

        $file = $request->file('file_bukti');
        $fileName = $file->getClientOriginalName();
        $fileSaved = $data_tagihan->siswa->nama.'-'.now()->format('Y-m-d H-i-s').'-'.$fileName;
        $file->move('bukti-pelunasan', $fileSaved);

        $chatId = $data_tagihan ? $data_tagihan->siswa->chat_id : null;
        if (!$chatId) {
            $chatId = env('TELEGRAM_CHAT_ID');
        }

        $botToken = env('TELEGRAM_BOT_TOKEN');
        $client = new Client();
        $response = $client->post("https://api.telegram.org/bot{$botToken}/sendPhoto", [
            'multipart' => [
                [
                    'name'     => 'chat_id',
                    'contents' => $chatId
                ],
                [
                    'name'     => 'photo',
                    'contents' => fopen(public_path("bukti-pelunasan/{$fileSaved}"), 'r'),
                    'filename' => $file->getClientOriginalName()
                ],
                [
                    'name'     => 'caption',
                    'contents' => "Halo {$data_tagihan->siswa->nama_wali}, Setelah dilakukan pengecekan data tagihan, nominal yang anda kirim lebih dari tagihan yang seharusnya Rp.". number_format($data_tagihan->biaya->nominal, 0, ',', '.').". Oleh karena itu dana kami kembalikan lagi sebesar Rp.". number_format($request->nominal, 0, ',', '.').", Terimakasih."
                ]
            ]
        ]);

        $pembayaran = Tagihan::find($id);
        $pembayaran->status = 'Lebih';
        $pembayaran->nominal = $request->nominal;
        $pembayaran->bukti_lebih = $fileSaved;
        $pembayaran->isSentKuitansi = 1;
        $pembayaran->save();

        return to_route('pembayaran.index')->with('success', 'Pesan tagihan nominal lebih sudah terkirim');
    }
    // kurang
    public function kurang($id, Request $request)
    {
        $data_tagihan = Tagihan::with(['siswa', 'biaya', 'penerbit', 'melunasi'])->where('id', $id)->first();

        $chatId = $data_tagihan ? $data_tagihan->siswa->chat_id : null;
        if (!$chatId) {
            $chatId = env('TELEGRAM_CHAT_ID');
        }

        $botToken = env('TELEGRAM_BOT_TOKEN');
        $client = new Client();
        $client->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
            'form_params' => [
                'chat_id' => $chatId,
                'text' => "Halo {$data_tagihan->siswa->nama_wali}, Setelah dilakukan pengecekan data tagihan, nominal yang anda kirim kurang dari tagihan yang seharusnya Rp.". number_format($data_tagihan->biaya->nominal, 0, ',', '.').". Oleh karena itu dimohon untuk melakukan pembayaran kembali sebesar Rp.". number_format($request->nominal, 0, ',', '.').", Terimakasih.",
            ],
        ]);

        $pembayaran = Tagihan::find($id);
        $pembayaran->status = 'Kurang';
        $pembayaran->nominal = $request->nominal;
        $pembayaran->save();

        return to_route('pembayaran.index')->with('success', 'Pesan tagihan nominal kurang sudah terkirim');
    }
    // tambahan b


    public function verifikasi($id)
    {
        $pembayaran = Tagihan::find($id);
        $pembayaran->status = 'Lunas';
        $pembayaran->user_melunasi_id = auth()->user()->id;
        $pembayaran->isSentKuitansi = '1';
        $pembayaran->tanggal_lunas = Carbon::now();
        $pembayaran->save();

        // dd($pembayaran);

        return to_route('pembayaran.index')->with('success', 'pembayaran telah diverifikasi');
    }

    public function kirimKuitansi($id)
    {
        $pembayaran = Tagihan::find($id);
        $pembayaran->melunasi_id = auth()->user()->id;
        $pembayaran->isSentKuitansi = 1;
        $pembayaran->save();

        return to_route('pembayaran.index')->with('success', 'Kuitansi Dikirim telah diverifikasi');
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
                        $query->whereTahun($request->filter_tahun);
                    })
                    ->when(!empty($request->filter_bulan), function ($query) use ($request) {
                        $query->whereBulan($request->filter_bulan);
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
                    ->latest()
                    ->get()
            );
        }
    }
}
