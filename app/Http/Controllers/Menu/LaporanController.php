<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\DetailTransaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Staff;

class LaporanController extends Controller
{
    public function index()
    {
        $last = Transaksi::max('id_pesanan');
        $nextId = $last !== null ? $last + 1 : 1;

        $produk = Produk::all();
        $staff = Staff::where('email', auth()->user()->email)->first();

        return view('menu/buatpesanan', compact(
            'nextId',
            'produk',
            'staff'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pesanan'   => 'required',
            'nama_pembeli' => 'required',
            'harga'        => 'required',
            'tanggal'      => 'required',
            'id_staff'     => 'required',
            'diskon'       => 'nullable|numeric|min:0|max:100',
            'id_produk'    => 'required|array',
            'jumlah'       => 'required|array',
        ]);

        $diskonPersen = $request->diskon ?? 0;

        $details = [];
        $totalItem = 0;
        $totalHarga = 0;

        foreach ($request->id_produk as $index => $produkId) {

            $produk = Produk::find($produkId);

            if (!$produk || $produk->stok < (int)$request->jumlah[$index]) {
                continue;
            }

            $jumlah = (int) $request->jumlah[$index];
            $subtotal = $produk->harga * $jumlah;

            $produk->stok -= $jumlah;
            $produk->save();

            DetailTransaksi::create([
                'id_pesanan' => $request->id_pesanan,
                'id_produk'  => $produkId,
                'qty'        => $jumlah,
                'subtotal'   => $subtotal,
            ]);

            $details[] = [
                'nama_produk' => $produk->nama_produk,
                'harga'       => $produk->harga,
                'jumlah'      => $jumlah,
                'subtotal'    => $subtotal,
            ];

            $totalItem += $jumlah;
            $totalHarga += $subtotal;
        }

        $diskonRp = ($diskonPersen / 100) * $totalHarga;
        $totalBayar = $totalHarga - $diskonRp;

        $transaksi = Transaksi::create([
            'id_pesanan'   => $request->id_pesanan,
            'nama_pembeli' => $request->nama_pembeli,
            'harga'        => $totalHarga,
            'diskon'       => $diskonPersen,
            'tanggal'      => $request->tanggal,
            'id_staff'     => $request->id_staff,
            'total_bayar'  => $totalBayar,
        ]);

        $pdf = Pdf::loadView('pdf.struk', [
            'transaksi'   => $transaksi,
            'details'     => $details,
            'total_item'  => $totalItem,
            'total_harga' => $totalHarga,
            'diskon'      => $diskonPersen,
            'diskon_rp'   => $diskonRp,
            'total_bayar' => $totalBayar,
        ]);

        return $pdf->download('strukpesanan-' . $transaksi->id_pesanan . '.pdf');
    }

    public function generatePdf($id)
    {
        $transaksi = Transaksi::with('staff')
            ->where('id_pesanan', $id)
            ->firstOrFail();

        $dbDetails = DetailTransaksi::where('id_pesanan', $id)->get();

        $details = [];
        $totalItem = 0;
        $totalHarga = 0;

        foreach ($dbDetails as $detail) {

            $produk = Produk::find($detail->id_produk);
            $harga = $produk ? $produk->harga : 0;

            $details[] = [
                'nama_produk' => $produk ? $produk->nama_produk : 'Produk Dihapus',
                'harga'       => $harga,
                'jumlah'      => $detail->qty,
                'subtotal'    => $detail->subtotal,
            ];

            $totalItem += $detail->qty;
            $totalHarga += $detail->subtotal;
        }

        $diskonPersen = $transaksi->diskon ?? 0;
        $diskonRp = ($diskonPersen / 100) * $totalHarga;
        $totalBayar = $totalHarga - $diskonRp;

        $pdf = Pdf::loadView('pdf.struk', [
            'transaksi'   => $transaksi,
            'details'     => $details,
            'total_item'  => $totalItem,
            'total_harga' => $totalHarga,
            'diskon'      => $diskonPersen,
            'diskon_rp'   => $diskonRp,
            'total_bayar' => $totalBayar,
        ]);

        return $pdf->download('struk-' . $transaksi->id_pesanan . '.pdf');
    }

    public function laporan(Request $request)
    {
        $query = Transaksi::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pembeli', 'like', '%' . $search . '%')
                    ->orWhere('id_pesanan', 'like', '%' . $search . '%');
            });
        }

        $transaksi = $query->with('staff')
            ->latest('id_pesanan')
            ->paginate(10);

        return view('menu/laporan', compact('transaksi'));
    }

    public function detailLaporan($id)
    {
        $transaksi = Transaksi::with(['details.produk', 'staff'])
            ->where('id_pesanan', $id)
            ->firstOrFail();

        $totalHarga = $transaksi->details->sum('subtotal');

        $diskonPersen = $transaksi->diskon ?? 0;
        $diskonRp = ($diskonPersen / 100) * $totalHarga;
        $totalBayar = $totalHarga - $diskonRp;

        return view('menu/detaillaporan', compact(
            'transaksi',
            'diskonRp',
            'diskonPersen',
            'totalHarga',
            'totalBayar'
        ));
    }
}
