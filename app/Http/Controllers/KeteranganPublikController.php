<?php

namespace App\Http\Controllers;

use App\Models\Ketpub;
use App\Models\Notification;
use App\Models\Penulis;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeteranganPublikController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $title = 'Surat Publikasi';
        $ketpub = Ketpub::where('user_id', $user->id)->with('penulis')->get();
        return view('user.dosen.ketpub.index', compact('ketpub', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Surat Publikasi';
        return view('user.dosen.ketpub.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori_publikasi' => 'required',
            'judul'         => 'required',
            'namaPenerbit'  => 'required|min:5',
            'penerbit'      => 'required|min:5',
            'bulan'         => 'required',
            'tahun'         => 'required',
            'issn'          => 'required|min:5',
        ]);

        $user = Auth::user();

        $ketpub = Ketpub::create([
            'kategori_publikasi'    => $request->kategori_publikasi,
            'judul'          => $request->judul,
            'namaPenerbit'   => $request->namaPenerbit,
            'penerbit'       => $request->penerbit,
            'jilid'         => $request->jilid,
            'edisi'          => $request->nomor,
            'bulan'          => $request->bulan,
            'tahun'          => $request->tahun,
            'issn'           => $request->issn,
            'tanggal'        => $request->tanggal,
            'statusSurat'        => 'pending',
            'user_id'       => $user->id // Otomatis mengisi user_id dengan ID user yang sedang login

        ]);

        // Kirim notifikasi ke admin
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Pengajuan Baru',
                'message' => 'Dosen telah membuat pengajuan surat Keterangan Publikasi.',
                'status' => 'unread',
            ]);
        }
        // Menambahkan Penulis (Validasi untuk mengabaikan input kosong)
        foreach ($request->penulis as $penulis) {
            if (!empty($penulis['nama']) && !empty($penulis['prodi'])) {
                Penulis::create([
                    'ketpub_id' => $ketpub->id,
                    'nama' => $penulis['nama'],
                    'jurusan_prodi' => $penulis['prodi'],
                ]);
            }
        }

        return redirect()->route('ketpub.index')->with(['success' => 'Data berhasil Disimpan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $ketpub = Ketpub::findOrFail($id);
        $ketpub->load('penulis');
        $year = Carbon::parse($ketpub->tanggal)->translatedFormat('Y');
        $title = 'Tampilan Surat Publikasi';
        if ($user->role === 'admin') {
            return view('user.admin.ketpub.show', compact('ketpub', 'title', 'year'));
        } elseif ($user->role === 'dosen') {
            return view('user.dosen.ketpub.show', compact('ketpub', 'title', 'year'));
        }
        abort(403, 'Unautorized');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ketpub = Ketpub::findOrFail($id);
        $ketpub->load('penulis');
        $title = 'Edit Surat Publikasi';
        return view('user.dosen.ketpub.edit', compact('ketpub', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi request
        $request->validate([
            'kategori_publikasi'         => 'required',
            'judul'         => 'required',
            'namaPenerbit'  => 'required|min:5',
            'penerbit'      => 'required|min:5',
            'bulan'         => 'required',
            'tahun'         => 'required',
            'issn'          => 'required|min:5',
        ]);

        $ketpub = Ketpub::findOrFail($id); // Pastikan data ada, gunakan findOrFail

        // Hapus penulis lama
        $ketpub->penulis()->delete();

        // Perbarui penulis jika ada
        if ($request->has('penulis')) {
            // Hapus data penulis lama
            $ketpub->penulis()->delete();

            // Simpan data penulis baru
            foreach ($request->penulis as $penulis) {
                if (!empty($penulis['nama']) && !empty($penulis['prodi'])) {
                    $ketpub->penulis()->create([
                        'nama' => $penulis['nama'],
                        'jurusan_prodi' => $penulis['prodi'],
                    ]);
                }
            }
        }

        // Update data ketpub
        $ketpub->update([
            'kategori_publikasi' => $request->kategori_publikasi,
            'judul' => $request->judul,
            'namaPenerbit' => $request->namaPenerbit,
            'penerbit' => $request->penerbit,
            'jilid' => $request->jilid,
            'edisi' => $request->edisi,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'issn' => $request->issn,
        ]);

        return redirect()->route('ketpub.index')->with('success', 'Data Berhasil di Update');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ketpub = Ketpub::findOrFail($id);
        $ketpub->delete();

        return redirect()->route('ketpub.index')->with('success', 'Data Berhasil di Hapus');
    }

    public function downloadWord(string $id)
    {
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor('suratKetPub.docx');

        $ketpub = Ketpub::with('penulis')->findOrFail($id);

        $tanggal = Carbon::parse($ketpub->tanggal)->translatedFormat('j F Y');
        $year = Carbon::parse($ketpub->tanggal)->translatedFormat('Y');

        $phpWord->setValues([
            'nomorSurat' => $ketpub->nomorSurat ?: '-',
            'kategori_publikasi'          => $ketpub->kategori_publikasi,
            'judul'          => $ketpub->judul,
            'namaPenerbit'   => $ketpub->namaPenerbit,
            'penerbit'       => $ketpub->penerbit,
            'jilid'         => $ketpub->jilid,
            'edisi'          => $ketpub->edisi,
            'bulan'          => $ketpub->bulan,
            'tahun'          => $ketpub->tahun,
            'akreditas'      => $ketpub->akreditas,
            'issn'           => $ketpub->issn,
            'tanggal'        => $tanggal,
            'tahun' => $year,

        ]);
        // Hitung jumlah penulis
        $penulis = $ketpub->penulis;

        // Clone row untuk data penulis
        $phpWord->cloneRow('no', $penulis->count());

        foreach ($penulis as $index => $penulis) {
            $row = $index + 1;

            $phpWord->setValue("no#{$row}", $row);
            $phpWord->setValue("namaPenulis#{$row}", $penulis->nama ?: '-');
            $phpWord->setValue("jurusanProdi#{$row}", $penulis->jurusan_prodi ?: '-');
        }

        $fileName = 'Surat_Keterangan_Publik_' . $ketpub->penulis1 . '.docx';
        $phpWord->saveAs($fileName);

        // Return the file for download
        return response()->download($fileName)->deleteFileAfterSend(true);
    }
}
