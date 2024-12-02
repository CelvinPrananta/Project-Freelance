<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\NotificationController;

// ----------------------------- Menu Sidebar Aktif ----------------------------- //
function set_active($route)
{
    if (is_array($route)) {
        return in_array(Request::path(), $route) ? 'active' : '';
    }
    return Request::path() == $route ? 'active' : '';
}

// ----------------------------- Autentikfikasi Login ----------------------------- //
Route::get('/', function () {
    return view('auth.landing');
});

Route::get('/login', function () {
    return view('auth.login');
});

// ----------------------------- Autentikfikasi MultiLevel ----------------------------- //
Route::group(['middleware' => 'auth'], function () {
    Route::get('home', function () {
        return view('home');
    });
});
Auth::routes();

// ----------------------------- Halaman Utama ----------------------------- //
Route::controller(HomeController::class)->middleware(['auth', 'auth.session'])->group(function () {
    Route::get('/home', 'index')->name('home');
    Route::patch('/update-tema/{id}', 'updateTemaAplikasi')->name('updateTemaAplikasi');
    Route::get('/ulangtahun', 'ulangtahun')->name('ulangtahun');
    Route::post('/mention-tag-description', 'mentionDescriptionNotification')->name('mention-tag-description');
    Route::post('/mention-tag-checklist', 'mentionChecklistNotification')->name('mention-tag-checklist');
    Route::post('/mention-tag-comment', 'mentionCommentNotification')->name('mention-tag-comment');
});

// ----------------------------- Masuk Aplikasi ----------------------------- //
Route::controller(LoginController::class)->group(function () {
    Route::get('/', 'landing')->name('landing');
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

// ----------------------------- Daftar Akun ----------------------------- //
Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'tampilanDaftar')->name('daftar');
    Route::post('/register', 'daftarAplikasi')->name('daftar');
});

// ----------------------------- Lupa Kata Sandi ----------------------------- //
Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('lupa-kata-sandi', 'getEmail')->name('lupa-kata-sandi');
    Route::post('lupa-kata-sandi', 'postEmail')->name('lupa-kata-sandi');
});

// ----------------------------- Atur Ulang Kata Sandi ----------------------------- //
Route::controller(ResetPasswordController::class)->group(function () {
    Route::get('ubah-kata-sandi/{token}', 'getPassword')->name('ubah-kata-sandi');
    Route::post('ubah-kata-sandi', 'updatePassword')->name('ubah-kata-sandi');
});

// ----------------------------- Pengelola Pengguna ----------------------------- //
Route::controller(UserManagementController::class)->middleware(['auth', 'auth.session'])->group(function () {
    Route::get('manajemen/pengguna', 'index')->middleware('isAdmin')->name('manajemen-pengguna');
    Route::get('get-users-data', 'getPenggunaData')->name('get-users-data');
    Route::get('riwayat/aktivitas', 'tampilanUserLogAktivitas')->middleware('isAdmin')->name('riwayat-aktivitas');
    Route::get('riwayat/otentikasi', 'tampilanLogAktivitas')->middleware('isAdmin')->name('riwayat-aktivitas-otentikasi');
    Route::get('profile/{user_id}', 'profileUser')->middleware('isAdmin')->name('showProfile');
    Route::get('profile', 'profileUser')->name('profile');
    Route::post('profile/perbaharui/data-pengguna', 'perbaharuiDataPengguna')->name('profile/perbaharui/data-pengguna');
    Route::post('profile/perbaharui/foto', 'perbaharuiFotoProfile')->name('profile/perbaharui/foto');
    Route::post('data/pengguna/tambah-data', 'tambahAkunPengguna')->name('data/pengguna/tambah-data');
    Route::post('data/pengguna/perbaharui', 'perbaharuiAkunPengguna')->name('data/pengguna/perbaharui');
    Route::post('data/pengguna/hapus', 'hapusAkunPengguna')->name('data/pengguna/hapus');
    Route::get('ubah-kata-sandi', 'tampilanPerbaharuiKataSandi')->name('rubah-kata-sandi');
    Route::post('change/password/db', 'perbaharuiKataSandi')->name('change/password/db');
    Route::get('get-history-activity', 'getHistoryActivity')->name('get-history-activity');
    Route::delete('delete/history', 'deleteHistoryActivity')->name('delete-all-history');
    Route::delete('delete/history-oten', 'deleteHistoryOtentifikasi')->name('delete-all-otentifikasi');
    Route::get('get-aktivitas-pengguna', 'getAktivitasPengguna')->name('get-aktivitas-pengguna');
});

Route::controller(EmployeeController::class)->middleware(['auth', 'auth.session'])->group(function () {
    Route::get('data/satuan', 'index')->name('data-satuan');
    Route::get('get-data-satuan', 'getDataSatuan')->name('get-data-satuan');
    Route::post('data/satuan/tambah-data', 'addDataSatuan')->name('data/satuan/tambah-data');
    Route::post('data/satuan/edit-data', 'editDataSatuan')->name('data/satuan/edit-data');
    Route::post('data/satuan/hapus-data', 'deleteDataSatuan')->name('data/satuan/hapus-data');
    Route::get('data/satuan/cari', 'searchDataSatuan')->name('data/satuan/cari');
});

// ----------------------------- Notifikasi ----------------------------- //
Route::prefix('tampilan/semua/notifikasi')->controller(NotificationController::class)->middleware(['auth', 'auth.session'])->group(function () {
    Route::get('/', 'tampilanNotifikasi')->name('tampilan-semua-notifikasi');
    Route::get('/detail-notif/{notif_id}', 'getDataNotif')->name('get-detail-notif');
    Route::get('/hapus-data/{id}', 'hapusNotifikasi')->name('tampilan-semua-notifikasi-hapus-data');
    Route::post('/notifikasi/dibaca/{id}', 'bacaNotifikasi')->name('bacaNotifikasi');
    Route::post('/notifikasi/dibaca-semua', 'bacasemuaNotifikasi')->name('bacasemuaNotifikasi');
    Route::delete('/hapus-all-notif', 'hapusSemua')->name('delete-all-notif');
});