<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DiagnosaController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\JenisTindakanController;
use App\Http\Controllers\RekapFeeRSIAController;
use App\Http\Controllers\RekapPendapatanController;
use App\Http\Controllers\TindakanPasienController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use App\Models\TindakanPasien;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect('/login');
    } else {
        // if (Auth::user()->role === 'admin') {
        //     return redirect('/tindakanPasien');
        // } else if (Auth::user()->role === 'superadmin') {
        //     return redirect('/super');
        // } else if (Auth::user()->role === 'dokter') {
        //     return redirect('/rekapPendapatan');
        // }
        return view('welcome');
        
    }
});

Route::get('/forgot', function () {
    return view('forgot');
});

Route::middleware(['auth', 'superadmin'])->group(function () {
});

Route::resource('dokter', DokterController::class);
Route::resource('super', DokterController::class);
Route::resource('admin', AdminController::class);
Route::resource('jenistindakan', JenisTindakanController::class);
Route::resource('diagnosa', DiagnosaController::class);
Route::resource('tindakanPasien', TindakanPasienController::class);
Route::resource('rekapfeersia', RekapFeeRSIAController::class);
Route::resource('rekapPendapatan', RekapPendapatanController::class);
Route::resource('verification', VerificationController::class);
Route::resource('user', UserController::class);



Route::post('dokter/aktifkan', [DokterController::class, 'aktifkan'])->name('dokter.aktifkan');
Route::post('dokter/nonaktifkan', [DokterController::class, 'nonaktifkan'])->name('dokter.nonaktifkan');

Route::post('admin/aktifkan', [AdminController::class, 'aktifkan'])->name('admin.aktifkan');
Route::post('admin/nonaktifkan', [AdminController::class, 'nonaktifkan'])->name('admin.nonaktifkan');

Route::post('diagnosa/aktifkan', [DiagnosaController::class, 'aktifkan'])->name('diagnosa.aktifkan');
Route::post('diagnosa/nonaktifkan', [DiagnosaController::class, 'nonaktifkan'])->name('diagnosa.nonaktifkan');

Route::post('verification/terima', [VerificationController::class, 'terima'])->name('verification.terima');
Route::post('verification/tolak', [VerificationController::class, 'tolak'])->name('verification.tolak');

Auth::routes();

Route::get('/tindakanPasien/{bulan?}/{tahun?}', [TindakanPasienController::class, 'index'])->name('tindakanpasien.index');
Route::get('/rekapfeersia/{bulan?}/{tahun?}', [RekapFeeRSIAController::class, 'index'])->name('rekapRSIA.index');
Route::get('/rekapPendapatan/{bulan?}/{tahun?}/{dokter?}', [RekapPendapatanController::class, 'index'])->name('rekapPendapatan.index');

Route::get('/print/feersia', [RekapFeeRSIAController::class, 'printPdf'])->name('feersia.print');
Route::get('/print/feedokter', [RekapPendapatanController::class, 'printPdf'])->name('feedokter.print');

Route::post('jenistindakan/aktifkan', [JenisTindakanController::class, 'aktifkan'])->name('jenistindakan.aktifkan');
Route::post('jenistindakan/nonaktifkan', [JenisTindakanController::class, 'nonaktifkan'])->name('jenistindakan.nonaktifkan');
Route::post('jenistindakan/ubahpersentase', [JenisTindakanController::class, 'ubahpersentase'])->name('jenistindakan.ubahpersentase');

Route::get('/ubahpassword', [AdminController::class, 'ubahPassword'])->name('ubahPassword');
Route::get('/newPassword', [AdminController::class, 'newPassword'])->name('newPassword');


Route::get('/setting', function () {
    return view('setting');
});
Route::get('/setting', [AdminController::class, 'setting'])->name('setting');

Route::post('/saveData', [AdminController::class, 'saveData'])->name('admin.saveData');
Route::post('/save', [AdminController::class, 'saveData'])->name('save');
