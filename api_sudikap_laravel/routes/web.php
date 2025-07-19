<?php
use App\Http\Controllers\AnggaranController;
use App\Http\Controllers\SppdController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\TmjabatanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\Logincontroller;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\UserLevelController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\UsersController;
 

Route::prefix('api/v1')->group(function () {
    Route::post('login', [Logincontroller::class, 'authenticate'])->name('login');
    Route::prefix('document')->group(function () {
        Route::get('download/{id}', [DocumentController::class, 'exportToWord'])->name('download');
    });
});
Route::group(['middleware' => ['jwt.verify', 'cors']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('/');
    Route::prefix('api/v1')->group(function () {
        Route::post('getdata', [UserLevelController::class, 'getuserdata'])->name('getdata');
        Route::prefix('disposisi')->group(function () {
            Route::get('currentdisposisi', [DisposisiController::class, 'getcurrentdisposisi'])->name('getcurrent');
            Route::get('list', [DisposisiController::class, 'index'])->name('list');
            Route::post('insert', [DisposisiController::class, 'store'])->name('insert');
            Route::get('detail/{id}', [DisposisiController::class, 'edit'])->name('detail');
            Route::post('update/{id}', [DisposisiController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [DisposisiController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('instansi')->group(function () {
            Route::get('list', [InstansiController::class, 'index'])->name('list');
            Route::post('insert', [InstansiController::class, 'store'])->name('insert');
            Route::get('detail/{id}', [InstansiController::class, 'edit'])->name('detail');
            Route::post('update', [InstansiController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [InstansiController::class, 'destroy'])->name('destroy');
        });
 
        Route::prefix('anggaran')->group(function () {
            Route::get('list', [AnggaranController::class, 'index'])->name('list');
            Route::post('insert', [AnggaranController::class, 'store'])->name('insert');
            Route::get('detail/{id}', [AnggaranController::class, 'edit'])->name('detail');
            Route::post('update', [AnggaranController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [AnggaranController::class, 'destroy'])->name('destroy');
        });
 

        Route::prefix('user')->group(function () {
            Route::get('list', [UsersController::class, 'index'])->name('list');
            Route::post('insert', [UsersController::class, 'store'])->name('insert');
            Route::get('detail/{id}', [UsersController::class, 'edit'])->name('detail');
            Route::post('update/{id}', [UsersController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [UsersController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('surat')->group(function () {
            Route::get('list', [SppdController::class, 'index'])->name('list');
            Route::post('insert', [SppdController::class, 'store'])->name('insert');
            Route::get('detail/{id}', [SppdController::class, 'edit'])->name('detail');
            Route::post('update/{id}', [SppdController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [SppdController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('surat_keluar')->group(function () {
            Route::get('list', [SuratKeluarController::class, 'index'])->name('list');
            Route::post('insert', [SuratKeluarController::class, 'store'])->name('insert');
            Route::get('detail/{id}', [SuratKeluarController::class, 'edit'])->name('detail');
            Route::post('update/{id}', [SuratKeluarController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [SuratKeluarController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('jenisarsip')->group(function () {
            Route::get('list', [JenisController::class, 'index'])->name('list');
            Route::post('insert', [JenisController::class, 'store'])->name('insert');
            Route::get('detail/{id}', [JenisController::class, 'edit'])->name('detail');
            Route::post('update/{id}', [JenisController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [JenisController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('getmaster')->group(function () {
            Route::post('jenis', [JenisController::class, 'JenisMaster'])->name('jenis');
            Route::post('satuan', [JenisController::class, 'masterSatuan'])->name('satuan');
        });
        Route::prefix('pegawai')->group(function () {
            Route::get('list', [PegawaiController::class, 'index'])->name('list');
            Route::post('insert', [PegawaiController::class, 'store'])->name('insert');
            Route::get('detail/{id}', [PegawaiController::class, 'edit'])->name('detail');
            Route::post('update/{id}', [PegawaiController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [PegawaiController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('tmjabatan')->group(function () {
            Route::get('list', [TmjabatanController::class, 'index'])->name('list');
            Route::post('insert', [TmjabatanController::class, 'store'])->name('insert');
            Route::get('detail/{id}', [TmjabatanController::class, 'edit'])->name('detail');
            Route::post('update/{id}', [TmjabatanController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [TmjabatanController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('arsip')->group(function () {
            Route::get('list', [ArsipController::class, 'index'])->name('list');
            Route::post('insert', [ArsipController::class, 'store'])->name('insert');
            Route::get('detail/{id}', [ArsipController::class, 'edit'])->name('detail');
            Route::post('update/{id}', [ArsipController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [ArsipController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('report')->group(function () {
            Route::post('datareportsurat', [SuratController::class, 'datareportsurat'])->name('datareportsurat');
            Route::post('diposisi', [SuratController::class, 'reportDisposisi'])->name('disposisi');
        });
        Route::prefix('satuan')->group(function () {
            Route::get('list', [SatuanController::class, 'list'])->name('list');
            Route::post('insert', [SatuanController::class, 'store'])->name('insert');
            Route::get('detail/{id}', [SatuanController::class, 'edit'])->name('detail');
            Route::post('update/{id}', [SatuanController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [SatuanController::class, 'destroy'])->name('destroy');
        });

    });

});
