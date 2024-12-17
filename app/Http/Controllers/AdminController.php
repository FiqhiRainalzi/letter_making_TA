<?php

namespace App\Http\Controllers;

use App\Models\Hki;
use App\Models\Ketpub;
use App\Models\Notification;
use App\Models\Penelitian;
use App\Models\Pkm;
use App\Models\Inventor;
use App\Models\Tugaspub;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminController extends BaseController
{
    public function home()
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Jika user adalah admin, tampilkan semua data dalam 24 jam terakhir
            $dataHki = Hki::where('created_at', '>=', Carbon::now()->subDay())->with('inventors')->get();
            $dataKetPub = Ketpub::where('created_at', '>=', Carbon::now()->subDay())->get();
            $dataPenelitian = Penelitian::where('created_at', '>=', Carbon::now()->subDay())->get();
            $dataPkm = Pkm::where('created_at', '>=', Carbon::now()->subDay())->get();
            $dataTugaspub = Tugaspub::where('created_at', '>=', Carbon::now()->subDay())->get();

            // Menghitung total jumlah semua data
            $countHki = Hki::count();
            $countKetPub = Ketpub::count();
            $countPenelitian = Penelitian::count();
            $countPkm = Pkm::count();
            $countTugaspub = Tugaspub::count();
        } else {
            // Jika bukan admin, tampilkan data berdasarkan user_id dan dalam 24 jam terakhir
            $dataHki = Hki::where('user_id', $user->id)
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->get();
            $dataKetPub = Ketpub::where('user_id', $user->id)
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->get();
            $dataPenelitian = Penelitian::where('user_id', $user->id)
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->get();
            $dataPkm = Pkm::where('user_id', $user->id)
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->get();
            $dataTugaspub = Tugaspub::where('user_id', $user->id)
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->get();

            // Menghitung jumlah data berdasarkan user_id
            $countHki = Hki::where('user_id', $user->id)->count();
            $countKetPub = Ketpub::where('user_id', $user->id)->count();
            $countPenelitian = Penelitian::where('user_id', $user->id)->count();
            $countPkm = Pkm::where('user_id', $user->id)->count();
            $countTugaspub = Tugaspub::where('user_id', $user->id)->count();
        }

        //$title
        $title = 'Dashboard';

        // Mengirim data dan hitungan ke view
        return view('user.admin.dashboard', compact(
            'countHki',
            'countKetPub',
            'countPenelitian',
            'countPkm',
            'countTugaspub',
            'dataHki',
            'dataKetPub',
            'dataPenelitian',
            'dataPkm',
            'dataTugaspub',
            'title'
        ));
    }


    //hki controller
    public function hkiView()
    {
        $hki = Hki::get();
        $today = date('Y-m-d');
        $title = 'Surat HKI';
        return view('user.admin.hki.index', compact('hki', 'today', 'title'));
    }

    public function hkiEdit($id)
    {
        $hki = Hki::findOrFail($id);
        $title = 'Edit Surat HKI';
        return view('user.admin.hki.edit', compact('hki', 'title'));
    }

    public function hkiUpdate(Request $request, string $id)
    {
        $hki = Hki::findOrFail($id);
        $request->validate([
            'nomorSurat' => 'unique:hki,nomorSurat,' . $hki->id,
            'statusSurat' => 'required|in:pending,approved,rejected', // Validasi status
        ]);
        // Kirim notifikasi ke dosen
        $dosenId = $request->input('dosen_id'); // ID dosen target
        Notification::create([
            'user_id' => $dosenId,
            'title' => 'Data HKI Baru oleh Admin',
            'message' => 'Admin telah membuat data HKI baru untuk Anda.',
            'status' => 'unread',
        ]);

        $hki->update($request->all());
        return redirect()->route('admin.hkiView')->with('success', 'Data berhasil diupdate');
    }
    //end hki controller

    //pkm controller
    public function pkmView()
    {
        $pkm = Pkm::get();
        $pkm->load('anggota', 'tenagaPembantu');
        $today = date('Y-m-d');
        $title = 'Surat PKM';
        return view('user.admin.pkm.index', compact('pkm', 'today', 'title'));
    }

    public function pkmEdit($id)
    {
        $pkm = Pkm::findOrFail($id);
        $title = 'Edit Surat PKM';
        return view('user.admin.pkm.edit', compact('pkm', 'title'));
    }

    public function pkmUpdate(Request $request, string $id)
    {
        $pkm = Pkm::findOrFail($id);

        $request->validate([
            'nomorSurat'    => 'unique:pkm,nomorSurat,' . $pkm->id,
            'statusSurat' => 'required|in:pending,approved,rejected', // Validasi status
        ]);
        // Kirim notifikasi ke dosen
        $dosenId = $request->input('dosen_id'); // ID dosen target
        Notification::create([
            'user_id' => $dosenId,
            'title' => 'Data PKM Baru oleh Admin',
            'message' => 'Admin telah membuat data PKM baru untuk Anda.',
            'status' => 'unread',
            'url' => route('pkm.index')
        ]);
        $pkm->update($request->all());
        return redirect()->route('admin.pkmView')->with('success', 'Data berhasil diupdate');
    }
    //end pkm controller

    //penelitian controller
    public function penelitianView()
    {
        $penelitian = Penelitian::get();
        $today = date('Y-m-d');
        $title = 'Surat Penelitian';
        return view('user.admin.penelitian.index', compact('penelitian', 'today', 'title'));
    }

    public function penelitianEdit($id)
    {
        $penelitian = Penelitian::findOrFail($id);
        $title = 'Edit Surat Penelitian';
        return view('user.admin.penelitian.edit', compact('penelitian', 'title'));
    }

    public function penelitianUpdate(Request $request, string $id)
    {
        $penelitian = Penelitian::findOrFail($id);

        $request->validate([
            'nomorSurat'    => 'unique:penelitian,nomorSurat,' . $penelitian->id,
            'statusSurat' => 'required|in:pending,approved,rejected', // Validasi status
        ]);
        // Kirim notifikasi ke dosen
        $dosenId = $request->input('dosen_id'); // ID dosen target
        Notification::create([
            'user_id' => $dosenId,
            'title' => 'Data Penelitian Baru oleh Admin',
            'message' => 'Admin telah membuat data Penelitian baru untuk Anda.',
            'status' => 'unread',
        ]);
        $penelitian->update($request->all());
        return redirect()->route('admin.penelitianView')->with('success', 'Data berhasil diupdate');
    }
    //end penelitian controller

    //ketpub controller
    public function ketpubView()
    {
        $ketpub = Ketpub::with('penulis')->get();
        $today = date('Y-m-d');
        $title = 'Surat Keterangan Publikasi ';
        return view('user.admin.ketpub.index', compact('ketpub', 'today', 'title'));
    }

    public function ketpubEdit($id)
    {
        $ketpub = Ketpub::findOrFail($id);
        $title = 'Edit Surat Keterangan Publikasi';
        return view('user.admin.ketpub.edit', compact('ketpub', 'title'));
    }

    public function ketpubUpdate(Request $request, string $id)
    {
        $ketpub = Ketpub::findOrFail($id);

        $request->validate([
            'nomorSurat'    => 'unique:ketpub,nomorSurat,' . $ketpub->id,
            'statusSurat' => 'required|in:pending,approved,rejected', // Validasi status
        ]);
        // Kirim notifikasi ke dosen
        $dosenId = $request->input('dosen_id'); // ID dosen target
        Notification::create([
            'user_id' => $dosenId,
            'title' => 'Data Keterangan Publik Baru oleh Admin',
            'message' => 'Admin telah membuat data Keterangan Publik baru untuk Anda.',
            'status' => 'unread',
        ]);
        $ketpub->update($request->all());
        return redirect()->route('admin.ketpubView')->with('success', 'Data berhasil diupdate');
    }
    //end ketpub controller

    //tugaspub controller
    public function tugaspubView()
    {
        $tugaspub = Tugaspub::get();
        $today = date('Y-m-d');
        $title = 'Surat Tugas Publikasi';
        return view('user.admin.tugaspub.index', compact('tugaspub', 'today', 'title'));
    }

    public function tugaspubEdit($id)
    {
        $tugaspub = tugaspub::findOrFail($id);
        $title = 'Edit Surat Tugas Publikasi';
        return view('user.admin.tugaspub.edit', compact('tugaspub', 'title'));
    }

    public function tugaspubUpdate(Request $request, string $id)
    {
        $tugaspub = Tugaspub::findOrFail($id);

        $request->validate([
            'nomorSurat'    => 'unique:tugaspub,nomorSurat,' . $tugaspub->id,
            'statusSurat' => 'required|in:pending,approved,rejected', // Validasi status
        ]);
        // Kirim notifikasi ke dosen
        $dosenId = $request->input('dosen_id'); // ID dosen target
        Notification::create([
            'user_id' => $dosenId,
            'title' => 'Data Tugas Publik Baru oleh Admin',
            'message' => 'Admin telah membuat data Tugas Publik baru untuk Anda.',
            'status' => 'unread',
        ]);
        $tugaspub->update($request->all());
        return redirect()->route('admin.tugaspubView')->with('success', 'Data berhasil diupdate');
    }
    //end tugaspub controller

    //akun pengguna controller
    public function akunPenggunaView()
    {
        $akunPengguna = User::get();
        $title = 'Akun Pengguna ';
        return view('user.admin.akunPengguna.index', compact('akunPengguna', 'title'));
    }

    public function akunPenggunaCreate()
    {
        $title = 'Tambah Akun Pengguna';
        return view('user.admin.akunPengguna.create', compact('title'));
    }

    public function akunPenggunaStore(Request $request)
    {

        $request->validate([
            'name'    => 'required',
            'email' => 'required|email|min:5|unique:users,email',
            'password'  => 'required|min:8',
            'role' => 'required|min:5'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role
        ]);

        return redirect()->route('admin.akunPengguna')->with(['success' => 'Data berhasil Disimpan']);
    }

    public function akunPenggunaEdit(string $id)
    {
        $user = User::findOrFail($id);
        $title = 'Edit Akun Pengguna';
        return view('user.admin.akunPengguna.edit', compact('user', 'title'));
    }

    public function akunPenggunaUpdate(Request $request, string $id)
    {

        $user = User::findOrFail($id);

        $request->validate([
            'name'    => 'required',
            'email' => 'required|email|min:5|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'role' => 'required|min:5'
        ]);

        // Ambil semua input kecuali password
        $data = $request->only('name', 'email', 'role');

        // Jika password diisi, hash password baru, jika tidak gunakan password lama
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        } else {
            $data['password'] = $user->password; // Tetap gunakan password lama
        }

        // Update data ke database
        $user->update($data);

        return redirect()->route('admin.akunPengguna')->with(['success' => 'Data berhasil Disimpan']);
    }

    public function akunPenggunaDestroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.akunPengguna')->with(['success' => 'Data berhasil Disimpan']);
    }
    //end controller akun pengguna
}
