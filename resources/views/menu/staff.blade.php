@extends('adminlte::page')

@section('title', 'Data Staff')

@section('content_header')
    <h1>Data Staff</h1>
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

        <div class="row align-items-center">

            <div class="col-md-4">
                <form method="GET" action="">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                               placeholder="Search Staff..."
                               value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-4 text-center">
                <h5 class="mb-0">Data Staff</h5>
                <small class="text-muted">Kelola staff & admin</small>
            </div>

            <div class="col-md-4 text-right">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">
                    <i class="fas fa-plus-circle"></i> Tambah Staff
                </button>
            </div>

        </div>

    </div>
</div>

<div class="row">

    <div class="col-md-4">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalpekerja ?? 0 }}</h3>
                <p>Total Pekerja</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalAdmin ?? 0 }}</h3>
                <p>Total Admin</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-shield"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalStaff ?? 0 }}</h3>
                <p>Total Staff</p>
            </div>
            <div class="icon">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </div>

</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Staff</h3>
        <div class="card-tools">
            <span class="badge badge-info">{{ $staff->count() ?? 0 }} Data</span>
        </div>
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-striped text-nowrap">

            <thead class="text-center bg-dark">
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>No Telp</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($staff as $item)
                    <tr>

                        <td class="text-center">{{ $loop->iteration }}</td>

                        <td class="text-center">
                            @if($item->foto)
                                <img src="{{ asset('storage/'.$item->foto) }}"
                                     class="img-circle" width="40"  height="40">
                            @else
                                <i class="fas fa-user text-muted"></i>
                            @endif
                        </td>

                        <td class="text-center">{{ $item->nama }}</td>

                        <td class="text-center">
                            <span class="badge {{ $item->jabatan == 'Admin' ? 'badge-primary' : 'badge-success' }}">
                                {{ $item->jabatan }}
                            </span>
                        </td>

                        <td class="text-center">{{ $item->no_telp }}</td>
                        <td class="text-center">{{ $item->email }}</td>

                        <td class="text-center">

                            <button class="btn btn-warning btn-sm"
                                onclick="openEditModal({{ $item->id }}, '{{ $item->nama }}', '{{ $item->jabatan }}', '{{ $item->no_telp }}', '{{ $item->email }}')">
                                Edit
                            </button>

                            <form action="{{ route('staff.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus staff ini?')">
                                    Hapus
                                </button>
                            </form>

                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Tidak ada data staff
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>

<div class="modal fade" id="modalTambah">
    <div class="modal-dialog">
        <form action="{{ route('staff.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf

            <div class="modal-header">
                <h4 class="modal-title">Tambah Staff</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <input type="text" name="nama" class="form-control mb-2" placeholder="Nama" required>

                <select name="jabatan" class="form-control mb-2" required>
                    <option value="Admin">Admin</option>
                    <option value="Staff">Staff</option>
                </select>

                <input type="text" name="no_telp" class="form-control mb-2" placeholder="No Telp" required>

                <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>

                <input type="file" name="foto" class="form-control-file">

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Simpan</button>
            </div>

        </form>
    </div>
</div>

<div class="modal fade" id="modalEdit">
    <div class="modal-dialog">
        <form id="editForm" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h4 class="modal-title">Edit Staff</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <input type="text" name="nama" id="edit_nama" class="form-control mb-2">

                <select name="jabatan" id="edit_jabatan" class="form-control mb-2">
                    <option value="Admin">Admin</option>
                    <option value="Staff">Staff</option>
                </select>

                <input type="text" name="no_telp" id="edit_no_telp" class="form-control mb-2">

                <input type="email" name="email" id="edit_email" class="form-control mb-2">

                <input type="file" name="foto" class="form-control-file">

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Update</button>
            </div>

        </form>
    </div>
</div>

@stop

@section('js')
<script>
    function openEditModal(id, nama, jabatan, no_telp, email) {
        $('#modalEdit').modal('show');

        $('#edit_nama').val(nama);
        $('#edit_jabatan').val(jabatan);
        $('#edit_no_telp').val(no_telp);
        $('#edit_email').val(email);

        $('#editForm').attr('action', '/staff/' + id);
    }
</script>
@stop