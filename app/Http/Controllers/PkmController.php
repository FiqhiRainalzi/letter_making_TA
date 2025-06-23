<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pkm;
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
use function Ramsey\Uuid\v1;
use Illuminate\Http\Request;
use App\Models\TenagaPembantu;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Helpers\ValidasiNomorSuratHelper;

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
        //menghitung hari proses pengajuan
        $pkm->transform(function ($item) {
            if (in_array($item->statusSurat, ['approved', 'ready_to_pickup', 'picked_up', 'rejected'])) {
                $item->lama_proses = Carbon::parse($item->created_at)->diffInDays(Carbon::parse($item->updated_at));
            } else {
                $item->lama_proses = Carbon::parse($item->created_at)->diffInDays(Carbon::now());
            }
            return $item;
        });
        return view('user.dosen.pkm.index', compact('pkm', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $prodis = Prodi::all();
        $title = 'Surat PKM';
        return view('user.dosen.pkm.create', compact('title', 'prodis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data utama PKM
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
            'jenis_surat' => 'pkm',
            'status' => 'diajukan',
        ]);

        // Simpan data PKM
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
            'ajuan_surat_id' => $ajuan->id,
            'user_id' => Auth::id(), // Ambil ID user yang sedang login
        ]);

        // Simpan anggota PKM
        foreach ($request->anggota as $anggota) {
            if (!empty($anggota['nama'])) {
                $pkm->anggota()->create([
                    'nama' => $anggota['nama'],
                    'prodi_id' => $anggota['prodi_id'],
                ]);
            }
        }

        // Simpan tenaga pembantu PKM
        foreach ($request->tenaga_pembantu as $tenaga) {
            if (!empty($tenaga['nama'])) {
                $pkm->tenagaPembantu()->create([
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
        $pkm = Pkm::latest()->first();
        $admins = User::where('role', 'admin')->get();
        $client = new Client();
        // Siapkan datanya untuk message
        $message = View::make('user.notif.whatsapp', [
            'judul_notifikasi' => 'Surat Tugas Pengabdian Baru Saja Dibuat',
            'data' => [
                'Judul'   => $pkm->judul,
                'Pemohon' => $pkm->user->name ?? 'N/A',
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

        return redirect()->route('pkm.index')->with('success', 'Data Berhasil Disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        $pkm = Pkm::findOrFail($id);
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
        $prodis = Prodi::all();
        $title = 'Edit Surat PKM';
        return view('user.dosen.pkm.edit', compact('pkm', 'title', 'prodis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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

        // Ambil data pkm yang akan diupdate
        $pkm = Pkm::findOrFail($id);

        // Update data utama
        $pkm->update([
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
        $pkm->anggota()->delete();
        $pkm->tenagaPembantu()->delete();

        // Simpan anggota pkm yang baru
        foreach ($request->anggota as $anggota) {
            if (!empty($anggota['nama'])) {
                $pkm->anggota()->create([
                    'nama' => $anggota['nama'],
                    'prodi_id' => $anggota['prodi_id'],
                ]);
            }
        }

        // Simpan tenaga pembantu pkm yang baru
        foreach ($request->tenaga_pembantu as $tenaga) {
            if (!empty($tenaga['nama'])) {
                $pkm->tenagaPembantu()->create([
                    'nama' => $tenaga['nama'],
                    'prodi_id' => $tenaga['prodi_id'],
                ]);
            }
        }

        // Tambahkan Riwayat
        $user = Auth::user();
        $ajuan = $pkm->ajuanSurat;
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

        return redirect()->route('pkm.index')->with('success', 'Data Berhasil di Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pkm = Pkm::findOrFail($id);
        // Simpan referensi sebelum dihapus
        $ajuan = $pkm->ajuanSurat;
        // Catat ke riwayat
        Riwayat::create([
            'user_id' => Auth::id(), // siapa yang menghapus
            'ajuan_surat_id' => $ajuan->id,
            'aksi' => 'Menghapus Surat Tugas Pengabdian',
            'catatan' => 'Data Pengabdian dengan judul "' . $pkm->judul . '" telah dihapus.',
            'waktu_perubahan' => now(),
        ]);
        $pkm->delete();
        return redirect()->route('pkm.index')->with('success', 'Data Berhasil di Hapus');
    }

    //pkm controller
    public function verifikasiPkmView()
    {
        $pkm = Pkm::get();
        $today = date('Y-m-d');
        $title = 'Surat PKM';
        return view('user.admin.pkm.index', compact('pkm', 'today', 'title'));
    }

    public function verifikasiPkmEdit($id)
    {

        $pkm = Pkm::findOrFail($id);
        $kodeSurats = KodeSurat::all();
        $title = 'Edit Surat PKM';
        return view('user.admin.pkm.edit', compact('pkm', 'title', 'kodeSurats'));
    }

    public function verifikasiPkmUpdate(Request $request, string $id)
    {
        $pkm = Pkm::findOrFail($id);
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

                        $max = ValidasiNomorSuratHelper::maxNomorTerakhir($request->kode_surat_id, $id, 'pkm') ?? 0;

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
        // Simpan ke pkm
        $pkm->update([
            'kode_surat_id' => $request->kode_surat_id,
            'nomorSurat' => $request->nomorSurat,
        ]);

        // Update status hanya di tabel ajuan_surats
        $ajuan = $pkm->ajuanSurat;
        $ajuan->update([
            'status' => $request->status,
        ]);


        // Simpan data ke tabel verifikasi
        Verifikasi::create([
            'ajuan_surat_id' => $pkm->ajuan_surat_id,
            'petugas_id' => Auth::id(), // user yang sedang login (harus petugas)
            'verified_at' => now(),
        ]);

        //simpan riwayat
        Riwayat::create([
            'user_id' => Auth::id(),
            'ajuan_surat_id' => $pkm->ajuan_surat_id,
            'aksi' => 'Verifikasi Surat HKI',
            'waktu_perubahan' => now(),
            'catatan' => 'Status diubah menjadi: ' . ucfirst($status),
        ]);
        // Cek jika status "disetujui" dan kirim WA ke ketua
        if ($status === 'disetujui') {
            if ($ketua && $ketua->nomor_telepon) {
                $message = View::make('user.notif.whatsapp', [
                    'judul_notifikasi' => 'Surat Tugas Pengabdian Telah Disetujui',
                    'data' => [
                        'Judul'   => $pkm->judul,
                        'Pemohon' => $pkm->user->name ?? 'N/A',
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
                    'judul_notifikasi' => 'Surat Tugas Pengabdian Dapat Diambil',
                    'data' => [
                        'Judul'   => $pkm->judul,
                        'Pemohon' => $pkm->user->name ?? 'N/A',
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
        return redirect()->route('admin.pkmView')->with('success', 'Data berhasil diupdate');
    }
    //end pkm controller

    public function downloadWord(string $id)
    {
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor('suratPkm.docx');

        $pkm = Pkm::findOrFail($id);

        $bulan = Carbon::parse($pkm->bulanPelaksanaan)->translatedFormat('F Y');
        $akhirBulan = Carbon::parse($pkm->bulanAkhirPelaksanaan)->translatedFormat('F Y');
        $year = Carbon::parse($pkm->tanggal)->translatedFormat('Y');
        $formattedDate = Carbon::parse($pkm->tanggal)->translatedFormat('j F Y');

        // Data Anggota dalam Bentuk Array
        $anggotaData = [];
        foreach ($pkm->anggota as $index => $anggota) {
            $anggotaData[] = [
                'no_anggota' => $index + 1,
                'nama_anggota' => $anggota->nama,
                'bidang_studi' => $anggota->prodi->nama ?? '-',
            ];
        }

        // Data Tenaga Pembantu dalam Bentuk Array
        $tenagaData = [];
        foreach ($pkm->tenagaPembantu as $index => $tenaga) {
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

        $tandaTanganPath = public_path('storage/' . $pkm->tanda_tangan);

        if (file_exists($tandaTanganPath)) {
            $phpWord->setImageValue('tanda_tangan_ketua', [
                'path' => $tandaTanganPath,
                'width' => 150,
                'height' => 80,
                'ratio' => true,
            ]);
        }

        $fileName = 'Surat_PKM_' . $pkm->namaKetua . '.docx';
        $phpWord->saveAs($fileName);

        // Return the file for download
        return response()->download($fileName)->deleteFileAfterSend(true);
    }
}
