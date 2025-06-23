<?php

namespace App\Http\Controllers;

use App\Models\Hki;
use App\Models\Pkm;
use App\Models\User;
use App\Models\Ketua;
use App\Models\Ketpub;
use GuzzleHttp\Client;
use App\Models\Riwayat;
use App\Models\Tugaspub;
use App\Models\Penelitian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class KetuaController extends Controller
{
    public function ajuan()
    {
        // Ambil semua surat yang statusnya disetujui, dan ambil relasinya
        $surats = collect();
        $title = 'Ajuan Surat';

        // Tambahkan HKI
        $hkis = Hki::with('ajuanSurat.user')
            ->whereHas('ajuanSurat', fn($q) => $q->where('status', 'disetujui'))
            ->get()
            ->map(function ($item) {
                return (object)[
                    'jenis' => 'HKI',
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'nomor_surat' => $item->nomorSurat,
                    'dosen' => $item->ajuanSurat->user->name,
                    'created_at' => $item->created_at,
                    'status' => $item->ajuanSurat->status,
                ];
            });

        // Tambahkan Penelitian
        $penelitians = Penelitian::with('ajuanSurat.user')
            ->whereHas('ajuanSurat', fn($q) => $q->where('status', 'disetujui'))
            ->get()
            ->map(function ($item) {
                return (object)[
                    'jenis' => 'Penelitian',
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'nomor_surat' => $item->nomorSurat,
                    'dosen' => $item->ajuanSurat->user->name,
                    'created_at' => $item->created_at,
                    'status' => $item->ajuanSurat->status,
                ];
            });

        // Tambahkan PKM
        $pkms = Pkm::with('ajuanSurat.user')
            ->whereHas('ajuanSurat', fn($q) => $q->where('status', 'disetujui'))
            ->get()
            ->map(function ($item) {
                return (object)[
                    'jenis' => 'PKM',
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'nomor_surat' => $item->nomorSurat,
                    'dosen' => $item->ajuanSurat->user->name,
                    'created_at' => $item->created_at,
                    'status' => $item->ajuanSurat->status,
                ];
            });

        // Tambahkan ketpub
        $ketpubs = Ketpub::with('ajuanSurat.user')
            ->whereHas('ajuanSurat', fn($q) => $q->where('status', 'disetujui'))
            ->get()
            ->map(function ($item) {
                return (object)[
                    'jenis' => 'Ketpub',
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'nomor_surat' => $item->nomorSurat,
                    'dosen' => $item->ajuanSurat->user->name,
                    'created_at' => $item->created_at,
                    'status' => $item->ajuanSurat->status,
                ];
            });

        // Tambahkan tugaspub
        $tugaspubs = Tugaspub::with('ajuanSurat.user')
            ->whereHas('ajuanSurat', fn($q) => $q->where('status', 'disetujui'))
            ->get()
            ->map(function ($item) {
                return (object)[
                    'jenis' => 'Tugaspub',
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'nomor_surat' => $item->nomorSurat,
                    'dosen' => $item->ajuanSurat->user->name,
                    'created_at' => $item->created_at,
                    'status' => $item->ajuanSurat->status,
                ];
            });

        // Gabungkan semua ke dalam satu collection
        $surats = $surats->concat($hkis)->concat($penelitians)->concat($pkms)->concat($tugaspubs)->concat($ketpubs);

        // Urutkan berdasarkan tanggal
        $surats = $surats->sortByDesc('created_at');

        return view('user.ketua.ajuansurat.index', compact('surats', 'title'));
    }

    public function ttd()
    {

        $title = 'Tandan Tangan';
        return view('user.ketua.ttd.index', compact('title'));
    }


    public function uploadTtd(Request $request)
    {
        $request->validate([
            'tanda_tangan' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $user = Auth::user();
        $ketua = $user->ketua;

        // Hapus file lama kalau ada
        if ($ketua->tanda_tangan && Storage::disk('public')->exists($ketua->tanda_tangan)) {
            Storage::disk('public')->delete($ketua->tanda_tangan);
        }

        // Simpan file baru
        $path = $request->file('tanda_tangan')->store('tanda_tangan_ketua', 'public');

        // Update data ketua
        $ketua->tanda_tangan = $path;
        $ketua->save();

        return back()->with('success', 'Tanda tangan berhasil diunggah.');
    }

    public function tandatanganiSurat($jenis, $id)
    {
        $admins = User::where('role', 'admin')->get();
        $user = Auth::user();
        $ttd = $user->ketua->tanda_tangan ?? null;

        if (!$ttd) {
            return back()->with('error', 'Tanda tangan belum diunggah.');
        }

        switch (strtolower($jenis)) {
            case 'hki':
                $surat = Hki::findOrFail($id);
                break;
            case 'penelitian':
                $surat = Penelitian::findOrFail($id);
                break;
            case 'pkm':
                $surat = Pkm::findOrFail($id);
                break;
            case 'ketpub':
                $surat = Ketpub::findOrFail($id);
                break;
            case 'tugaspub':
                $surat = Tugaspub::findOrFail($id);
                break;
            default:
                return back()->with('error', 'Jenis surat tidak dikenal.');
        }

        $message = View::make('user.notif.whatsapp', [
            'judul_notifikasi' => 'Surat Tugas Telah Ditandatangani',
            'data' => [
                'Jenis Surat' => strtoupper($jenis),
                'Judul' => $surat->judul ?? '-',
                'Pemohon' => $surat->user->name ?? '-',
                'Tanggal' => now()->format('d M Y'),
                'Status' => 'Sudah Ditandatangani',
            ],
            'footer' => 'Silakan cek sistem untuk mencetak atau mengarsipkan surat.',
        ])->render();

        $client = new Client();
        foreach ($admins as $admin) {
            if ($admin->nomor_telepon) {
                $client->post('https://api.fonnte.com/send', [
                    'headers' => [
                        'Authorization' => env('FONNTE_API_KEY'),
                        'Accept' => 'application/json',
                    ],
                    'form_params' => [
                        'target' => $admin->nomor_telepon,
                        'message' => $message,
                    ],
                ]);
            }
        }

        // Simpan tanda tangan di surat
        $surat->tanda_tangan = $ttd;
        $surat->save();
        // Ubah status pada ajuan_surat menjadi "sudah ditandatangani"
        $ajuan = $surat->ajuanSurat;
        $ajuan->status = 'sudah ditandatangani';
        $ajuan->save();
        // Buat log riwayat ketua
        Riwayat::create([
            'user_id' => Auth::id(), // ketua yang login
            'ajuan_surat_id' => $ajuan->id,
            'aksi' => 'Menandatangani Surat ' . strtoupper($jenis),
            'catatan' => 'Judul: ' . ($surat->judul ?? '-'),
            'waktu_perubahan' => now(),
        ]);
        return back()->with('success', 'Surat berhasil ditandatangani.');
    }
}
