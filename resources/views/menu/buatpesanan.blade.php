@extends('adminlte::page')

@section('title', 'Buat Pesanan')

@section('content_header')
    <h1>Buat Pesanan</h1>
@stop

@section('content')

<div class="card card-primary card-outline">

    <div class="card-header text-center">
        <h3 class="card-title font-weight-bold">SimKopi System</h3>
        <p class="text-muted mb-0">Form pembuatan transaksi & struk</p>
    </div>

    <div class="card-body">

        <form action="{{ route('buatpesanan.store') }}" method="POST">
            @csrf

            <div class="row">

                <div class="col-md-3">
                    <label>ID Pesanan</label>
                    <input type="text" name="id_pesanan" value="{{ $nextId }}" readonly
                           class="form-control bg-light">
                </div>

                <div class="col-md-3">
                    <label>Nama Pembeli</label>
                    <input type="text" name="nama_pembeli" class="form-control" required>
                </div>
                

                <div class="col-md-3">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}"
                           class="form-control" required readonly>
                </div>

                <div class="col-md-3">
                    <label>Staff</label>

                    <input type="hidden" name="id_staff" value="{{ $staff->id }}">

                    <input type="text"
                           value="{{ auth()->user()->name }}"
                           readonly
                           class="form-control bg-light">
                </div>

                <div class="col-md-3">
                    <label>Diskon(%)</label>
                    <input type="number" name="diskon" class="form-control" required>
                </div>

            </div>

            <hr>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 font-weight-bold">Produk Pesanan</h5>

                <button type="button" class="btn btn-primary btn-sm" onclick="tambahProduk()">
                    + Tambah Produk
                </button>
            </div>

            <div id="produk-wrapper">

                <div class="produk-item border rounded p-3 mb-3">

                    <div class="row">

                        <div class="col-md-8">
                            <label>Produk</label>
                            <select name="id_produk[]" class="form-control produk-select" onchange="updateHarga()">
                                <option value="">Pilih Produk</option>
                                @foreach($produk as $item)
                                    <option value="{{ $item->id_produk }}"
                                            data-harga="{{ $item->harga }}"
                                            {{ $item->stok <= 0 ? 'disabled' : '' }}>
                                        {{ $item->nama_produk }}
                                        - Rp {{ number_format($item->harga,0,',','.') }}
                                        {{ $item->stok <= 0 ? '(HABIS)' : '(Stok '.$item->stok.')' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label>Jumlah</label>
                            <input type="number" name="jumlah[]" value="1" min="1"
                                   class="form-control jumlah-input"
                                   onchange="updateHarga()">
                        </div>


                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button"
                                    class="btn btn-danger btn-block"
                                    onclick="hapusProduk(this)">
                                Hapus
                            </button>
                        </div>

                    </div>

                </div>

            </div>

            <hr>

            <div class="row">

                <div class="col-md-4">
                    <label>Total Harga</label>
                    <input type="number" id="total_harga" name="harga"
                           class="form-control bg-light font-weight-bold" readonly>
                </div>

            </div>

            <div class="text-right mt-4">
                <button type="submit" class="btn btn-success btn-lg">
                    Buat Struk
                </button>
            </div>

        </form>

    </div>

</div>

<template id="produk-template">
    <div class="produk-item border rounded p-3 mb-3">

        <div class="row">

            <div class="col-md-8">
                <label>Produk</label>
                <select name="id_produk[]" class="form-control produk-select" onchange="updateHarga()">
                    <option value="">Pilih Produk</option>
                    @foreach($produk as $item)
                        <option value="{{ $item->id_produk }}"
                                data-harga="{{ $item->harga }}"
                                {{ $item->stok <= 0 ? 'disabled' : '' }}>
                            {{ $item->nama_produk }}
                            - Rp {{ number_format($item->harga,0,',','.') }}
                            {{ $item->stok <= 0 ? '(HABIS)' : '(Stok '.$item->stok.')' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label>Jumlah</label>
                <input type="number" name="jumlah[]" value="1" min="1"
                       class="form-control jumlah-input"
                       onchange="updateHarga()">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="button"
                        class="btn btn-danger btn-block"
                        onclick="hapusProduk(this)">
                    Hapus
                </button>
            </div>

        </div>

    </div>
</template>

@stop

@section('js')
<script>
    function tambahProduk() {
        const template = document.getElementById('produk-template');
        const clone = template.content.cloneNode(true);
        document.getElementById('produk-wrapper').appendChild(clone);
        updateHarga();
    }

    function hapusProduk(btn) {
        const items = document.querySelectorAll('.produk-item');
        if (items.length > 1) {
            btn.closest('.produk-item').remove();
            updateHarga();
        }
    }

    function updateHarga() {
        let total = 0;

        document.querySelectorAll('.produk-item').forEach(item => {
            const select = item.querySelector('.produk-select');
            const qty = item.querySelector('.jumlah-input');

            const harga = parseInt(select?.options[select.selectedIndex]?.dataset.harga || 0);
            const jumlah = parseInt(qty?.value || 0);

            total += harga * jumlah;
        });

        document.getElementById('total_harga').value = total;
    }
</script>
@stop