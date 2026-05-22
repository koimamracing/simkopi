@extends('adminlte::page')

@section('title', 'Laporan Transaksi')

@section('content_header')
<h1>Laporan Transaksi</h1>
@stop

@section('content')

<div class="card card-outline card-primary">

    <div class="card-header">
        <form method="GET" action="{{ route('transaksi.laporan') }}">
            <div class="row">

                <div class="col-md-6">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Search Nama Pembeli / ID...">
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary btn-block">
                        Search
                    </button>
                </div>

                <div class="col-md-2">
                    <a href="{{ route('transaksi.laporan.excel') }}" class="btn btn-success btn-block">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                </div>

                <div class="col-md-4 text-right">
                    <span class="text-muted">
                        {{ \Carbon\Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY') }}
                    </span>
                </div>

            </div>
        </form>

        <div class="col-md-4">

            <form action="{{ route('transaksi.import') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="input-group">

                    <div class="custom-file">
                        <input type="file" name="file" class="custom-file-input" required>

                        <label class="custom-file-label">
                            Pilih Excel
                        </label>
                    </div>

                    <div class="input-group-append">
                        <button class="btn btn-info">
                            Import
                        </button>
                    </div>

                </div>

            </form>

        </div>
    </div>

    <div class="card-body table-responsive p-0">

        <table class="table table-hover table-bordered text-nowrap">

            <thead class="thead-light">
                <tr>
                    <th class="text-center">Pesanan Ke</th>
                    <th class="text-center">Nama Pembeli</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Staff Kasir</th>
                    <th class="text-center">Total Harga Bayar</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($transaksi as $item)
                    <tr>
                        <td class="font-weight-bold text-primary text-center">
                            {{ $item->id_pesanan }}
                        </td>

                        <td class="text-center">
                            {{ $item->nama_pembeli }}
                        </td>

                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                        </td>
                        <td class="text-center">
                            {{ $item->staff->nama }}
                        </td>

                        <td class="text-center font-weight-bold">
                            Rp {{ number_format($item->total_bayar, 0, ',', '.') }}
                        </td>

                        <td class="text-center">

                            <a href="{{ route('transaksi.detailLaporan', $item->id_pesanan) }}" class="btn btn-sm btn-info"
                                title="Detail">
                                Detail
                            </a>

                            <a href="{{ route('transaksi.pdf', $item->id_pesanan) }}" class="btn btn-sm btn-success"
                                title="PDF">
                                PDF
                            </a>

                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Tidak ada data transaksi.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>

    @if(method_exists($transaksi, 'hasPages') && $transaksi->hasPages())
        <div class="card-footer clearfix">
            <div class="float-right">
                {{ $transaksi->appends(request()->query())->links() }}
            </div>
        </div>
    @endif

</div>

@stop