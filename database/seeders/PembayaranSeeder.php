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

        for ($i = 1; $i <= 50; $i++) {
            $tagihan = new Tagihan();
            $tagihan->no_invoice = "INV" . sprintf('%03d', $i);
            $tagihan->nama_invoice = "Tagihan Bulanan " . $i;
            $tagihan->user_id = $user[array_rand($user)];
            $tagihan->biaya_id = $biaya[array_rand($biaya)];
            $tagihan->bulan = Carbon::createFromFormat('Y-m-d', date('Y-m-d'))->format('F');
            $tagihan->tahun = date('Y');
            $tagihan->tanggal_terbit = Carbon::now();
            $tagihan->tanggal_lunas = null;
            $tagihan->user_penerbit_id = 1;
            $tagihan->bukti_pelunasan = [null, 'Ada'][rand(0, 1)];
            $tagihan->save();
        }
    }
}
