<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HkiController;
use App\Http\Controllers\PkmController;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\KetuaController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\GrafikController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\KodeSuratController;
use App\Http\Controllers\PenelitianController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TugasPublikasiController;
use App\Http\Controllers\KeteranganPublikController;
use App\Models\Penelitian;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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


Route::middleware(['auth', 'role:ketua'])->group(function () {
    Route::get('ajuan', [KetuaController::class, 'ajuan'])->name('ketua.ajuan');
    Route::get('ttd', [KetuaController::class, 'ttd'])->name('ketua.ttd');
    Route::post('/ketua/upload-ttd', [KetuaController::class, 'uploadTtd'])->name('ketua.uploadTtd');
    Route::post('/ketua/tandatangani/{jenis}/{id}', [KetuaController::class, 'tandatanganiSurat'])->name('ketua.tandatangani');
});

//middleware auth dossen
Route::middleware(['auth', 'role:dosen'])->group(function () {

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
Route::middleware(['auth', 'role:admin'])->group(function () {
    //akun pengguna
    Route::get('akunPengguna', [AdminController::class, 'akunPenggunaView'])->name('admin.akunPengguna');
    Route::get('akunPengguna/create', [AdminController::class, 'akunPenggunaCreate'])->name('admin.akunPenggunaCreate');
    Route::post('akunPengguna', [AdminController::class, 'akunPenggunaStore'])->name('admin.akunPenggunaStore');
    Route::get('akunPengguna/{akunPengguna}/edit', [AdminController::class, 'akunPenggunaEdit'])->name('admin.akunPenggunaEdit');
    Route::put('akunPengguna/{akunPengguna}', [AdminController::class, 'akunPenggunaUpdate'])->name('admin.akunPenggunaUpdate');
    Route::delete('akunPengguna/{akunPengguna}', [AdminController::class, 'akunPenggunaDestroy'])->name('admin.akunPenggunaDestroy');
    //hki
    Route::get('hkiView', [HkiController::class, 'verifikaiHkiView'])->name('admin.hkiView');
    Route::get('hkiEdit/{hki}/edit', [HkiController::class, 'verifikaiHkiEdit'])->name('admin.hkiEdit');
    Route::put('hkiUpdate/{hki}', [HkiController::class, 'verifikaiHkiUpdate'])->name('admin.hkiUpdate');
    Route::get('hkiShow/{hkiShow}', [HkiController::class, 'show'])->name('admin.hkiShow');
    Route::get('hkiShow/{hkiShow}/download', [HkiController::class, 'downloadWord'])->name('admin.hkiDownload');
    //pkm
    Route::get('pkmView', [PkmController::class, 'verifikasiPkmView'])->name('admin.pkmView');
    Route::get('pkmEdit/{pkm}/edit', [PkmController::class, 'verifikasiPkmEdit'])->name('admin.pkmEdit');
    Route::put('pkmUpdate/{pkm}', [PkmController::class, 'verifikasiPkmUpdate'])->name('admin.pkmUpdate');
    Route::get('pkmShow/{pkmShow}', [PkmController::class, 'show'])->name('admin.pkmShow');
    Route::get('pkmShow/{pkmShow}/download', [PkmController::class, 'downloadWord'])->name('admin.pkmDownload');
    //penelitian
    Route::get('penelitianView', [Penelitian::class, 'verifikasiPenelitianView'])->name('admin.penelitianView');
    Route::get('penelitianEdit/{penelitian}/edit', [Penelitian::class, 'verifikasiPenelitianEdit'])->name('admin.penelitianEdit');
    Route::put('penelitianUpdate/{penelitian}', [Penelitian::class, 'verifikasiPenelitianUpdate'])->name('admin.penelitianUpdate');
    Route::get('penelitianShow/{penelitianShow}', [PenelitianController::class, 'show'])->name('admin.penelitianShow');
    Route::get('penelitianShow/{penelitianShow}/download', [PenelitianController::class, 'downloadWord'])->name('admin.penelitianDownload');
    //ketpub
    Route::get('ketpubView', [KeteranganPublikController::class, 'verifikasiKetpubView'])->name('admin.ketpubView');
    Route::get('ketpubEdit/{ketpub}/edit', [KeteranganPublikController::class, 'verifikasiKetpubEdit'])->name('admin.ketpubEdit');
    Route::put('ketpubUpdate/{ketpub}', [KeteranganPublikController::class, 'verifikasiKetpubUpdate'])->name('admin.ketpubUpdate');
    Route::get('ketpubShow/{ketpubShow}', [KeteranganPublikController::class, 'show'])->name('admin.ketpubShow');
    Route::get('ketpubShow/{ketpubShow}/download', [KeteranganPublikController::class, 'downloadWord'])->name('admin.ketpubDownload');
    //tugaspub
    Route::get('tugaspubView', [TugasPublikasiController::class, 'verifikasiTugaspubView'])->name('admin.tugaspubView');
    Route::get('tugaspubEdit/{tugaspub}/edit', [TugasPublikasiController::class, 'verifikasiTugaspubEdit'])->name('admin.tugaspubEdit');
    Route::put('tugaspubUpdate/{tugaspub}', [TugasPublikasiController::class, 'verifikasiTugaspubUpdate'])->name('admin.tugaspubUpdate');
    Route::get('tugaspubShow/{tugaspubShow}', [TugasPublikasiController::class, 'show'])->name('admin.tugaspubShow');
    Route::get('tugaspubShow/{tugaspubShow}/download', [TugasPublikasiController::class, 'downloadWord'])->name('admin.tugaspubDownload');
    //Prodi
    Route::get('prodi', [ProdiController::class, 'index'])->name('prodi.index');
    Route::get('prodi/create', [ProdiController::class, 'create'])->name('prodi.create');
    Route::post('prodi', [ProdiController::class, 'store'])->name('prodi.store');
    Route::get('prodi/{prodi}/edit', [ProdiController::class, 'edit'])->name('prodi.edit');
    Route::put('prodi/{prodi}', [ProdiController::class, 'update'])->name('prodi.update');
    Route::delete('prodi/{prodi}', [ProdiController::class, 'destroy'])->name('prodi.destroy');
    //laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    //kode surat
    Route::resource('kode_surat', KodeSuratController::class);
});

// Riwayat Dosen
Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::get('/dosen/riwayat-surat', [RiwayatController::class, 'indexDosen'])->name('dosen.riwayat');
});

// Riwayat Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/riwayat-surat', [RiwayatController::class, 'indexAdmin'])->name('admin.riwayat');
});

// Riwayat Ketua
Route::middleware(['auth', 'role:ketua'])->group(function () {
    Route::get('/ketua/riwayat-surat', [RiwayatController::class, 'indexKetua'])->name('ketua.riwayat');
});


Route::delete('/riwayat/{id}', [RiwayatController::class, 'destroy'])->name('riwayat.destroy');
Route::delete('/riwayat', [RiwayatController::class, 'destroyAll'])->name('riwayat.destroyAll');


// Verifikasi Email Registration
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
