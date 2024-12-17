<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\HkiController;
use App\Http\Controllers\KeteranganPublikController;
use App\Http\Controllers\PenelitianController;
use App\Http\Controllers\PkmController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\TugasPublikasiController;
use App\Http\Controllers\NotificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

//notif
Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');


//middleware guest
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [SesiController::class, 'index'])->name('loginPage');
    Route::post('/login', [SesiController::class, 'login'])->name('login');
    Route::get('register', [SesiController::class, 'regisPage'])->name('regisPage');
    Route::post('register', [SesiController::class, 'regisCreate'])->name('regisCreate');
});

//'welcome'
Route::get('/', [SesiController::class, 'welcome'])->name('welcome');

//Auth
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [SesiController::class, 'logout'])->name('logout');
    Route::get('/home', [AdminController::class, 'home'])->name('admin.home');
    Route::get('profil/{profil}/profil', [DosenController::class, 'profilView'])->name('dosen.profilView');
    Route::put('profil/{profil}/profile', [DosenController::class, 'updateProfile'])->name('dosen.updateProfile');
    Route::put('profil/{profil}', [DosenController::class, 'updatePassword'])->name('dosen.updatePassword');
});

//middleware auth dossen
Route::middleware(['auth', 'verified', 'role:dosen'])->group(function () {

    //dosen hki
    Route::get('hki', [HkiController::class, 'index'])->name('hki.index');
    Route::get('hki/create', [HkiController::class, 'create'])->name('hki.create');
    Route::post('hki', [HkiController::class, 'store'])->name('hki.store');
    Route::get('hki/{hki}/edit', [HkiController::class, 'edit'])->name('hki.edit');
    Route::put('hki/{hki}', [HkiController::class, 'update'])->name('hki.update');
    Route::get('hki/{hki}', [HkiController::class, 'show'])->name('hki.show');
    Route::delete('hki/{hki}', [HkiController::class, 'destroy'])->name('hki.destroy');
    Route::get('hki/{hki}/cetak', [HkiController::class, 'cetak'])->name('hki.cetak');

    //dosen pkm
    Route::get('pkm', [PkmController::class, 'index'])->name('pkm.index');
    Route::get('pkm/create', [PkmController::class, 'create'])->name('pkm.create');
    Route::post('pkm', [PkmController::class, 'store'])->name('pkm.store');
    Route::get('pkm/{pkm}/edit', [PkmController::class, 'edit'])->name('pkm.edit');
    Route::put('pkm/{pkm}', [PkmController::class, 'update'])->name('pkm.update');
    Route::delete('pkm/{pkm}', [PkmController::class, 'destroy'])->name('pkm.destroy');
    Route::get('pkm/{pkm}', [PkmController::class, 'show'])->name('pkm.show');

    //dosen penelitian
    Route::get('penelitian', [PenelitianController::class, 'index'])->name('penelitian.index');
    Route::get('peneliti/create', [PenelitianController::class, 'create'])->name('penelitian.create');
    Route::post('penelitian', [PenelitianController::class, 'store'])->name('penelitian.store');
    Route::get('penelitian/{penelitian}/edit', [PenelitianController::class, 'edit'])->name('penelitian.edit');
    Route::put('penelitian/{penelitian}', [PenelitianController::class, 'update'])->name('penelitian.update');
    Route::delete('penelitian/{penelitian}', [PenelitianController::class, 'destroy'])->name('penelitian.destroy');
    Route::get('penelitian/{penelitian}', [PenelitianController::class, 'show'])->name('penelitian.show');

    //dosen surat keterangan publik
    Route::get('ketpub', [KeteranganPublikController::class, 'index'])->name('ketpub.index');
    Route::get('ketpub/create', [KeteranganPublikController::class, 'create'])->name('ketpub.create');
    Route::post('ketpub', [KeteranganPublikController::class, 'store'])->name('ketpub.store');
    Route::get('ketpub/{ketpub}/edit', [KeteranganPublikController::class, 'edit'])->name('ketpub.edit');
    Route::put('ketpub/{ketpub}', [KeteranganPublikController::class, 'update'])->name('ketpub.update');
    Route::delete('ketpub/{ketpub}', [KeteranganPublikController::class, 'destroy'])->name('ketpub.destroy');
    Route::get('ketpub/{ketpub}', [KeteranganPublikController::class, 'show'])->name('ketpub.show');

    //dosen surat tugas publikasi
    Route::get('tugaspub', [TugasPublikasiController::class, 'index'])->name('tugaspub.index');
    Route::get('tugaspub/create', [TugasPublikasiController::class, 'create'])->name('tugaspub.create');
    Route::post('tugaspub', [TugasPublikasiController::class, 'store'])->name('tugaspub.store');
    Route::get('tugaspub/{tugaspub}/edit', [TugasPublikasiController::class, 'edit'])->name('tugaspub.edit');
    Route::put('tugaspub/{tugaspub}', [TugasPublikasiController::class, 'update'])->name('tugaspub.update');
    Route::delete('tugaspub/{tugaspub}', [TugasPublikasiController::class, 'destroy'])->name('tugaspub.destroy');
    Route::get('tugaspub/{tugaspub}', [TugasPublikasiController::class, 'show'])->name('tugaspub.show');
});


