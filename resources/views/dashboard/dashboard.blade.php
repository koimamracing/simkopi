@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">

        <div class="col-lg-4 col-sm-6 col-12">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalTransaksi }}</h3>
                    <p>Transaksi</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-sm-6 col-12">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalProduk }}</h3>
                    <p>Produk</p>
                </div>
                <div class="icon">
                    <i class="fas fa-box"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-sm-12 col-12">
            <div class="small-box bg-gradient-success">
                <div class="inner">
                    <h3 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        Rp {{ number_format($uangDiperoleh,0,',','.') }}
                    </h3>
                    <p>Uang Diperoleh</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>

    </div>
@stop