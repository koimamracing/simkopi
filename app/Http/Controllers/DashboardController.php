<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Produk;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTransaksi = Transaksi::count();
        $totalProduk = Produk::count();

        $uangDiperoleh = Transaksi::sum('total_bayar');

        return view('dashboard/dashboard', compact(
            'totalTransaksi',
            'totalProduk',
            'uangDiperoleh'
        ));
    }
}