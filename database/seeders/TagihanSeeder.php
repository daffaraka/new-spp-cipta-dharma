<?php

namespace Database\Seeders;

use DB;
use App\Models\User;
use App\Models\Biaya;
use App\Models\Tagihan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TagihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            $noInvoice = 'INV' . sprintf('%03d', $i);
            $namaInvoice = 'Tagihan Bulanan ' . $i;
            $bulan = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d'))->format('F');
            $use = User::pluck('id')->toArray();
            $biaya = Biaya::pluck('id')->toArray();
            $buktiLunas = [null, 'Ada'];
            Tagihan::insert([
                'no_invoice' => $noInvoice,
                'nama_invoice' => $namaInvoice,
                'user_id' => $use[array_rand($use)],
                'biaya_id' => $biaya[array_rand($biaya)],
                'bulan' => $bulan,
                'tahun' => date('Y'),
                'tanggal_terbit' => \Carbon\Carbon::now(),
                'tanggal_lunas' => null,
                'user_penerbit_id' => 1,
                'bukti_pelunasan' => $buktiLunas[array_rand($buktiLunas)],
            ]);
        }
    }
}
