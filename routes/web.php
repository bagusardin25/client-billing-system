<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JenisBiayaController;
use App\Http\Controllers\CabangUsahaController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Client Management
    Route::resource('clients', ClientController::class);
    Route::get('clients/{client}/whatsapp-reminder', [ClientController::class, 'sendWhatsAppReminder'])
        ->name('clients.whatsapp-reminder');

    // Invoice Management
    Route::put('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markAsPaid'])->name('invoices.mark-paid');
    Route::resource('invoices', InvoiceController::class);

    // Jenis Biaya Management
    Route::resource('jenis-biaya', JenisBiayaController::class);

    // Cabang Usaha Management
    Route::resource('cabang-usaha', CabangUsahaController::class);

    // Pemasukan (Income)
    Route::resource('pemasukan', PemasukanController::class);

    // Pengeluaran (Expense)
    Route::resource('pengeluaran', PengeluaranController::class);

    // Laporan (Reports)
    Route::controller(App\Http\Controllers\LaporanController::class)->prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/pemasukan', 'pemasukan')->name('pemasukan');
        Route::get('/pengeluaran', 'pengeluaran')->name('pengeluaran');
        Route::get('/laba-rugi', 'labaRugi')->name('laba-rugi');
    });
});

require __DIR__.'/auth.php';
