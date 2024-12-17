<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Notification;
use App\Models\Penelitian;
use App\Models\TenagaPembantu;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenelitianController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $penelitian = Penelitian::where('user_id', $user->id)->get();
        $title = 'Surat Penelitian';
        return view('user.dosen.penelitian.index', compact('penelitian', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Surat Penelitian';
        return view('user.dosen.penelitian.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'namaKetua' => 'required',
            'nipNidn' => 'required|min:5',
            'jabatanAkademik' => 'required|min:5',
            'jurusanProdi' => 'required|min:5',
            'anggota' => 'required|array',
            'judul' => 'required|min:5',
            'skim' => 'required|min:5',
            'dasarPelaksanaan' => 'required|min:5',
            'lokasi' => 'required|min:5',
            'bulanPelaksanaan' => 'required',
            'bulanAkhirPelaksanaan' => 'required',
        ]);

        $user = Auth::user();

        // Membuat Penelitian
        $penelitian = Penelitian::create([
            'namaKetua' => $request->namaKetua,
            'nipNidn' => $request->nipNidn,
            'jabatanAkademik' => $request->jabatanAkademik,
            'jurusanProdi' => $request->jurusanProdi,
            'judul' => $request->judul,
            'skim' => $request->skim,
            'dasarPelaksanaan' => $request->dasarPelaksanaan,
            'lokasi' => $request->lokasi,
            'bulanPelaksanaan' => $request->bulanPelaksanaan,
            'bulanAkhirPelaksanaan' => $request->bulanAkhirPelaksanaan,
            'tanggal' => $request->tanggal,
            'statusSurat' => 'pending',
            'user_id' => $user->id,
        ]);

        // Kirim notifikasi ke admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Pengajuan Baru',
                'message' => 'Dosen telah membuat pengajuan surat Penelitian.',
                'status' => 'unread',
            ]);
        }

        // Menambahkan Anggota (Validasi untuk mengabaikan input kosong)
        foreach ($request->anggota as $anggota) {
            if (!empty($anggota['nama']) && !empty($anggota['prodi'])) { // Hanya proses jika nama dan prodi tidak kosong
                Anggota::create([
                    'penelitian_id' => $penelitian->id,
                    'nama' => $anggota['nama'],
                    'prodi' => $anggota['prodi'],
                ]);
            }
        }

        // Menambahkan Tenaga Pembantu (Validasi untuk mengabaikan input kosong)
        foreach ($request->tenaga as $tenaga) {
            if (!empty($tenaga['nama']) && !empty($tenaga['status'])) { // Hanya proses jika nama dan status tidak kosong
                TenagaPembantu::create([
                    'penelitian_id' => $penelitian->id,
                    'nama' => $tenaga['nama'],
                    'status' => $tenaga['status'],
                ]);
            }
        }

        return redirect()->route('penelitian.index')->with('success', 'Surat Tugas Penelitian berhasil dibuat');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        $penelitian = Penelitian::findOrFail($id);

        // Memuat relasi anggota dan tenagaPembantu
        $penelitian->load('anggota', 'tenagaPembantu');

        $title = 'Tampilan Surat Penelitian';

        if ($user->role === 'admin') {
            return view('user.admin.penelitian.show', compact('penelitian', 'title'));
        } elseif ($user->role === 'dosen') {
            return view('user.dosen.penelitian.show', compact('penelitian', 'title'));
        }

        abort(403, 'Unauthorized');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $penelitian = Penelitian::findOrFail($id);
        $penelitian->load('anggota', 'tenagaPembantu');
        $title = 'Edit Surat Penelitian';
        return view('user.dosen.penelitian.edit', compact('penelitian', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Mencari data penelitian berdasarkan ID
        $penelitian = Penelitian::findOrFail($id);

        // Mengupdate anggota
        if ($request->has('anggota')) {
            $existingAnggotaIds = [];

            // Perbarui atau tambahkan anggota baru
            foreach ($request->anggota as $anggota) {
                if (!empty($anggota['nama'])) {
                    if (isset($anggota['id']) && $anggota['id']) {
                        // Jika anggota memiliki ID, update data yang sudah ada
                        $existingAnggota = $penelitian->anggota()->find($anggota['id']);
                        if ($existingAnggota) {
                            $existingAnggota->update([
                                'nama' => $anggota['nama'],
                                'prodi' => $anggota['prodi'] ?? null,
                            ]);
                            $existingAnggotaIds[] = $anggota['id']; // Menyimpan ID yang sudah diperbarui
                        }
                    } else {
                        // Jika anggota tidak memiliki ID, tambahkan anggota baru
                        $newAnggota = $penelitian->anggota()->create([
                            'nama' => $anggota['nama'],
                            'prodi' => $anggota['prodi'] ?? null,
                        ]);
                        $existingAnggotaIds[] = $newAnggota->id; // Menyimpan ID anggota yang baru
                    }
                }
            }

            // Menghapus anggota yang tidak ada di input
            $penelitian->anggota()->whereNotIn('id', $existingAnggotaIds)->delete();
        }

        // Mengupdate tenaga pembantu
        if ($request->has('tenagaPembantu')) {
            $existingTenagaIds = [];

            // Perbarui atau tambahkan tenaga pembantu baru
            foreach ($request->tenagaPembantu as $tenaga) {
                if (!empty($tenaga['nama'])) {
                    if (isset($tenaga['id']) && $tenaga['id']) {
                        // Jika tenaga pembantu memiliki ID, update data yang sudah ada
                        $existingTenaga = $penelitian->tenagaPembantu()->find($tenaga['id']);
                        if ($existingTenaga) {
                            $existingTenaga->update([
                                'nama' => $tenaga['nama'],
                                'status' => $tenaga['status'] ?? null,
                            ]);
                            $existingTenagaIds[] = $tenaga['id']; // Menyimpan ID yang sudah diperbarui
                        }
                    } else {
                        // Jika tenaga pembantu tidak memiliki ID, tambahkan tenaga pembantu baru
                        $newTenaga = $penelitian->tenagaPembantu()->create([
                            'nama' => $tenaga['nama'],
                            'status' => $tenaga['status'] ?? null,
                        ]);
                        $existingTenagaIds[] = $newTenaga->id; // Menyimpan ID tenaga pembantu yang baru
                    }
                }
            }

            // Menghapus tenaga pembantu yang tidak ada di input
            $penelitian->tenagaPembantu()->whereNotIn('id', $existingTenagaIds)->delete();
        }

        // Menyimpan perubahan lainnya yang tidak melibatkan anggota dan tenaga pembantu
        $penelitian->update([
            'namaKetua' => $request->namaKetua,
            'nipNidn' => $request->nipNidn,
            'jabatanAkademik' => $request->jabatanAkademik,
            'jurusanProdi'  => $request->jurusanProdi,
            'skim'  => $request->skim,
            'dasarPelaksanaan'  => $request->dasarPelaksanaan,
            'lokasi'  => $request->lokasi,
            'bulanPelaksanaan'  => $request->bulanPelaksanaan,
            'bulanAkhirPelaksanaan'  => $request->bulanAkhirPelaksanaan,
            'tanggal' => $request->tanggal,
            'judul' => $request->judul,  // Contoh field lain untuk update
        ]);

        return redirect()->route('penelitian.index')->with('success', 'Data Penelitian berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $penelitian = Penelitian::findOrFail($id);
        $penelitian->delete();

        return redirect()->route('penelitian.index')->with('success', 'Data Berhasil di Hapus');
    }

    public function downloadWord(string $id)
    {
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor('suratPenelitian.docx');

        $penelitian = Penelitian::with(['anggota', 'tenagaPembantu'])->findOrFail($id);

        $bulan = Carbon::parse($penelitian->bulanPelaksanaan)->translatedFormat('F Y');
        $akhirBulan = Carbon::parse($penelitian->bulanAkhirPelaksanaan)->translatedFormat('F Y');
        $year = Carbon::parse($penelitian->tanggal)->translatedFormat('Y');
        $formattedDate = Carbon::parse($penelitian->tanggal)->translatedFormat('j F Y');

        // Hitung jumlah anggota
        $anggotas = $penelitian->anggota;

        // Clone row untuk data anggota
        $phpWord->cloneRow('noA', $anggotas->count());

        foreach ($anggotas as $index => $anggota) {
            $row = $index + 1;

            $phpWord->setValue("noA#{$row}", $row);
            $phpWord->setValue("namaAnggota#{$row}", $anggota->nama ?: '-');
            $phpWord->setValue("prodiAnggota#{$row}", $anggota->prodi ?: '-');
        }

        // Hitung jumlah tenaga pembantu
        $tenagaPenelitiPembantu = $penelitian->tenagaPembantu;

        // Clone row untuk data tenaga pembantu
        $phpWord->cloneRow('noB', $tenagaPenelitiPembantu->count());

        foreach ($tenagaPenelitiPembantu as $index => $tenaga) {
            $row = $index + 1;

            $phpWord->setValue("noB#{$row}", $row);
            $phpWord->setValue("tenagaPembantu#{$row}", $tenaga->nama ?: '-');
            $phpWord->setValue("status#{$row}", $tenaga->status ?: '-');
        }


        // Isi placeholder statis lainnya
        $phpWord->setValues([
            'nomorSurat' => $penelitian->nomorSurat,
            'namaKetua' => $penelitian->namaKetua,
            'nipNidn' => $penelitian->nipNidn,
            'jabatanAkademik' => $penelitian->jabatanAkademik,
            'jurusanProdi' => $penelitian->jurusanProdi,
            'judul' => $penelitian->judul,
            'skim' => $penelitian->skim,
            'dasarPelaksanaan' => $penelitian->dasarPelaksanaan,
            'lokasi' => $penelitian->lokasi,
            'tanggalPelaksanaan' => $bulan,
            'tanggalAkhirPelaksanaan' => $akhirBulan,
            'tanggal' => $formattedDate,
            'tahun' => $year,
        ]);

        $fileName = 'Surat_Penelitian_' . str_replace(' ', '_', $penelitian->namaKetua) . '.docx';
        $phpWord->saveAs($fileName);

        // Return the file for download
        return response()->download($fileName)->deleteFileAfterSend(true);
    }
}
