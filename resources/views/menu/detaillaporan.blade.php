@extends('adminlte::page')

@section('title', 'Detail Laporan')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="m-0">Detail Laporan</h1>
        <small class="text-muted">Pesanan #{{ $transaksi->id_pesanan }}</small>
    </div>

    <div>
        <a href="{{ route('transaksi.laporan') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <a href="{{ route('transaksi.pdf', $transaksi->id_pesanan) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-download"></i> Cetak Struk
        </a>
    </div>
</div>
@stop

@section('content')

<div class="row">

    <div class="col-md-4">

        <div class="card card-outline card-primary">

            <div class="card-header">
                <h3 class="card-title">Informasi Transaksi</h3>
            </div>

            <div class="card-body">

                <dl>
                    <dt>Nama Pembeli</dt>
                    <dd>{{ $transaksi->nama_pembeli }}</dd>

                    <dt>Tanggal</dt>
                    <dd>
                        <td>
                            {{ \Carbon\Carbon::parse($transaksi->tanggal)->translatedFormat('d/m/y') }}
                        </td>
                    </dd>

                    <dt>Staff Kasir</dt>
                    <dd>{{ $transaksi->staff->nama ?? '-' }}</dd>

                    <dt>Total Harga</dt>
                    <dd>
                        Rp {{ number_format($transaksi->harga, 0, ',', '.') }}
                    </dd>
                    <dt>Diskon</dt>
                    <dd>
                       Rp {{ number_format($diskonRp, 0, ',', '.') }}
                    </dd>
                    <dt>Total Bayar</dt>
                    <dd>
                        <h4 class="text-primary font-weight-bold">
                       Rp {{ number_format( $transaksi->total_bayar, 0, ',', '.') }}
                        </h4>
                    </dd>
                </dl>

            </div>

        </div>

    </div>

    <div class="col-md-8">

        <div class="card card-outline card-primary">

            <div class="card-header">
                <h3 class="card-title">Produk Dibeli</h3>
            </div>

            <div class="card-body table-responsive p-0">

                <table class="table table-hover table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 80px;">Foto</th>
                            <th>Nama Produk</th>
                            <th class="text-center">Qty</th>
                            <th class="text-right">Subtotal</th>
                            <th class="text-right">Diskon</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($transaksi->details as $detail)
                            <tr>

                                <td>
                                    @if($detail->produk && $detail->produk->foto)
                                        <img src="{{ asset('storage/' . $detail->produk->foto) }}" class="img-thumbnail"
                                            style="width:60px; height:40px; object-fit:cover;">
                                    @else
                                        <span class="badge badge-secondary">No Image</span>
                                    @endif
                                </td>

                                <td>
                                    <strong>
                                        {{ $detail->produk->nama_produk ?? 'Produk Dihapus' }}
                                    </strong>
                                </td>

                                <td class="text-center">
                                    {{ $detail->qty }}
                                </td>

                                <td class="text-right font-weight-bold">
                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@stop