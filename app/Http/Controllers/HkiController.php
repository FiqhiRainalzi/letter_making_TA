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

        // Mengambil data HKI yang terkait dengan user, diurutkan berdasarkan terbaru, dan dipaginasi
        $hki = Hki::where('user_id', $user->id)
            ->with('inventors') // Memuat relasi inventors
            ->get();
        // ->paginate(5); // Paginasi 5 per halaman
        $title = 'Surat HKI';
        $today = date('Y-m-d');
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
            'namaPemHki'    => 'required',
            'alamatPemHki'  => 'required|min:5',
            'judulInvensi'  => 'required|min:5',
        ]);

        // Simpan data HKI
        $hki = Hki::create([
            'user_id'       => Auth::id(),
            'namaPemHki'    => $request->namaPemHki,
            'alamatPemHki'  => $request->alamatPemHki,
            'judulInvensi'  => $request->judulInvensi,
            'tanggalPemHki' => $request->tanggalPemHki,
            'statusSurat'   => 'pending',
        ]);

        // Simpan data inventors
        foreach ($request->inventors as $inventorData) {
            $hki->inventors()->create($inventorData);
        }

        return redirect()->route('hki.index')->with(['success' => 'Data berhasil disimpan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Muat relasi 'user' dan 'inventors'
        $hki = Hki::findOrFail($id);
        $hki->load('inventors');
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
        $hki->load('inventors');
        $title = 'Edit Surat HKI';
        return view('user.dosen.hki.edit', compact('hki', 'title'));
    }

    public function update(Request $request, string $id)
    {
        // Validasi input hanya untuk data utama
        $request->validate([
            'namaPemHki'    => 'required',
            'alamatPemHki'  => 'required|min:5',
            'judulInvensi'  => 'required|min:5',
        ]);

        // Cari data HKI berdasarkan ID
        $hki = Hki::findOrFail($id);

        // Update data utama HKI
        $hki->update([
            'namaPemHki'   => $request->namaPemHki,
            'alamatPemHki' => $request->alamatPemHki,
            'judulInvensi' => $request->judulInvensi,
            'tanggalPemHki' => $request->tanggalPemHki,
        ]);

        // Hapus semua inventor yang lama (hanya jika kita tidak ingin menduplikasi)
        $hki->inventors()->delete();

        // Update atau tambah inventor tanpa validasi
        if (!empty($request->inventors)) {
            foreach ($request->inventors as $inventorData) {
                // Cek jika namaInventor atau bidangStudi kosong
                if (!empty($inventorData['nama']) && !empty($inventorData['bidang_studi'])) {
                    if (isset($inventorData['id'])) {
                        // Update data inventor yang ada
                        $inventor = Inventor::find($inventorData['id']);
                        if ($inventor) {
                            $inventor->update($inventorData);
                        }
                    } else {
                        // Tambah inventor baru jika nama dan bidang_studi tidak kosong
                        $hki->inventors()->create($inventorData);
                    }
                }
            }
        }

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