//admin
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    //akun pengguna
    Route::get('akunPengguna', [AdminController::class, 'akunPenggunaView'])->name('admin.akunPengguna');
    Route::get('akunPengguna/create', [AdminController::class, 'akunPenggunaCreate'])->name('admin.akunPenggunaCreate');
    Route::post('akunPengguna', [AdminController::class, 'akunPenggunaStore'])->name('admin.akunPenggunaStore');
    Route::get('akunPengguna/{akunPengguna}/edit', [AdminController::class, 'akunPenggunaEdit'])->name('admin.akunPenggunaEdit');
    Route::put('akunPengguna/{akunPengguna}', [AdminController::class, 'akunPenggunaUpdate'])->name('admin.akunPenggunaUpdate');
    Route::delete('akunPengguna/{akunPengguna}', [AdminController::class, 'akunPenggunaDestroy'])->name('admin.akunPenggunaDestroy');
    //hki
    Route::get('hkiView', [AdminController::class, 'hkiView'])->name('admin.hkiView');
    Route::get('hkiEdit/{hki}/edit', [AdminController::class, 'hkiEdit'])->name('admin.hkiEdit');
    Route::put('hkiUpdate/{hki}', [AdminController::class, 'hkiUpdate'])->name('admin.hkiUpdate');
    Route::get('hkiShow/{hkiShow}', [HkiController::class, 'show'])->name('admin.hkiShow');
    Route::get('hkiShow/{hkiShow}/download', [HkiController::class, 'downloadWord'])->name('admin.hkiDownload');
    //pkm
    Route::get('pkmView', [AdminController::class, 'pkmView'])->name('admin.pkmView');
    Route::get('pkmEdit/{pkm}/edit', [AdminController::class, 'pkmEdit'])->name('admin.pkmEdit');
    Route::put('pkmUpdate/{pkm}', [AdminController::class, 'pkmUpdate'])->name('admin.pkmUpdate');
    Route::get('pkmShow/{pkmShow}', [PkmController::class, 'show'])->name('admin.pkmShow');
    Route::get('pkmShow/{pkmShow}/download', [PkmController::class, 'downloadWord'])->name('admin.pkmDownload');
    //penelitian
    Route::get('penelitianView', [AdminController::class, 'penelitianView'])->name('admin.penelitianView');
    Route::get('penelitianEdit/{penelitian}/edit', [AdminController::class, 'penelitianEdit'])->name('admin.penelitianEdit');
    Route::put('penelitianUpdate/{penelitian}', [AdminController::class, 'penelitianUpdate'])->name('admin.penelitianUpdate');
    Route::get('penelitianShow/{penelitianShow}', [PenelitianController::class, 'show'])->name('admin.penelitianShow');
    Route::get('penelitianShow/{penelitianShow}/download', [PenelitianController::class, 'downloadWord'])->name('admin.penelitianDownload');
    //ketpub
    Route::get('ketpubView', [AdminController::class, 'ketpubView'])->name('admin.ketpubView');
    Route::get('ketpubEdit/{ketpub}/edit', [AdminController::class, 'ketpubEdit'])->name('admin.ketpubEdit');
    Route::put('ketpubUpdate/{ketpub}', [AdminController::class, 'ketpubUpdate'])->name('admin.ketpubUpdate');
    Route::get('ketpubShow/{ketpubShow}', [KeteranganPublikController::class, 'show'])->name('admin.ketpubShow');
    Route::get('ketpubShow/{ketpubShow}/download', [KeteranganPublikController::class, 'downloadWord'])->name('admin.ketpubDownload');
    //tugaspub
    Route::get('tugaspubView', [AdminController::class, 'tugaspubView'])->name('admin.tugaspubView');
    Route::get('tugaspubEdit/{tugaspub}/edit', [AdminController::class, 'tugaspubEdit'])->name('admin.tugaspubEdit');
    Route::put('tugaspubUpdate/{tugaspub}', [AdminController::class, 'tugaspubUpdate'])->name('admin.tugaspubUpdate');
    Route::get('tugaspubShow/{tugaspubShow}', [TugasPublikasiController::class, 'show'])->name('admin.tugaspubShow');
    Route::get('tugaspubShow/{tugaspubShow}/download', [TugasPublikasiController::class, 'downloadWord'])->name('admin.tugaspubDownload');
});


Route::get('/email/verify', function () {
    return view('emails.verify-email'); // Ganti dengan tampilan yang Anda miliki
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // Verifikasi email berhasil
    return redirect('/login')->with('success', 'Email berhasil diverifikasi.');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Email verifikasi telah dikirim ulang.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');