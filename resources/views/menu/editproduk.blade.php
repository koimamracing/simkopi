@extends('adminlte::page')

@section('title', 'Edit Produk')

@section('content_header')
    <h1>Edit Produk</h1>
@stop

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card card-primary shadow">
            <div class="card-header">
                <h3 class="card-title">Form Edit Produk</h3>

                <div class="card-tools">
                    <a href="{{ route('produk.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <form action="{{ route('produk.update', $produk->id_produk) }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="card-body">

                    {{-- NAMA --}}
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input type="text"
                            name="nama_produk"
                            value="{{ old('nama_produk', $produk->nama_produk) }}"
                            class="form-control"
                            required>
                    </div>

                    {{-- KATEGORI --}}
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="kategori" class="form-control" required>
                            <option value="Kopi" {{ $produk->kategori == 'Kopi' ? 'selected' : '' }}>Kopi</option>
                            <option value="Susu" {{ $produk->kategori == 'Susu' ? 'selected' : '' }}>Susu</option>
                            <option value="Dessert" {{ $produk->kategori == 'Dessert' ? 'selected' : '' }}>Dessert</option>
                            <option value="Lainnya" {{ $produk->kategori == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    {{-- HARGA --}}
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number"
                            name="harga"
                            value="{{ old('harga', $produk->harga) }}"
                            class="form-control"
                            required>
                    </div>

                    {{-- STOK --}}
                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number"
                            name="stok"
                            value="{{ old('stok', $produk->stok) }}"
                            class="form-control"
                            required>
                    </div>

                    {{-- FOTO SAAT INI --}}
                    <div class="form-group">
                        <label>Foto Saat Ini</label>
                        <div>
                            @if($produk->foto)
                                <img src="{{ asset('storage/' . $produk->foto) }}"
                                    class="img-thumbnail"
                                    style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <p class="text-muted">Tidak ada foto</p>
                            @endif
                        </div>
                    </div>

                    {{-- GANTI FOTO --}}
                    <div class="form-group">
                        <label>Ganti Foto</label>
                        <input type="file"
                            name="foto"
                            class="form-control-file"
                            accept="image/jpeg,image/png">
                        <small class="text-muted">Format: JPG, PNG</small>
                    </div>

                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Update Produk
                    </button>
                </div>

            </form>

        </div>

    </div>
</div>

@stop