<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Menu\ProdukController;
use App\Http\Controllers\Menu\LaporanController;
use App\Http\Controllers\Menu\LaporanExcelController;
use App\Http\Controllers\Menu\ImportExcelController;
use App\Http\Controllers\Menu\StaffController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi dikirim ulang!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])
    ->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
    ->name('password.email');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->name('password.update');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');
Route::get('admin/staff', [StaffController::class, 'index'])->name('staff.index');
Route::post('admin/staff', [StaffController::class, 'store'])->name('staff.store');
Route::get('admin/staff/{staff}/edit', [StaffController::class, 'edit'])->name('staff.edit');
Route::put('admin/staff/{staff}', [StaffController::class, 'update'])->name('staff.update');
Route::delete('admin/staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
Route::resource('produk', ProdukController::class);
Route::get('admin/produk', [ProdukController::class, 'index'])->name('produk.index');
Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
Route::get('admin/laporan', [LaporanController::class, 'laporan'])->name('transaksi.laporan');
Route::get('/menu/{id}', [LaporanController::class, 'detailLaporan'])->name('transaksi.detailLaporan');


Route::middleware(['auth'])->group(function () {

    Route::get('admin/pesanan', [LaporanController::class, 'index'])
        ->name('buatpesanan');

    Route::post('/buatpesanan/store', [LaporanController::class, 'store'])
        ->name('buatpesanan.store');

    Route::get('/transaksi/pdf/{id}', [LaporanController::class, 'generatePdf'])
        ->name('transaksi.pdf');
});

Route::get('admin/laporan/export/excel', [LaporanExcelController::class, 'export'])
    ->name('transaksi.laporan.excel');

Route::post('admin/laporan/import', [ImportExcelController::class, 'import'])
    ->name('transaksi.import');
