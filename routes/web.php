<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LockScreen;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\CopyCardController;
use App\Http\Controllers\SSOController;

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
    return view('auth.login');
    return view('auth.landing');
});

// Route::get('/login', function () {
//     return view('auth.login');
// });

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
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
    Route::get('/authorization/{username}', 'autorize')->name('autorize');
    Route::get('/landing', 'landing')->name('landing');
});

// ----------------------------- Daftar Akun ----------------------------- //
Route::controller(RegisterController::class)->group(function () {
    Route::get('/daftar', 'tampilanDaftar')->name('daftar');
    Route::post('/daftar', 'daftarAplikasi')->name('daftar');
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

// ----------------------------- Notifikasi ----------------------------- //
Route::prefix('tampilan/semua/notifikasi')->controller(NotificationController::class)->middleware(['auth', 'auth.session'])->group(function () {
    Route::get('/', 'tampilanNotifikasi')->name('tampilan-semua-notifikasi');
    Route::get('/detail-notif/{notif_id}', 'getDataNotif')->name('get-detail-notif');
    Route::get('/hapus-data/{id}', 'hapusNotifikasi')->name('tampilan-semua-notifikasi-hapus-data');
    Route::post('/notifikasi/dibaca/{id}', 'bacaNotifikasi')->name('bacaNotifikasi');
    Route::post('/notifikasi/dibaca-semua', 'bacasemuaNotifikasi')->name('bacasemuaNotifikasi');
    Route::delete('/hapus-all-notif', 'hapusSemua')->name('delete-all-notif');
});

// ----------------------------- Team ----------------------------- //
Route::prefix('team')->controller(TeamController::class)->middleware(['auth', 'auth.session'])->group(function () {
    Route::post("/", "createTeam")->name("doCreateTeam");
    Route::get("/show", "showTeams")->name("showTeams");
    Route::get("/cari", "search")->name("searchTeam");
    Route::get("/showboard/{team_ids}", "showTeam")->middleware('userInTeam')->name("viewTeam");
    Route::get("/undangan/diterima/{team_id}/{user_id}", "acceptInvite")->name("acceptTeamInvite");
    Route::get("/undangan/ditolak/{team_id}/{user_id}", "rejectInvite")->name("rejectTeamInvite");
    Route::get("/showboard/cari/papan/{team_ids}", "searchBoard")->middleware('userInTeam')->name("searchBoard");
    Route::post("/perbaharui/tim/{team_ids}", "updateData")->middleware('userInTeam')->name("doTeamDataUpdate");
    Route::post("/hapus/tim/{team_ids}", "deleteTeam")->middleware('userInTeam')->name("doDeleteTeam");
    Route::post("/hapus/pengguna/{team_id}", "deleteMembers")->name("deleteTeamMember");
    Route::post("/undangan/{team_ids}", "inviteMembers")->middleware('userInTeam')->name("doInviteMembers");
    Route::post("/perbaharui/foto/{team_id}", "updateImage")->middleware('userInTeam')->name("doChangeTeamImage");
    Route::get("/undangan/{team_id}/{user_id}", "getInvite")->name("getInvite");
    Route::post("/tinggalkan/{team_ids}", "leaveTeam")->middleware('userInTeam')->name("doLeaveTeam");
});

Route::prefix('team/board')->controller(BoardController::class)->middleware(['auth', 'auth.session'])->group(function () {
    Route::post('/kartu/pulihkan', 'pulihkanKartu')->name('pulihkanKartu');
    Route::post('/hapus-kartu-permanen', 'hapusKartuPermanen')->name('hapusKartuPermanen');
    Route::get('/pulihkan-kartu', 'dataPulihkanKartu')->name('pulihkan-kartu');
    Route::post('/perbaharui/Posisi/kolom', 'perbaharuiPosisiKolom')->name('perbaharuiPosisiKolom');
    Route::post("/{team_id}/{board_id}/cari", "searchCol")->name("searchCol");
});

// ----------------------------- Board ----------------------------- //
Route::prefix('team/board')->controller(BoardController::class)->middleware(['auth', 'auth.session'])->group(function () {
    Route::post("/{team_ids}", "createBoard")->middleware('userInTeam')->name("createBoard");
    Route::get("/{team_id}/{board_id}", "showBoard")->middleware('boardAccess')->name("board");
    Route::post("/{team_id}/{board_id}", "updateBoard")->middleware(['boardAccess'])->name("updateBoard");
    Route::post("/{team_id}/{board_id}/hapus", "deleteBoard")->middleware(['boardAccess'])->name("deleteBoard");
    Route::post("/{team_id}/{board_id}/kolom", "addColumn")->middleware('boardAccess')->name("addCol");
    Route::post("/{team_id}/{board_id}/updatekolom", "updateCol")->middleware('boardAccess')->name("updateCol");
    Route::post("/{team_id}/{board_id}/hapuskolom", "deleteCol")->middleware(['boardAccess', 'isAdmin'])->name("deleteCol");
    Route::post("/{team_id}/{board_id}/kolom/{column_id}/kartu", "addCard")->middleware('boardAccess')->name("addCard");
    Route::post("/kolom/kartu/perbaharui/{card_id}", "perbaharuiKartu")->name("perbaharuiKartu");
    Route::post("/kolom/kartu/hapus/{card_id}", "hapusKartu")->name("hapusKartu");
    Route::post("/kolom/kartu/deskripsi/tambah", "addDescription")->name("addDescription");
    Route::post("/kolom/kartu/komentar/{card_id}", "komentarKartu")->name("komentarKartu");
    Route::post("/kolom/kartu/cover/perbaharui", "perbaharuiCover")->name("perbaharuiCover");
    Route::post("/kolom/kartu/cover/hapus", "hapusCover")->name("hapusCover");
    Route::post('/get/data/kartu', 'getDataKartu')->name('getDataKartu');
    Route::post('/perbaharui/Posisi/kartu', 'perbaharuiPosisiKartu')->name('perbaharuiPosisiKartu');
    Route::post('/perbaharui/Posisi/judul', 'perbaharuiPosisiJudul')->name('perbaharuiPosisiJudul');
    Route::post('/perbaharui/Posisi/ceklist', 'perbaharuiPosisiCeklist')->name('perbaharuiPosisiCeklist');
    Route::post('/perbaharui/Posisi/kartu-ke-kolom', 'perbaharuiPosisiKartuKeKolom')->name('perbaharuiPosisiKartuKeKolom');
    // Route::post('/kolom/pulihkan', 'pulihkanKolom')->name('pulihkanKolom');
    // Route::post('/hapus-kolom-permanen', 'hapusKolomPermanen')->name('hapusKolomPermanen');
    // Route::get('/pulihkan-kolom', 'dataPulihkanKolom')->name('dataPulihkanKolom');
});

// ----------------------------- Checklist ----------------------------- //
Route::prefix('board')->controller(ChecklistController::class)->middleware(['auth', 'auth.session'])->group(function () {
    Route::post("/title/tambah", "addTitle")->name("addTitle");
    Route::post("/title/perbaharui", "updateTitle")->name("updateTitle");
    Route::post("/title/hapus", "hapusTitle")->name("hapusTitle");
    Route::post("/checklist/hapus", "hapusChecklist")->name("hapusChecklist");
    Route::post("/checklist/tambah", "addChecklist")->name("addChecklist");
    Route::post("/checklist/perbaharui", "updateChecklist")->name("updateChecklist");
    Route::get('/checklist/perbaharui/{title_checklists_id}', 'getProgress');
    Route::post('/pulihkan', 'pulihkanJudulChecklist')->name('pulihkanJudulChecklist');
    Route::post('/hapus-judul-checklist-permanen', 'hapusJudulChecklistPermanen')->name('hapusJudulChecklistPermanen');
    Route::post('/checklist/pulihkan', 'pulihkanChecklist')->name('pulihkanChecklist');
    Route::post('/hapus-checklist-permanen', 'hapusChecklistPermanen')->name('hapusChecklistPermanen');
    Route::get('/pulihkan-title-checklist', 'dataPulihkanTitleChecklist')->name('dataPulihkanTitleChecklist');
    Route::post('/membuat/template/judul', 'templateTitle')->name('templateTitle');
    Route::post('/perbaharui/semua/checklist', 'perbaharuiSemuaChecklist')->name('perbaharuiSemuaChecklist');
});

// ----------------------------- Copy Card ----------------------------- //
Route::controller(CopyCardController::class)->group(function () {
    Route::post('/copy-card/{column_id}/{id}', 'copyCard')->name('copyCard');
    Route::get('/cards/{id}/total-active-checklists', 'getTotalActiveChecklists');
});

// ----------------------------- SSO CLIENT ----------------------------- //
Route::controller(SSOController::class)->group(function () {
    Route::get('callback', 'callback');
});
