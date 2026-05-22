<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;

class LaporanExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
{
    return Transaksi::select(
        'id_pesanan',
        'nama_pembeli',
        'harga',
        'tanggal',
        'id_staff'
    )->get();
}
}
