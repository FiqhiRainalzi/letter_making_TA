<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Prodi;
use App\Models\Ketpub;
use GuzzleHttp\Client;
use App\Models\Penulis;
use App\Models\Riwayat;
use App\Models\KodeSurat;
use App\Models\AjuanSurat;
use App\Models\Verifikasi;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Helpers\ValidasiNomorSuratHelper;

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
        //menghitung hari proses pengajuan
        $ketpub->transform(function ($item) {
            if (in_array($item->statusSurat, ['approved', 'ready_to_pickup', 'picked_up', 'rejected'])) {
                $item->lama_proses = Carbon::parse($item->created_at)->diffInDays(Carbon::parse($item->updated_at));
            } else {
                $item->lama_proses = Carbon::parse($item->created_at)->diffInDays(Carbon::now());
            }
            return $item;
        });
        return view('user.dosen.ketpub.index', compact('ketpub', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Surat Publikasi';
        $prodis = Prodi::all();
        return view('user.dosen.ketpub.create', compact('title', 'prodis'));
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
            'edisi'         => 'required'
        ]);

        $user = Auth::user();

        $ajuan = AjuanSurat::create([
            'user_id' => Auth::id(),
            'jenis_surat' => 'ketpub',
            'status' => 'diajukan',
        ]);

        $ketpub = Ketpub::create([
            'kategori_publikasi'    => $request->kategori_publikasi,
            'judul'          => $request->judul,
            'namaPenerbit'   => $request->namaPenerbit,
            'penerbit'       => $request->penerbit,
            'jilid'          => $request->jilid,
            'edisi'          => $request->edisi,
            'bulan'          => $request->bulan,
            'tahun'          => $request->tahun,
            'issn'           => $request->issn,
            'tanggal'        => $request->tanggal,
            'ajuan_surat_id' => $ajuan->id,
            'user_id'       => $user->id // Otomatis mengisi user_id dengan ID user yang sedang login

        ]);

        // Simpan penulis
        foreach ($request->penulis as $penulis) {
            if (!empty($penulis['nama'])) {
                $ketpub->penulis()->create([
                    'nama' => $penulis['nama'],
                    'prodi_id' => $penulis['prodi_id'],
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
        $ketpub = Ketpub::latest()->first();
        $admins = User::where('role', 'admin')->get();
        $client = new Client();
        // Siapkan datanya untuk message
        $message = View::make('user.notif.whatsapp', [
            'judul_notifikasi' => 'Surat Tugas Keterangan Publikasi Baru Saja Dibuat',
            'data' => [
                'Judul'   => $ketpub->judul,
                'Pemohon' => $ketpub->user->name ?? 'N/A',
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

        return redirect()->route('ketpub.index')->with(['success' => 'Data berhasil Disimpan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $ketpub = Ketpub::findOrFail($id);
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
        $prodis = Prodi::all();
        $title = 'Edit Surat Publikasi';
        return view('user.dosen.ketpub.edit', compact('ketpub', 'title', 'prodis'));
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

        // Hapus semua penulis dan tenaga pembantu yang terkait
        $ketpub->penulis()->delete();

        // Simpan penulis ketp$ketpub yang baru
        foreach ($request->penulis as $penulis) {
            if (!empty($penulis['nama'])) {
                $ketpub->penulis()->create([
                    'nama' => $penulis['nama'],
                    'prodi_id' => $penulis['prodi_id'],
                ]);
            }
        }

        // Tambahkan Riwayat
        $user = Auth::user();
        $ajuan = $ketpub->ajuanSurat;
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

        return redirect()->route('ketpub.index')->with('success', 'Data Berhasil di Update');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ketpub = Ketpub::findOrFail($id);
        // Simpan referensi sebelum dihapus
        $ajuan = $ketpub->ajuanSurat;
        // Catat ke riwayat
        Riwayat::create([
            'user_id' => Auth::id(), // siapa yang menghapus
            'ajuan_surat_id' => $ajuan->id,
            'aksi' => 'Menghapus Surat Keterangan Publikasi',
            'catatan' => 'Data Keterangan Publikasi dengan judul "' . $ketpub->judul . '" telah dihapus.',
            'waktu_perubahan' => now(),
        ]);
        $ketpub->delete();
        return redirect()->route('ketpub.index')->with('success', 'Data Berhasil di Hapus');
    }

      //ketpub controller
    public function verifikasiKetpubView()
    {
        $ketpub = Ketpub::with('penulis')->get();
        $today = date('Y-m-d');
        $title = 'Surat Keterangan Publikasi ';
        return view('user.admin.ketpub.index', compact('ketpub', 'today', 'title'));
    }

    public function verifikasiKetpubEdit($id)
    {
        $ketpub = Ketpub::findOrFail($id);
        $kodeSurats = KodeSurat::all();
        $title = 'Edit Surat Keterangan Publikasi';
        return view('user.admin.ketpub.edit', compact('ketpub', 'title', 'kodeSurats'));
    }

    public function verifikasiKetpubUpdate(Request $request, string $id)
    {
        $ketpub = Ketpub::findOrFail($id);
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

                        $max = ValidasiNomorSuratHelper::maxNomorTerakhir($request->kode_surat_id, $id, 'ketpub') ?? 0;

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

        // Simpan ke ketpub
        $ketpub->update([
            'kode_surat_id' => $request->kode_surat_id,
            'nomorSurat' => $request->nomorSurat,
        ]);

        // Simpan data ke tabel verifikasi
        Verifikasi::create([
            'ajuan_surat_id' => $ketpub->ajuan_surat_id,
            'petugas_id' => Auth::id(), // user yang sedang login (harus petugas)
            'verified_at' => now(),
        ]);

        // Update status hanya di tabel ajuan_surats
        $ajuan = $ketpub->ajuanSurat;
        $ajuan->update([
            'status' => $request->status,
        ]);
        Riwayat::create([
            'user_id' => Auth::id(),
            'ajuan_surat_id' => $ketpub->ajuan_surat_id,
            'aksi' => 'Verifikasi Surat HKI',
            'waktu_perubahan' => now(),
            'catatan' => 'Status diubah menjadi: ' . ucfirst($status),
        ]);
        // Cek jika status "disetujui" dan kirim WA ke ketua
        if ($status === 'disetujui') {
            if ($ketua && $ketua->nomor_telepon) {
                $message = View::make('user.notif.whatsapp', [
                    'judul_notifikasi' => 'Surat Tugas Keterangan Publikasi Telah Disetujui',
                    'data' => [
                        'Judul'   => $ketpub->judul,
                        'Pemohon' => $ketpub->user->name ?? 'N/A',
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
                    'judul_notifikasi' => 'Surat Tugas Keterangan Publikasi Dapat Diambil',
                    'data' => [
                        'Judul'   => $ketpub->judul,
                        'Pemohon' => $ketpub->user->name ?? 'N/A',
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
        $ketpub->save();

        return redirect()->route('admin.ketpubView')->with('success', 'Data berhasil diupdate');
    }
    //end ketpub controller
    
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
            $phpWord->setValue("jurusanProdi#{$row}", $penulis->prodi->nama ?: '-');
        }
        $tandaTanganPath = public_path('storage/' . $ketpub->tanda_tangan);

        if (file_exists($tandaTanganPath)) {
            $phpWord->setImageValue('tanda_tangan_ketua', [
                'path' => $tandaTanganPath,
                'width' => 150,
                'height' => 80,
                'ratio' => true,
            ]);
        }

        $fileName = 'Surat_Keterangan_Publik_' . $ketpub->penulis1 . '.docx';
        $phpWord->saveAs($fileName);

        // Return the file for download
        return response()->download($fileName)->deleteFileAfterSend(true);
    }
}
