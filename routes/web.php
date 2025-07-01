<?php



use App\Http\Controllers\ActionLogController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\ModulesController;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\JaringanController;
use App\Http\Controllers\PopController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\Backend\SettingsController;
use App\Http\Controllers\Backend\ProfilesController;
use App\Http\Controllers\Backend\UserLoginAsController;
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
Route::get('/action-log', [ActionLogController::class, 'index'])->name('actionlog.index');

/**
 * Admin routes.
 */
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('roles', RolesController::class);

    // Modules Routes.
    Route::get('/modules', [ModulesController::class, 'index'])->name('modules.index');
    Route::post('/modules/toggle-status/{module}', [ModulesController::class, 'toggleStatus'])->name('modules.toggle-status');
    Route::post('/modules/upload', [ModulesController::class, 'upload'])->name('modules.upload');
    Route::delete('/modules/{module}', [ModulesController::class, 'destroy'])->name('modules.delete');

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
            Route::post('/', [WilayahController::class, 'store'])->name('store');
            Route::delete('/{id}', [WilayahController::class, 'destroy'])->name('destroy');
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
});
Route::get('/profile/edit', [ProfilesController::class, 'edit'])->name('profile.edit');
Route::put('/profile/update', [ProfilesController::class, 'update'])->name('profile.update');
/**
 * 
 * Profile routes.
 */
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/personal', [PelangganController::class, 'personal'])->name('personal');
        Route::get('/perusahaan', [PelangganController::class, 'perusahaan'])->name('perusahaan');
        Route::post('/store', [PelangganController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PelangganController::class, 'edit'])->name('edit'); // Ini yang penting!
        Route::put('/{id}', [PelangganController::class, 'update'])->name('update');
        Route::get('/{id}', [PelangganController::class, 'show'])->name('show'); // Tambahkan ini
        Route::delete('/{id}', [PelangganController::class, 'destroy'])->name('destroy');
    });
});
