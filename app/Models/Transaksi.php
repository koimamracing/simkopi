<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DetailTransaksi;


class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $primaryKey = 'id_pesanan';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_pesanan',
        'nama_pembeli',
        'harga',
        'tanggal',
        'id_staff',
        'diskon',
        'total_bayar'
    ];

    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_pesanan', 'id_pesanan');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'id_staff');
    }
}
