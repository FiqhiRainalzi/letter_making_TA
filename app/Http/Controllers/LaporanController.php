<?php
namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\AjuanSurat;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index()
    {
        $title = 'Laporan';
        return view('user.admin.laporan.index', compact('title'));
    }

    public function cetak(Request $request)
    {
        $request->validate([
            'jenis_surat' => 'nullable|string',
            'status' => 'nullable|string',
            'dari' => 'nullable|date',
            'sampai' => 'nullable|date',
            'catatan' => 'nullable|string|max:500',
        ]);

        $data = AjuanSurat::with('user')
            ->when($request->jenis_surat, fn($q) => $q->where('jenis_surat', $request->jenis_surat))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->dari && $request->sampai, fn($q) =>
            $q->whereBetween('created_at', [$request->dari, $request->sampai]))
            ->get() 
            ->filter(fn($item) => $item->getSurat() && $item->getSurat()->judul !== null);

        // Simpan riwayat laporan
        $laporan = Laporan::create([
            'user_id' => Auth::id(),
            'judul' => 'Laporan Surat ' . now()->format('d-m-Y H:i'),
            'catatan' => $request->catatan,
            'jenis_surat' => $request->jenis_surat,
            'status_filter' => $request->status,
            'tanggal_dari' => $request->dari,
            'tanggal_sampai' => $request->sampai,
        ]);

        $pdf = Pdf::loadView('user.admin.laporan.cetak', [
            'data' => $data,
            'catatan' => $request->catatan,
            'laporan' => $laporan
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-surat.pdf');
    }
}
