<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'no_invoice',
        'nama_invoice',
        'tanggal_terbit',
        'tanggal_lunas',
        'bukti_pelunasan',
        'status',
        'user_penerbit_id',
        'user_melunasi_id',
        'biaya_id',
        'user_id',
        'bulan',
        'tahun'
    ];

    public function siswa()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function biaya()
    {
        return $this->belongsTo(Biaya::class);
    }

    public function penerbit()
    {
        return $this->belongsTo(User::class, 'user_penerbit_id');
    }

    public function melunasi()
    {
        return $this->belongsTo(User::class, 'user_melunasi_id');
    }
}
