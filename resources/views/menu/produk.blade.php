@extends('adminlte::page')

@section('title', 'Data Produk')

@section('content_header')
<h1>Data Produk</h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="icon fas fa-check"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

<div class="card mb-3">
    <div class="card-body">

        <form method="GET" action="{{ route('produk.index') }}" class="row">

            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari produk..."
                    value="{{ request('search') }}">
            </div>

            <div class="col-md-3">
                <select name="type" class="form-control">
                    <option value="">Semua Kategori</option>
                    <option value="Kopi" {{ request('type') == 'Kopi' ? 'selected' : '' }}>Kopi</option>
                    <option value="Susu" {{ request('type') == 'Susu' ? 'selected' : '' }}>Susu</option>
                    <option value="Dessert" {{ request('type') == 'Dessert' ? 'selected' : '' }}>Dessert</option>
                    <option value="Lainnya" {{ request('type') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="Normal" {{ request('status') == 'Normal' ? 'selected' : '' }}>Normal</option>
                    <option value="Hampir Habis" {{ request('status') == 'Hampir Habis' ? 'selected' : '' }}>Hampir Habis
                    </option>
                    <option value="Habis" {{ request('status') == 'Habis' ? 'selected' : '' }}>Habis</option>
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary btn-block">
                    Filter
                </button>
            </div>

        </form>
    </div>
</div>

<div class="mb-3">
    <button class="btn btn-primary elevation-2" data-toggle="modal" data-target="#modalTambah">
        <i class="fas fa-plus-circle"></i> Tambah Produk
    </button>
    <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data"
                class="modal-content">

                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Produk</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="kategori" class="form-control" required>
                            <option value="Kopi">Kopi</option>
                            <option value="Susu">Susu</option>
                            <option value="Dessert">Dessert</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" name="harga" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Foto</label>
                        <input type="file" name="foto" class="form-control-file" accept="image/png, image/jpg, image/jpegphp">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Produk</h3>
        <div class="card-tools">
            <span class="badge badge-info">{{ $produk->count() }} Produk</span>
        </div>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="text-center">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($produk as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $item->nama_produk }}</td>
                        <td class="text-center">
                            <span class="badge badge-secondary">
                                {{ $item->kategori }}
                            </span>
                        </td>

                        <td class="text-center">
                            Rp {{ number_format($item->harga, 0, ',', '.') }}
                        </td>

                        <td class="text-center">
                            ({{ $item->stok }})
                            @if($item->stok == 0)
                                <span class="badge badge-danger">Habis</span>
                            @elseif($item->stok <= 5)
                                <span class="badge badge-warning">Hampir Habis</span>
                            @else
                                <span class="badge badge-success">Normal</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <a href="{{ route('produk.edit', $item->id_produk) }}" class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <form action="{{ route('produk.destroy', $item->id_produk) }}" method="POST" class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Tidak ada data produk
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@stop