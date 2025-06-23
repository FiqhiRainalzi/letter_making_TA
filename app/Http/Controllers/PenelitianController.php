<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Prodi;
use GuzzleHttp\Client;
use App\Models\Anggota;
use App\Models\Riwayat;
use App\Models\KodeSurat;
use App\Models\AjuanSurat;
use App\Models\Penelitian;
use App\Models\Verifikasi;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\TenagaPembantu;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Helpers\ValidasiNomorSuratHelper;

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
        //menghitung hari proses pengajuan
        $penelitian->transform(function ($item) {
            if (in_array($item->statusSurat, ['approved', 'ready_to_pickup', 'picked_up', 'rejected'])) {
                $item->lama_proses = Carbon::parse($item->created_at)->diffInDays(Carbon::parse($item->updated_at));
            } else {
                $item->lama_proses = Carbon::parse($item->created_at)->diffInDays(Carbon::now());
            }
            return $item;
        });
        return view('user.dosen.penelitian.index', compact('penelitian', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $prodis = Prodi::all();
        $title = 'Tambah Surat Penelitian';
        return view('user.dosen.penelitian.create', compact('title', 'prodis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data utama
        $request->validate([
            'namaKetua' => 'required',
            'nipNidn' => 'required|min:5',
            'jabatanAkademik' => 'required|min:5',
            'jurusanProdi' => 'required',
            'judul' => 'required|min:5',
            'skim' => 'required|min:5',
            'dasarPelaksanaan' => 'required|min:5',
            'lokasi' => 'required|min:5',
            'bulanPelaksanaan' => 'required|date',
            'bulanAkhirPelaksanaan' => 'required|date',
        ]);

        $ajuan = AjuanSurat::create([
            'user_id' => Auth::id(),
            'jenis_surat' => 'penelitian',
            'status' => 'diajukan',
        ]);

        // Simpan data penelitian
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
            'statusSurat' => 'draf',
            'ajuan_surat_id' => $ajuan->id,
            'user_id' => Auth::id(),
        ]);

        // Simpan anggota penelitian
        foreach ($request->anggota as $anggota) {
            if (!empty($anggota['nama'])) {
                $penelitian->anggota()->create([
                    'nama' => $anggota['nama'],
                    'prodi_id' => $anggota['prodi_id'],
                ]);
            }
        }

        // Simpan tenaga pembantu penelitian
        foreach ($request->tenaga_pembantu as $tenaga) {
            if (!empty($tenaga['nama'])) {
                $penelitian->tenagaPembantu()->create([
                    'nama' => $tenaga['nama'],
                    'prodi_id' => $tenaga['prodi_id'],
                ]);
            }
        }

        $user = Auth::user();
        // Tambahkan ke tabel riwayat surat
        Riwayat::create([
            'user_id' => Auth::id(), // <- WAJIB agar masuk ke index dosen
            'ajuan_surat_id' => $ajuan->id,
            'aksi' => 'Mengajukan Surat Tugas Pengabdian',
            'diubah_oleh' => $user->name,
            'catatan' => 'Surat "' . $request->judul . '" diajukan oleh ' . $user->name,
            'waktu_perubahan' => now(),
        ]);

        // Notifikasi
        $penelitian = Penelitian::latest()->first();
        $admins = User::where('role', 'admin')->get();
        $client = new Client();
        // Siapkan datanya untuk message
        $message = View::make('user.notif.whatsapp', [
            'judul_notifikasi' => 'Surat Tugas Penelitian Baru Saja Dibuat',
            'data' => [
                'Judul'   => $penelitian->judul,
                'Pemohon' => $penelitian->user->name ?? 'N/A',
                'Tanggal' => now()->format('d M Y'),
                'Status'  => ucfirst($request->status ?? 'Belum Ditentukan'),
            ],
            'footer' => 'Silakan cek sistem untuk melakukan tanda tangan.',
        ])->render();

        foreach ($admins as $admin) {
            $response = $client->post('https://api.fonnte.com/send', [
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

        return redirect()->route('penelitian.index')->with('success', 'Surat Tugas Penelitian berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        $penelitian = Penelitian::findOrFail($id);
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
        $penelitian = Penelitian::findOrFail($id); // Ambil data penelitian
        $prodis = Prodi::all(); // Ambil semua data prodi
        $title = 'Edit Surat Penelitian';
        return view('user.dosen.penelitian.edit', compact('penelitian', 'prodis', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi data utama
        $request->validate([
            'namaKetua' => 'required',
            'nipNidn' => 'required|min:5',
            'jabatanAkademik' => 'required|min:5',
            'jurusanProdi' => 'required',
            'judul' => 'required|min:5',
            'skim' => 'required|min:5',
            'dasarPelaksanaan' => 'required|min:5',
            'lokasi' => 'required|min:5',
            'bulanPelaksanaan' => 'required',
            'bulanAkhirPelaksanaan' => 'required',
        ]);

        // Ambil data penelitian yang akan diupdate
        $penelitian = Penelitian::findOrFail($id);

        // Update data utama
        $penelitian->update([
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
        ]);

        // Hapus semua anggota dan tenaga pembantu yang terkait
        $penelitian->anggota()->delete();
        $penelitian->tenagaPembantu()->delete();

        // Simpan anggota penelitian yang baru
        foreach ($request->anggota as $anggota) {
            if (!empty($anggota['nama'])) {
                $penelitian->anggota()->create([
                    'nama' => $anggota['nama'],
                    'prodi_id' => $anggota['prodi_id'],
                ]);
            }
        }

        // Simpan tenaga pembantu penelitian yang baru
        foreach ($request->tenaga_pembantu as $tenaga) {
            if (!empty($tenaga['nama'])) {
                $penelitian->tenagaPembantu()->create([
                    'nama' => $tenaga['nama'],
                    'prodi_id' => $tenaga['prodi_id'],
                ]);
            }
        }
        // Tambahkan Riwayat
        $user = Auth::user();
        $ajuan = $penelitian->ajuanSurat;
        if ($ajuan) {
            Riwayat::create([
                'user_id' => Auth::id(), // <- WAJIB agar masuk ke index dosen
                'ajuan_surat_id' => $ajuan->id,
                'aksi' => 'Mengedit Surat Tugas Pengabdian',
                'diubah_oleh' => $user->name,
                'catatan' => 'Surat diperbarui oleh ' . $user->name,
                'waktu_perubahan' => now(),
            ]);
        } else {
            Log::warning("AjuanSurat dengan ID $id tidak ditemukan saat ingin membuat riwayat.");
        }

        return redirect()->route('penelitian.index')->with('success', 'Surat Tugas Penelitian berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $penelitian = Penelitian::findOrFail($id);
        // Simpan referensi sebelum dihapus
        $ajuan = $penelitian->ajuanSurat;
        // Catat ke riwayat
        Riwayat::create([
            'user_id' => Auth::id(), // siapa yang menghapus
            'ajuan_surat_id' => $ajuan->id,
            'aksi' => 'Menghapus Surat Penelitian',
            'catatan' => 'Data Penelitian dengan judul "' . $penelitian->judul . '" telah dihapus.',
            'waktu_perubahan' => now(),
        ]);
        $penelitian->delete();
        return redirect()->route('penelitian.index')->with('success', 'Data Berhasil di Hapus');
    }
    //penelitian controller
    public function verifikasiPenelitianView()
    {
        $penelitian = Penelitian::get();
        $today = date('Y-m-d');
        $title = 'Surat Penelitian';
        return view('user.admin.penelitian.index', compact('penelitian', 'today', 'title'));
    }

    public function verifikasiPenelitianEdit($id)
    {
        $penelitian = Penelitian::findOrFail($id);
        $kodeSurats = KodeSurat::all();
        $title = 'Edit Surat Penelitian';
        return view('user.admin.penelitian.edit', compact('penelitian', 'title', 'kodeSurats'));
    }

    public function verifikasiPenelitianUpdate(Request $request, string $id)
    {
        $penelitian = Penelitian::findOrFail($id);
        //buat notif
        $ketua = User::where('role', 'ketua')->first();
        $status = strtolower($request->status);
        // VALIDASI
        $request->validate(
            [
                'kode_surat_id' => 'required|exists:kode_surat,id',
                'status' => 'required',
                'nomorSurat' => [
                    'required',
                    'numeric',
                    function ($attribute, $value, $fail) use ($request, $id) {
                        if (ValidasiNomorSuratHelper::isDipakai($request->kode_surat_id, $value, $id)) {
                            $fail("Nomor $value sudah dipakai untuk kode surat ini di surat lain.");
                        }

                        $max = ValidasiNomorSuratHelper::maxNomorTerakhir($request->kode_surat_id, $id, 'penelitian') ?? 0;

                        if ($value > $max + 1) {
                            $fail("Nomor tidak boleh lompat. Nomor terakhir: $max. Anda hanya boleh mengisi " . ($max + 1));
                        }
                    },
                ],
            ],
            [
                'nomorSurat.required' => 'Nomor surat wajib diisi.',
                'nomorSurat.numeric' => 'Nomor surat harus berupa angka.',
            ]
        );

        // Simpan ke penelitian
        $penelitian->update([
            'kode_surat_id' => $request->kode_surat_id,
            'nomorSurat' => $request->nomorSurat,
        ]);

        // Simpan data ke tabel verifikasi
        Verifikasi::create([
            'ajuan_surat_id' => $penelitian->ajuan_surat_id,
            'petugas_id' => Auth::id(), // user yang sedang login (harus petugas)
            'verified_at' => now(),
        ]);

        // Update status hanya di tabel ajuan_surats
        $ajuan = $penelitian->ajuanSurat;
        $ajuan->update([
            'status' => $request->status,
        ]);
        Riwayat::create([
            'user_id' => Auth::id(),
            'ajuan_surat_id' => $penelitian->ajuan_surat_id,
            'aksi' => 'Verifikasi Surat HKI',
            'waktu_perubahan' => now(),
            'catatan' => 'Status diubah menjadi: ' . ucfirst($status),
        ]);

        // Cek jika status "disetujui" dan kirim WA ke ketua
        if ($status === 'disetujui') {
            if ($ketua && $ketua->nomor_telepon) {
                $message = View::make('user.notif.whatsapp', [
                    'judul_notifikasi' => 'Surat Tugas Penelitian Telah Disetujui',
                    'data' => [
                        'Judul'   => $penelitian->judul,
                        'Pemohon' => $penelitian->user->name ?? 'N/A',
                        'Tanggal' => now()->format('d M Y'),
                        'Status'  => ucfirst($status),
                    ],
                    'footer' => 'Silakan cek sistem untuk melakukan tanda tangan.',
                ])->render();

                $client = new Client();
                $client->post('https://api.fonnte.com/send', [
                    'headers' => [
                        'Authorization' => env('FONNTE_API_KEY'),
                        'Accept' => 'application/json',
                    ],
                    'form_params' => [
                        'target' => $ketua->nomor_telepon,
                        'message' => $message,
                    ],
                ]);
            }
        }

        if ($status === 'siap diambil') {
            $dosen = $ajuan->user;
            if ($dosen && $dosen->nomor_telepon) {
                $message = View::make('user.notif.whatsapp', [
                    'judul_notifikasi' => 'Surat Tugas Penelitian Dapat Diambil',
                    'data' => [
                        'Judul'   => $penelitian->judul,
                        'Pemohon' => $penelitian->user->name ?? 'N/A',
                        'Tanggal' => now()->format('d M Y'),
                        'Status'  => ucfirst($status),
                    ],
                    'footer' => 'Silakan mendatangi kantor unit P3M untuk mengambil surat tugas.',
                ])->render();

                $client = new Client();
                $client->post('https://api.fonnte.com/send', [
                    'headers' => [
                        'Authorization' => env('FONNTE_API_KEY'),
                        'Accept' => 'application/json',
                    ],
                    'form_params' => [
                        'target' => $dosen->nomor_telepon,
                        'message' => $message,
                    ],
                ]);
            }
        }
        return redirect()->route('admin.penelitianView')->with('success', 'Data berhasil diupdate');
    }
    //end penelitian controller
    public function downloadWord(string $id)
    {
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor('suratPenelitian.docx');

        $penelitian = Penelitian::findOrFail($id);

        $bulan = Carbon::parse($penelitian->bulanPelaksanaan)->translatedFormat('F Y');
        $akhirBulan = Carbon::parse($penelitian->bulanAkhirPelaksanaan)->translatedFormat('F Y');
        $year = Carbon::parse($penelitian->tanggal)->translatedFormat('Y');
        $formattedDate = Carbon::parse($penelitian->tanggal)->translatedFormat('j F Y');

        // Data Anggota dalam Bentuk Array
        $anggotaData = [];
        foreach ($penelitian->anggota as $index => $anggota) {
            $anggotaData[] = [
                'no_anggota' => $index + 1,
                'nama_anggota' => $anggota->nama,
                'bidang_studi' => $anggota->prodi->nama ?? '-',
            ];
        }

        // Data Tenaga Pembantu dalam Bentuk Array
        $tenagaData = [];
        foreach ($penelitian->tenagaPembantu as $index => $tenaga) {
            $tenagaData[] = [
                'no_pembantu' => $index + 1,
                'nama_pembantu' => $tenaga->nama,
                'prodi_pembantu' => $tenaga->prodi->nama ?? '-',
            ];
        }

        // Clone Row untuk Anggota
        if (!empty($anggotaData)) {
            $phpWord->cloneRowAndSetValues('no_anggota', $anggotaData);
        } else {
            $phpWord->setValue('no_anggota', '');
            $phpWord->setValue('nama_anggota', '');
            $phpWord->setValue('bidang_studi', '');
        }

        // Clone Row untuk Tenaga Pembantu
        if (!empty($tenagaData)) {
            $phpWord->cloneRowAndSetValues('no_pembantu', $tenagaData);
        } else {
            $phpWord->setValue('no_pembantu', '');
            $phpWord->setValue('nama_pembantu', '');
            $phpWord->setValue('prodi_pembantu', '');
        }
        //  **Isi Placeholder Statis**
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

        $tandaTanganPath = public_path('storage/' . $penelitian->tanda_tangan);

        if (file_exists($tandaTanganPath)) {
            $phpWord->setImageValue('tanda_tangan_ketua', [
                'path' => $tandaTanganPath,
                'width' => 150,
                'height' => 80,
                'ratio' => true,
            ]);
        }

        $fileName = 'Surat_Penelitian_' . str_replace(' ', '_', $penelitian->namaKetua) . '.docx';
        $phpWord->saveAs($fileName);

        // Return the file for download
        return response()->download($fileName)->deleteFileAfterSend(true);
    }
}
