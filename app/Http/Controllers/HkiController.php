<?php

namespace App\Http\Controllers;

use App\Models\Hki;
use App\Models\Inventor;
use App\Models\Notification;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HkiController extends BaseController
{
    public function index()
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Mengambil data HKI yang terkait dengan user
        $hki = Hki::where('user_id', $user->id)->get();
        $title = 'Surat HKI';
        $today = date('Y-m-d');
        //menghitung hari proses pengajuan
        $hki->transform(function ($item) {
            if (in_array($item->statusSurat, ['approved', 'ready_to_pickup', 'picked_up', 'rejected'])) {
                $item->lama_proses = Carbon::parse($item->created_at)->diffInDays(Carbon::parse($item->updated_at));
            } else {
                $item->lama_proses = Carbon::parse($item->created_at)->diffInDays(Carbon::now());
            }
            return $item;
        });
        return view('user.dosen.hki.index', compact('today', 'hki', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Surat HKI';
        return view('user.dosen.hki.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'namaPemegang'    => 'required',
            'alamat'  => 'required|min:5',
            'judul'  => 'required|min:5',
            'tanggal' => 'required',
        ]);

        // Simpan data HKI
        Hki::create([
            'user_id'       => Auth::id(),
            'namaPemegang'    => $request->namaPemegang,
            'alamat'  => $request->alamat,
            'judul'  => $request->judul,
            // Inventor 1-10
            'inventor1'  => $request->inventor1 ?? null,
            'inventor2'  => $request->inventor2 ?? null,
            'inventor3'  => $request->inventor3 ?? null,
            'inventor4'  => $request->inventor4 ?? null,
            'inventor5'  => $request->inventor5 ?? null,
            'inventor6'  => $request->inventor6 ?? null,
            'inventor7'  => $request->inventor7 ?? null,
            'inventor8'  => $request->inventor8 ?? null,
            'inventor9'  => $request->inventor9 ?? null,
            'inventor10' => $request->inventor10 ?? null,
            // Bidang Studi 1-10
            'bidangStudi1'  => $request->bidangStudi1 ?? null,
            'bidangStudi2'  => $request->bidangStudi2 ?? null,
            'bidangStudi3'  => $request->bidangStudi3 ?? null,
            'bidangStudi4'  => $request->bidangStudi4 ?? null,
            'bidangStudi5'  => $request->bidangStudi5 ?? null,
            'bidangStudi6'  => $request->bidangStudi6 ?? null,
            'bidangStudi7'  => $request->bidangStudi7 ?? null,
            'bidangStudi8'  => $request->bidangStudi8 ?? null,
            'bidangStudi9'  => $request->bidangStudi9 ?? null,
            'bidangStudi10' => $request->bidangStudi10 ?? null,
            'tanggal'   => $request->tanggal,
            'statusSurat'   => 'draft',
        ]);

        return redirect()->route('hki.index')->with(['success' => 'Data berhasil disimpan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Muat relasi 'user' dan 'inventors'
        $hki = Hki::findOrFail($id);
        $user = Auth::user();
        $title = 'Tampilan Surat HKI';

        if ($user->role === 'admin') {
            return view('user.admin.hki.show', compact('hki', 'title'));
        } elseif ($user->role === 'dosen') {
            return view('user.dosen.hki.show', compact('hki', 'title'));
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $hki = Hki::findOrFail($id);
        $title = 'Edit Surat HKI';
        return view('user.dosen.hki.edit', compact('hki', 'title'));
    }

    public function update(Request $request, string $id)
    {
        // Validasi input hanya untuk data utama
        $request->validate([
            'namaPemegang'    => 'required',
            'alamat'  => 'required|min:5',
            'judul'  => 'required|min:5',
        ]);

        // Cari data HKI berdasarkan ID
        $hki = Hki::findOrFail($id);

        // Update data utama HKI
        $hki->update([
            'namaPemegang'   => $request->namaPemegang,
            'alamat' => $request->alamat,
            'judul' => $request->judul,
            // Inventor 1-10
            'inventor1'  => $request->inventor1 ?? null,
            'inventor2'  => $request->inventor2 ?? null,
            'inventor3'  => $request->inventor3 ?? null,
            'inventor4'  => $request->inventor4 ?? null,
            'inventor5'  => $request->inventor5 ?? null,
            'inventor6'  => $request->inventor6 ?? null,
            'inventor7'  => $request->inventor7 ?? null,
            'inventor8'  => $request->inventor8 ?? null,
            'inventor9'  => $request->inventor9 ?? null,
            'inventor10' => $request->inventor10 ?? null,
            // Bidang Studi 1-10
            'bidangStudi1'  => $request->bidangStudi1 ?? null,
            'bidangStudi2'  => $request->bidangStudi2 ?? null,
            'bidangStudi3'  => $request->bidangStudi3 ?? null,
            'bidangStudi4'  => $request->bidangStudi4 ?? null,
            'bidangStudi5'  => $request->bidangStudi5 ?? null,
            'bidangStudi6'  => $request->bidangStudi6 ?? null,
            'bidangStudi7'  => $request->bidangStudi7 ?? null,
            'bidangStudi8'  => $request->bidangStudi8 ?? null,
            'bidangStudi9'  => $request->bidangStudi9 ?? null,
            'bidangStudi10' => $request->bidangStudi10 ?? null,
            'tanggal' => $request->tanggal,
        ]);


        return redirect()->route('hki.index')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Temukan dan hapus data
        $hki = Hki::findOrFail($id);
        $hki->delete();

        // Arahkan kembali dengan pesan sukses
        return redirect()->route('hki.index')->with('success', 'Data berhasil dihapus.');
    }


    public function viewPdf($id) {}

    public function cetak(Hki $hki)
    {
        $pdf = Pdf::loadview('user.dosen.hki.cetak', [
            'hki' => $hki,
        ])->setPaper('a4', 'potrait');
        return $pdf->stream('HKI_' . '' . $hki->id . '.pdf');
    }

    public function downloadWord($id)
    {
        // Load template Word
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor('suratHki.docx');

        // Ambil data HKI beserta relasi inventors
        $hki = Hki::with('inventors')->findOrFail($id);

        // Format tanggal
        $year = Carbon::parse($hki->tanggalPemHki)->translatedFormat('Y');
        $formattedDate = Carbon::parse($hki->tanggalPemHki)->translatedFormat('j F Y');

        // Set data utama di Word
        $phpWord->setValues([
            'namaPemHki'   => $hki->namaPemHki,
            'nomorSurat'   => $hki->nomorSurat ?: '-',
            'alamatPemHki' => $hki->alamatPemHki ?: '-',
            'judulInvensi' => $hki->judulInvensi ?: '-',
            'tanggalPemHki' => $formattedDate,
            'tahun'         => $year
        ]);

        // Hitung jumlah inventor
        $inventors = $hki->inventors;

        // Clone row untuk data inventor
        $phpWord->cloneRow('no', $inventors->count());

        foreach ($inventors as $index => $inventor) {
            $row = $index + 1;

            $phpWord->setValue("no#{$row}", $row);
            $phpWord->setValue("namaInventor#{$row}", $inventor->nama ?: '-');
            $phpWord->setValue("bidangStudi#{$row}", $inventor->bidang_studi ?: '-');
        }

        // Simpan dan download file
        $fileName = 'Surat_HKI_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $hki->namaPemHki) . '.docx';
        $phpWord->saveAs($fileName);

        return response()->download($fileName)->deleteFileAfterSend(true);
    }
}
