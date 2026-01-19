<?php




use App\Http\Controllers\Backend\DashboardController;

use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\JaringanController;
use App\Http\Controllers\KeluhanController;
use App\Http\Controllers\PopController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SpkController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LayananIndukController;
use App\Http\Controllers\LayananEntryController;
use App\Http\Controllers\Backend\SettingsController;
use App\Http\Controllers\Backend\ProfilesController;
use App\Http\Controllers\Backend\UserLoginAsController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', 'HomeController@redirectAdmin')->name('index');
Route::get('/home', 'HomeController@index')->name('home');


/**
 * Admin routes.
 */
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('roles', RolesController::class);



    // Settings Routes.
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'store'])->name('settings.store');

    // Login as & Switch back
    Route::resource('users', UsersController::class);
    Route::get('users/{id}/login-as', [UserLoginAsController::class, 'loginAs'])->name('users.login-as');
    Route::post('users/switch-back', [UserLoginAsController::class, 'switchBack'])->name('users.switch-back');

    // Routes untuk Pelanggan
    Route::group(['prefix' => 'pelanggan', 'as' => 'pelanggan.'], function () {
        Route::get('/personal', [App\Http\Controllers\PelangganController::class, 'personal'])->name('personal');
        Route::get('/perusahaan', [App\Http\Controllers\PelangganController::class, 'perusahaan'])->name('perusahaan');
    });

    // Rute untuk Jaringan (dari JaringanController Anda)
    Route::prefix('jaringan')->name('jaringan.')->group(function () {
        Route::get('/node', [JaringanController::class, 'node'])->name('node');
        // Rute untuk CRUD Wilayah (Provinsi, Kabupaten, Kelurahan, Bagian)
        Route::prefix('wilayah')->name('wilayah.')->group(function () {
            Route::get('/', [WilayahController::class, 'index'])->name('index');
            Route::get('/export', [ExportController::class, 'wilayahBagian'])->name('export');
            Route::post('/', [WilayahController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [WilayahController::class, 'edit'])->name('edit');
            Route::put('/{id}', [WilayahController::class, 'update'])->name('update');
            Route::delete('/{id}', [WilayahController::class, 'destroy'])->name('destroy');
            // API endpoint untuk cascading dropdown
            Route::get('/children', [WilayahController::class, 'getChildren'])->name('children');
        });
        // PERUBAHAN UTAMA: Rute untuk POP, menggunakan PopController
        Route::prefix('pop')->name('pop.')->group(function () {
            Route::get('/', [PopController::class, 'index'])->name('index');
            Route::post('/', [PopController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [PopController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PopController::class, 'update'])->name('update');
            Route::delete('/{id}', [PopController::class, 'destroy'])->name('destroy');
        });
    });
    Route::prefix('layanan')->name('layanan.')->group(function () {

        // Rute untuk Layanan Induk
        Route::get('/induk', [LayananIndukController::class, 'index'])->name('induk.index');
        Route::post('/induk', [LayananIndukController::class, 'store'])->name('induk.store');
        Route::get('/induk/{layananInduk}/edit', [LayananIndukController::class, 'edit'])->name('induk.edit');
        Route::put('/induk/{layananInduk}', [LayananIndukController::class, 'update'])->name('induk.update');
        Route::delete('/induk/{layananInduk}', [LayananIndukController::class, 'destroy'])->name('induk.destroy');

        // Pastikan route ini ada
        Route::get('/entry', [LayananEntryController::class, 'index'])->name('entry.index'); 
        Route::get('/entry/export', [ExportController::class, 'layananEntry'])->name('entry.export');
        Route::post('/entry', [LayananEntryController::class, 'store'])->name('entry.store');
        Route::get('/entry/{layananEntry}', [LayananEntryController::class, 'show'])->name('entry.show');
        Route::get('/entry/{layananEntry}/edit', [LayananEntryController::class, 'edit'])->name('entry.edit');
        Route::put('/entry/{layananEntry}', [LayananEntryController::class, 'update'])->name('entry.update');
        Route::delete('/entry/{layananEntry}', [LayananEntryController::class, 'destroy'])->name('entry.destroy');
    });
   // Rute untuk Keluhan (dibuat secara manual)
    Route::get('keluhan', [KeluhanController::class, 'index'])->name('keluhan.index');
    Route::post('keluhan', [KeluhanController::class, 'store'])->name('keluhan.store');
    Route::get('keluhan/{keluhan}/edit', [KeluhanController::class, 'edit'])->name('keluhan.edit');
    Route::put('keluhan/{keluhan}', [KeluhanController::class, 'update'])->name('keluhan.update');
    Route::delete('keluhan/{keluhan}', [KeluhanController::class, 'destroy'])->name('keluhan.destroy');

    // Export Excel (mengikuti filter/search aktif)
    Route::get('keluhan/export', [ExportController::class, 'keluhan'])->name('keluhan.export');
    Route::get('pelanggan/export', [ExportController::class, 'pelanggan'])->name('pelanggan.export');
    
    // RUTE BARU UNTUK PENCARIAN PELANGGAN, DIPINDAHKAN KE DALAM GROUP INI
    Route::get('pelanggan-search', [InvoiceController::class, 'searchPelanggan'])->name('pelanggan.search');
    Route::get('layanan-by-pelanggan/{pelangganId}', [InvoiceController::class, 'getLayananByPelanggan'])->name('layanan.by.pelanggan');

    // PERBAIKAN DI SINI: Hapus 'invoice' dari prefix agar nama rute tidak ganda
    Route::prefix('invoice')->name('invoice.')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('/export', [ExportController::class, 'invoice'])->name('export');
        Route::post('/', [InvoiceController::class, 'store'])->name('store');
        Route::get('/create', [InvoiceController::class, 'create'])->name('create');
        Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
        Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('edit');
        Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('update');
        Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy');
        Route::get('/{invoice}/print', [InvoiceController::class, 'printInvoice'])->name('print');

    });
    // Definisi rute utama untuk daftar semua pelanggan (menggantikan personal/perusahaan)
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
    
    // Pastikan rute lainnya juga didefinisikan dengan benar
    Route::post('/pelanggan', [PelangganController::class, 'store'])->name('pelanggan.store');
    Route::get('/pelanggan/create', [PelangganController::class, 'create'])->name('pelanggan.create');
    Route::get('/pelanggan/{pelanggan}/edit', [PelangganController::class, 'edit'])->name('pelanggan.edit');
    Route::put('/pelanggan/{pelanggan}', [PelangganController::class, 'update'])->name('pelanggan.update');
    Route::delete('/pelanggan/{pelanggan}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');
    Route::get('/pelanggan/{pelanggan}', [PelangganController::class, 'show'])->name('pelanggan.show');

    // Kategori Pelanggan
    Route::get('kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::post('kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('kategori/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
// Rute untuk SPK
Route::prefix('spk')->name('spk.')->group(function () {
    Route::get('/', [SpkController::class, 'index'])->name('index');
    Route::get('export', [ExportController::class, 'spk'])->name('export');
    Route::post('/', [SpkController::class, 'store'])->name('store');
        Route::get('{spk}/print', [SpkController::class, 'printSpk'])->name('print')->where('spk', '.*');

    // Rute yang lebih spesifik harus diletakkan di atas
    Route::get('{spk}/edit', [SpkController::class, 'edit'])->name('edit')->where('spk', '.*');
    
    // Rute yang lebih umum diletakkan di bawah
    Route::get('{spk}', [SpkController::class, 'show'])->name('show')->where('spk', '.*');
    
    Route::put('{spk}', [SpkController::class, 'update'])->name('update')->where('spk', '.*');
    Route::delete('{spk}', [SpkController::class, 'destroy'])->name('destroy')->where('spk', '.*');

});
    Route::get('/notifications/{id}/read', [App\Http\Controllers\Backend\NotificationController::class, 'read'])->name('notifications.read');
});



Route::get('/profile/edit', [ProfilesController::class, 'edit'])->name('profile.edit');
Route::put('/profile/update', [ProfilesController::class, 'update'])->name('profile.update');
/**

 * Profile routes.
 */

// Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
//     Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
//         Route::get('/personal', [PelangganController::class, 'personal'])->name('personal');
//         Route::get('/perusahaan', [PelangganController::class, 'perusahaan'])->name('perusahaan');
//         Route::post('/store', [PelangganController::class, 'store'])->name('store');
//         Route::get('/{id}/edit', [PelangganController::class, 'edit'])->name('edit'); // Ini yang penting!
//         Route::put('/{id}', [PelangganController::class, 'update'])->name('update');
//         Route::get('/{id}', [PelangganController::class, 'show'])->name('show'); // Tambahkan ini
//         Route::delete('/{id}', [PelangganController::class, 'destroy'])->name('destroy');
//     });
    // di routes/web.php atau routes/admin.php
// });
