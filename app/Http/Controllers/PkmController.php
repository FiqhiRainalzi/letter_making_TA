<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Notification;
use App\Models\Pkm;
use App\Models\TenagaPembantu;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Ramsey\Uuid\v1;

class PkmController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $pkm = Pkm::where('user_id', $user->id)->get();
        $title = 'Surat PKM';
        return view('user.dosen.pkm.index', compact('pkm', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Surat PKM';
        return view('user.dosen.pkm.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Kirim notifikasi ke admin
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Pengajuan Baru',
                'message' => 'Dosen telah membuat pengajuan surat PKM.',
                'status' => 'unread',
            ]);
        }

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

        // Membuat pkm
        $pkm = Pkm::create([
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

        // Menambahkan Anggota (Validasi untuk mengabaikan input kosong)
        foreach ($request->anggota as $anggota) {
            if (!empty($anggota['nama']) && !empty($anggota['prodi'])) { // Hanya proses jika nama dan prodi tidak kosong
                Anggota::create([
                    'pkm_id' => $pkm->id,
                    'nama' => $anggota['nama'],
                    'prodi' => $anggota['prodi'],
                ]);
            }
        }

        // Menambahkan Tenaga Pembantu (Validasi untuk mengabaikan input kosong)
        foreach ($request->tenaga as $tenaga) {
            if (!empty($tenaga['nama']) && !empty($tenaga['status'])) { // Hanya proses jika nama dan status tidak kosong
                TenagaPembantu::create([
                    'pkm_id' => $pkm->id,
                    'nama' => $tenaga['nama'],
                    'status' => $tenaga['status'],
                ]);
            }
        }

        return redirect()->route('pkm.index')->with('success', 'Data Berhasil Disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        $pkm = Pkm::findOrFail($id);
        $pkm->load(['anggota', 'tenagaPembantu']);
        $title = 'Tampilan Surat PKM';
        if ($user->role === 'admin') {
            return view('user.admin.pkm.show', compact('pkm', 'title'));
        } elseif ($user->role === 'dosen') {
            return view('user.dosen.pkm.show', compact('pkm', 'title'));
        }
        abort(403, 'Unauthorized');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pkm = Pkm::findOrFail($id);
        $title = 'Edit Surat PKM';
        return view('user.dosen.pkm.edit', compact('pkm', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Mencari data PKM berdasarkan ID
        $pkm = Pkm::findOrFail($id);

        // Mengupdate anggota
        if ($request->has('anggota')) {
            $existingAnggotaIds = [];

            // Perbarui atau tambahkan anggota baru
            foreach ($request->anggota as $anggota) {
                if (!empty($anggota['nama'])) {
                    if (isset($anggota['id']) && $anggota['id']) {
                        // Jika anggota memiliki ID, update data yang sudah ada
                        $existingAnggota = $pkm->anggota()->find($anggota['id']);
                        if ($existingAnggota) {
                            $existingAnggota->update([
                                'nama' => $anggota['nama'],
                                'prodi' => $anggota['prodi'] ?? null,
                            ]);
                            $existingAnggotaIds[] = $anggota['id']; // Menyimpan ID yang sudah diperbarui
                        }
                    } else {
                        // Jika anggota tidak memiliki ID, tambahkan anggota baru
                        $newAnggota = $pkm->anggota()->create([
                            'nama' => $anggota['nama'],
                            'prodi' => $anggota['prodi'] ?? null,
                        ]);
                        $existingAnggotaIds[] = $newAnggota->id; // Menyimpan ID anggota yang baru
                    }
                }
            }

            // Menghapus anggota yang tidak ada di input
            $pkm->anggota()->whereNotIn('id', $existingAnggotaIds)->delete();
        }

        // Mengupdate tenaga pembantu
        if ($request->has('tenagaPembantu')) {
            $existingTenagaIds = [];

            // Perbarui atau tambahkan tenaga pembantu baru
            foreach ($request->tenagaPembantu as $tenaga) {
                if (!empty($tenaga['nama'])) {
                    if (isset($tenaga['id']) && $tenaga['id']) {
                        // Jika tenaga pembantu memiliki ID, update data yang sudah ada
                        $existingTenaga = $pkm->tenagaPembantu()->find($tenaga['id']);
                        if ($existingTenaga) {
                            $existingTenaga->update([
                                'nama' => $tenaga['nama'],
                                'status' => $tenaga['status'] ?? null,
                            ]);
                            $existingTenagaIds[] = $tenaga['id']; // Menyimpan ID yang sudah diperbarui
                        }
                    } else {
                        // Jika tenaga pembantu tidak memiliki ID, tambahkan tenaga pembantu baru
                        $newTenaga = $pkm->tenagaPembantu()->create([
                            'nama' => $tenaga['nama'],
                            'status' => $tenaga['status'] ?? null,
                        ]);
                        $existingTenagaIds[] = $newTenaga->id; // Menyimpan ID tenaga pembantu yang baru
                    }
                }
            }

            // Menghapus tenaga pembantu yang tidak ada di input
            $pkm->tenagaPembantu()->whereNotIn('id', $existingTenagaIds)->delete();
        }

        // Menyimpan perubahan lainnya yang tidak melibatkan anggota dan tenaga pembantu
        $pkm->update([
            'namaKetua' => $request->namaKetua,
            'nipNidn' => $request->nipNidn,
            'jabatanAkademik' => $request->jabatanAkademik,
            'jurusanProdi'  => $request->jurusanProdi,
            'skim'  => $request->skim,
            'dasarPelaksanaan'  => $request->dasarPelaksanaan,
            'lokasi'  => $request->lokasi,
            'bulanPelaksanaan'  => $request->bulanPelaksanaan,
            'bulanAkhirPelaksanaan'  => $request->bulanAkhirPelaksanaan,
            'judul' => $request->judul,  // Contoh field lain untuk update
        ]);
        return redirect()->route('pkm.index')->with('success', 'Data Berhasil di Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pkm = Pkm::findOrFail($id);
        $pkm->delete();

        return redirect()->route('pkm.index')->with('success', 'Data Berhasil di Hapus');
    }

    public function downloadWord(string $id)
    {
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor('suratPkm.docx');

        $pkm = Pkm::with(['anggota', 'tenagaPembantu'])->findOrFail($id);

        $bulan = Carbon::parse($pkm->bulanPelaksanaan)->translatedFormat('F Y');
        $akhirBulan = Carbon::parse($pkm->bulanAkhirPelaksanaan)->translatedFormat('F Y');
        $year = Carbon::parse($pkm->tanggal)->translatedFormat('Y');
        $formattedDate = Carbon::parse($pkm->tanggal)->translatedFormat('j F Y');

        // Ambil data anggota
        $anggota = $pkm->anggota;

         // Clone row untuk data anggota
         $phpWord->cloneRow('noA', $anggota->count());
 
         foreach ($anggota as $index => $anggota) {
             $row = $index + 1;
 
             $phpWord->setValue("noA#{$row}", $row);
             $phpWord->setValue("namaAnggota#{$row}", $anggota->nama ?: '-');
             $phpWord->setValue("prodiAnggota#{$row}", $anggota->prodi ?: '-');
         }
 
         // Hitung jumlah tenaga pembantu
         $tenagaPenelitiPembantu = $pkm->tenagaPembantu;
 
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
            'nomorSurat' => $pkm->nomorSurat,
            'namaKetua' => $pkm->namaKetua,
            'nipNidn' => $pkm->nipNidn,
            'jabatanAkademik' => $pkm->jabatanAkademik,
            'jurusanProdi' => $pkm->jurusanProdi,
            'judul' => $pkm->judul,
            'skim' => $pkm->skim,
            'dasarPelaksanaan' => $pkm->dasarPelaksanaan,
            'lokasi' => $pkm->lokasi,
            'tanggalPelaksanaan' => $bulan,
            'tanggalAkhirPelaksanaan' => $akhirBulan,
            'tanggal' => $formattedDate,
            'tahun' => $year,
        ]);

        $fileName = 'Surat_PKM_' . $pkm->namaKetua . '.docx';
        $phpWord->saveAs($fileName);

        // Return the file for download
        return response()->download($fileName)->deleteFileAfterSend(true);
    }
}
