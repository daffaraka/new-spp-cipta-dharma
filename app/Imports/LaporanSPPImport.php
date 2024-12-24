<?php

namespace App\Imports;

use App\Models\Tagihan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class LaporanSPPImport implements ToModel
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return new Tagihan([
            'no_invoice' => $row[1],
            'nis'      => $row[2],
            'nama'     => $row[3],
            'kelas'    => $row[4],
            'bulan'    => $row[5],
            'tahun'    => date('Y', strtotime($row[6])),
            'total_bayar'  => $row[7],
        ]);
    }
}
