<?php

namespace App\Exports;

use App\Models\Tagihan;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanSPPExport implements FromCollection,WithHeadings,WithMapping, ShouldAutoSize,WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $counter = 0;

    use Exportable;

    public function collection()
    {
        $tagihan = Tagihan::with('siswa')->latest()->get();

        return $tagihan;
    }


    public function headings(): array
    {
        return [
            '#',
            'No Invoice',
            'NIS',
            'Nama Siswa',
            'Kelas',
            'Bulan',
            'Tahun',
            'Total Bayar',
        ];
    }


    public function map($tagihan) : array
    {
        return [
            ++$this->counter,
            $tagihan->no_invoice,
            $tagihan->siswa->nis,
            $tagihan->siswa->nama,
            $tagihan->siswa->kelas,
            $tagihan->bulan,
            $tagihan->tahun,
            'Rp. ' . number_format($tagihan->total_bayar, 0, ',', '.'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            'A1:K1'    => ['font' => ['bold' => true]],
        ];
    }
}
