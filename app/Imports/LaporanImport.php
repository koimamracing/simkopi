<?php

namespace App\Imports;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class LaporanImport implements ToModel
{
    public function model(array $row)
    {
        $produk = Produk::find($row[3]);

        if (!$produk) {
            return null;
        }

        $lastId = Transaksi::max('id_pesanan');
        $nextId = $lastId ? $lastId + 1 : 1;

        $subtotal = $produk->harga * $row[4];

        $transaksi = Transaksi::create([
            'id_pesanan' => $nextId,
            'nama_pembeli' => $row[0],
            'tanggal' => Date::excelToDateTimeObject($row[2])->format('Y-m-d'),
            'id_staff'     => $row[1],
            'harga'        => $subtotal,
        ]);

        DetailTransaksi::create([
            'id_pesanan' => $transaksi->id_pesanan,
            'id_produk'  => $row[3],
            'qty'        => $row[4],
            'subtotal'   => $subtotal,
        ]);

        $produk->stok -= $row[4];
        $produk->save();

        return $transaksi;
    }
}
