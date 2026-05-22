<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Produk;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi';

    protected $fillable = [
        'id_pesanan',
        'id_produk',
        'qty',
        'subtotal'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_pesanan', 'id_pesanan');
    }
}
