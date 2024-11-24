<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class SiswaImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new User([
            'name'     => $row[1],
            'nis'      => $row[2],
            'nisn'     => $row[3],
            'angkatan' => $row[4],
            'kelas'    => $row[5],
            'jenis_kelamin' => $row[6],
            'nama_wali' => $row[7],
            'alamat'   => $row[8],
            'email'    => $row[9],
            'no_telp'  => $row[10],
        ]);
    }
}
