<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Biaya;
use App\Models\Tagihan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = User::pluck('id')->toArray();
        $biaya = Biaya::pluck('id')->toArray();
        $tahun = ['2019', '2020', '2021', '2022', '2023', '2024'];
        $userMenerbitkan = User::role(['Petugas', 'KepalaSekolah'])->pluck('id')->toArray();
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        for ($i = 1; $i <= 50; $i++) {
            $tagihan = new Tagihan();
            $tagihan->no_invoice = "INV" . sprintf('%03d', $i);
            $tagihan->keterangan = "Tagihan Bulanan " . $i;
            $tagihan->user_id = $user[array_rand($user)];
            $tagihan->biaya_id = $biaya[array_rand($biaya)];
            $tagihan->bulan =  $bulan[array_rand($bulan)];
            $tagihan->tahun = $tahun[array_rand($tahun)];
            $tagihan->tanggal_terbit = Carbon::now();
            $tagihan->tanggal_lunas = null;
            $tagihan->user_penerbit_id = $userMenerbitkan[array_rand($userMenerbitkan)];
            $tagihan->bukti_pelunasan = [null, 'Ada'][rand(0, 1)];
            $tagihan->save();
        }
    }
}
