<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Penulis;
use App\Models\Tugaspub;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasPublikasiController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $title = 'Surat Tugas Publikasi';
        $tugaspub = Tugaspub::where('user_id', $user->id)->with('penulis')->get();
        return view('user.dosen.tugaspub.index', compact('tugaspub', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Surat Tugas Publikasi';
        return view('user.dosen.tugaspub.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'namaPublikasi' => 'required',
            'penerbit' => 'required',
            'alamat' => 'required',
            'link' => 'required',
            'volume' => 'required',
            'nomor' => 'required',
            'bulan' => 'required',
            'issn' => 'required',
            'judul' => 'required',
            'tanggal' => 'required',
        ]);

        $user = Auth::user();

        $tugaspub = Tugaspub::create([
            'namaPublikasi' => $request->namaPublikasi,
            'penerbit' => $request->penerbit,
            'alamat' => $request->alamat,
            'link' => $request->link,
            'volume' => $request->volume,
            'nomor' => $request->nomor,
            'bulan' => $request->bulan,
            'akreditas' => $request->akreditas,
            'issn' => $request->issn,
            'judul' => $request->judul,
            'tanggal' => $request->tanggal,
            'statusSurat'        => 'pending',
            'user_id'       => $user->id // Otomatis mengisi user_id dengan ID user yang sedang login

        ]);

        // Kirim notifikasi ke admin
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Pengajuan Baru',
                'message' => 'Dosen telah membuat pengajuan surat Tugas Publikasi.',
                'status' => 'unread',
            ]);
        }
        // Menambahkan Penulis (Validasi untuk mengabaikan input kosong)
        foreach ($request->penulis as $penulis) {
            if (!empty($penulis['nama']) && !empty($penulis['prodi'])) {
                Penulis::create([
                    'tugaspub_id' => $tugaspub->id,
                    'nama' => $penulis['nama'],
                    'jurusan_prodi' => $penulis['prodi'],
                ]);
            }
        }


        return redirect()->route('tugaspub.index')->with(['success' => 'Data berhasil Disimpan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $tugaspub = Tugaspub::find($id);
        $tugaspub->load('penulis');
        $title = 'Tampilan Surat Tugas Publikasi';
        if ($user->role === 'admin') {
            return view('user.admin.tugaspub.show', compact('tugaspub', 'title'));
        } elseif ($user->role === 'dosen') {
            return view('user.dosen.tugaspub.show', compact('tugaspub', 'title'));
        }
        abort(403, 'Unautorized');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tugaspub = Tugaspub::findOrFail($id);
        $tugaspub->load('penulis');
        $title = 'Edit Surat Tugas Publikasi';
        return view('user.dosen.tugaspub.edit', compact('tugaspub', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'namaPublikasi' => 'required|string',
            'penerbit' => 'required|string',
            'alamat' => 'required|string',
            'link' => 'required|url',
            'volume' => 'required|string',
            'nomor' => 'required|string',
            'bulan' => 'required|string',
            'issn' => 'required|string',
            'judul' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        // Ambil data tugaspub berdasarkan ID
        $tugaspub = Tugaspub::findOrFail($id);

        // Update data utama
        $tugaspub->update([
            'namaPublikasi' => $request->namaPublikasi,
            'penerbit' => $request->penerbit,
            'alamat' => $request->alamat,
            'link' => $request->link,
            'volume' => $request->volume,
            'nomor' => $request->nomor,
            'bulan' => $request->bulan,
            'issn' => $request->issn,
            'judul' => $request->judul,
            'tanggal' => $request->tanggal,
        ]);

        // Perbarui penulis jika ada
        if ($request->has('penulis')) {
            // Hapus data penulis lama
            $tugaspub->penulis()->delete();

            // Simpan data penulis baru
            foreach ($request->penulis as $penulis) {
                if (!empty($penulis['nama']) && !empty($penulis['prodi'])) {
                    $tugaspub->penulis()->create([
                        'nama' => $penulis['nama'],
                        'jurusan_prodi' => $penulis['prodi'],
                    ]);
                }
            }
        }

        return redirect()->route('tugaspub.index')->with('success', 'Data Berhasil di Update');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tugaspub = Tugaspub::findOrFail($id);
        $tugaspub->delete();

        return redirect()->route('tugaspub.index')->with('success', 'Data Berhasil di Hapus');
    }

    public function downloadWord(string $id)
    {
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor('suratTugasPub.docx');

        $tugaspub = Tugaspub::findOrFail($id);
        $formattedDate = Carbon::parse($tugaspub->tanggal)->translatedFormat('j F Y');
        $year = Carbon::parse($tugaspub->tanggal)->translatedFormat('Y');

        $phpWord->setValues([
            'nomorSurat' => $tugaspub->nomorSurat?: '-',
            'namaPublikasi' => $tugaspub->namaPublikasi,
            'penerbit' => $tugaspub->penerbit,
            'alamat' => $tugaspub->alamat,
            'link' => $tugaspub->link,
            'volume' => $tugaspub->volume,
            'nomor' => $tugaspub->nomor,
            'bulan' => $tugaspub->bulan,
            'akreditas' => $tugaspub->akreditas,
            'issn' => $tugaspub->issn,
            'judul' => $tugaspub->judul,
            'tanggal' => $formattedDate,
            'tahun' => $year,

        ]);

        // Hitung jumlah penulis
        $penulis = $tugaspub->penulis;

        // Clone row untuk data penulis
        $phpWord->cloneRow('no', $penulis->count());

        foreach ($penulis as $index => $penulis) {
            $row = $index + 1;

            $phpWord->setValue("no#{$row}", $row);
            $phpWord->setValue("namaPenulis#{$row}", $penulis->nama ?: '-');
            $phpWord->setValue("jurusanProdi#{$row}", $penulis->jurusan_prodi ?: '-');
        }

        $fileName = 'Surat_Tugas_Publikasi_' . $tugaspub->namaPenulis1 . '.docx';
        $phpWord->saveAs($fileName);

        // Return the file for download
        return response()->download($fileName)->deleteFileAfterSend(true);
    }
}
