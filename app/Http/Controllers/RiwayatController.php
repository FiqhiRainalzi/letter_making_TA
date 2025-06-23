<?php

namespace App\Http\Controllers;

use App\Models\Riwayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function indexDosen()
    {
        $userId = Auth::id();

        $riwayats = Riwayat::where('user_id', $userId)
            ->with('ajuanSurat')
            ->orderByDesc('waktu_perubahan')
            ->get();

        $title = 'Riwayat Aktivitas Saya';

        return view('user.dosen.riwayat', compact('riwayats', 'title'));
    }

    public function indexAdmin()
    {
        $userId = Auth::id();

        $riwayats = Riwayat::where('user_id', $userId)
            ->with('ajuanSurat')
            ->orderByDesc('waktu_perubahan')
            ->get();

        $title = 'Riwayat Aktivitas Saya';

        return view('user.admin.riwayat', compact('riwayats', 'title'));
    }

    public function indexKetua()
    {
        $userId = Auth::id();

        $riwayats = Riwayat::where('user_id', $userId)
            ->with('ajuanSurat')
            ->orderByDesc('waktu_perubahan')
            ->get();

        $title = 'Riwayat Aktivitas Saya';

        return view('user.ketua.riwayat', compact('riwayats', 'title'));
    }


    public function destroy($id)
    {
        $riwayat = Riwayat::findOrFail($id);
        $riwayat->delete();

        return redirect()->back()->with('success', 'Riwayat berhasil dihapus.');
    }

    public function destroyAll()
    {
        $userId = Auth::id();
        Riwayat::where('user_id', $userId)->delete(); // Hapus hanya riwayat miliknya
        return redirect()->back()->with('success', 'Semua riwayat Anda berhasil dihapus.');
    }
}
