<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Prodi;
use GuzzleHttp\Client;
use App\Models\Penulis;
use App\Models\Riwayat;
use App\Models\Tugaspub;
use App\Models\KodeSurat;
use App\Models\AjuanSurat;
use App\Models\Verifikasi;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Helpers\ValidasiNomorSuratHelper;

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
        //menghitung hari proses pengajuan
        $tugaspub->transform(function ($item) {
            if (in_array($item->statusSurat, ['approved', 'ready_to_pickup', 'picked_up', 'rejected'])) {
                $item->lama_proses = Carbon::parse($item->created_at)->diffInDays(Carbon::parse($item->updated_at));
            } else {
                $item->lama_proses = Carbon::parse($item->created_at)->diffInDays(Carbon::now());
            }
            return $item;
        });
        return view('user.dosen.tugaspub.index', compact('tugaspub', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Surat Tugas Publikasi';
        $prodis = Prodi::all();
        return view('user.dosen.tugaspub.create', compact('title', 'prodis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'namaPublikasi' => 'required',
            'kategori_jurnal' => 'required',
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

        $ajuan = AjuanSurat::create([
            'user_id' => Auth::id(),
            'jenis_surat' => 'tugaspub',
            'status' => 'diajukan',
        ]);

        $tugaspub = Tugaspub::create([
            'namaPublikasi' => $request->namaPublikasi,
            'kategori_jurnal' => $request->kategori_jurnal,
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
            'ajuan_surat_id' => $ajuan->id,
            'user_id'       => $user->id // Otomatis mengisi user_id dengan ID user yang sedang login

        ]);

        // Simpan penulis
        foreach ($request->penulis as $penulis) {
            if (!empty($penulis['nama'])) {
                $tugaspub->penulis()->create([
                    'nama' => $penulis['nama'],
                    'prodi_id' => $penulis['prodi_id'],
                ]);
            }
        }

        // Tambahkan ke tabel riwayat surat
        Riwayat::create([
            'user_id' => Auth::id(), // <- WAJIB agar masuk ke index dosen
            'ajuan_surat_id' => $ajuan->id,
            'aksi' => 'Mengajukan Surat Tugas Publikasi',
            'diubah_oleh' => $user->name,
            'catatan' => 'Surat "' . $request->judul . '" diajukan oleh ' . $user->name,
            'waktu_perubahan' => now(),
        ]);


        // Notifikasi
        $tugaspub = Tugaspub::latest()->first();
        $admins = User::where('role', 'admin')->get();
        $client = new Client();
        // Siapkan datanya untuk message
        $message = View::make('user.notif.whatsapp', [
            'judul_notifikasi' => 'Surat Tugas Keterangan Publikasi Baru Saja Dibuat',
            'data' => [
                'Judul'   => $tugaspub->judul,
                'Pemohon' => $tugaspub->user->name ?? 'N/A',
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

        return redirect()->route('tugaspub.index')->with(['success' => 'Data berhasil Disimpan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $tugaspub = Tugaspub::findOrFail($id);
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
        $prodis = Prodi::all();
        $title = 'Edit Surat Tugas Publikasi';
        return view('user.dosen.tugaspub.edit', compact('tugaspub', 'title', 'prodis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'namaPublikasi' => 'required|string',
            'kategori_jurnal' => 'required',
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
            'kategori_jurnal' => $request->kategori_jurnal,
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

        // Hapus semua penulis dan tenaga pembantu yang terkait
        $tugaspub->penulis()->delete();

        // Simpan penulis tugaspub yang baru
        foreach ($request->penulis as $penulis) {
            if (!empty($penulis['nama'])) {
                $tugaspub->penulis()->create([
                    'nama' => $penulis['nama'],
                    'prodi_id' => $penulis['prodi_id'],
                ]);
            }
        }

        // Tambahkan Riwayat
        $user = Auth::user();
        $ajuan = $tugaspub->ajuanSurat;
        if ($ajuan) {
            Riwayat::create([
                'user_id' => Auth::id(), // <- WAJIB agar masuk ke index dosen
                'ajuan_surat_id' => $ajuan->id,
                'aksi' => 'Mengedit Surat Tugas Publikasi',
                'diubah_oleh' => $user->name,
                'catatan' => 'Surat diperbarui oleh ' . $user->name,
                'waktu_perubahan' => now(),
            ]);
        } else {
            Log::warning("AjuanSurat dengan ID $id tidak ditemukan saat ingin membuat riwayat.");
        }


        return redirect()->route('tugaspub.index')->with('success', 'Data Berhasil di Update');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tugaspub = Tugaspub::findOrFail($id);
        $user = Auth::user();
        // Ambil ajuan surat terkait
        $ajuan = $tugaspub->ajuanSurat;
        // Tambahkan log sebelum data dihapus
        if ($ajuan) {
            Riwayat::create([
                'user_id' => Auth::id(), // <- WAJIB agar masuk ke index dosen
                'ajuan_surat_id' => $ajuan->id,
                'aksi' => 'Menghapus Surat Tugas Publikasi',
                'diubah_oleh' => $user->name,
                'catatan' => 'Surat dengan judul "' . $tugaspub->judul . '" dihapus oleh ' . $user->name,
                'waktu_perubahan' => now(),
            ]);
        }
        $tugaspub->delete();

        return redirect()->route('tugaspub.index')->with('success', 'Data Berhasil di Hapus');
    }

     //tugaspub controller
    public function verifikasiTugaspubView()
    {
        $tugaspub = Tugaspub::get();
        $today = date('Y-m-d');
        $title = 'Surat Tugas Publikasi';
        return view('user.admin.tugaspub.index', compact('tugaspub', 'today', 'title'));
    }

    public function verifikasiTugaspubEdit($id)
    {
        $tugaspub = tugaspub::findOrFail($id);
        $kodeSurats = KodeSurat::all();
        $title = 'Edit Surat Tugas Publikasi';
        return view('user.admin.tugaspub.edit', compact('tugaspub', 'title', 'kodeSurats'));
    }

    public function verifikasiTugaspubUpdate(Request $request, string $id)
    {
        $tugaspub = Tugaspub::findOrFail($id);
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

                        $max = ValidasiNomorSuratHelper::maxNomorTerakhir($request->kode_surat_id, $id, 'tugaspub') ?? 0;

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

        // Simpan ke tugaspub
        $tugaspub->update([
            'kode_surat_id' => $request->kode_surat_id,
            'nomorSurat' => $request->nomorSurat,
        ]);
        // Simpan data ke tabel verifikasi
        Verifikasi::create([
            'ajuan_surat_id' => $tugaspub->ajuan_surat_id,
            'petugas_id' => Auth::id(), // user yang sedang login (harus petugas)
            'verified_at' => now(),
        ]);

        // Update status hanya di tabel ajuan_surats
        $ajuan = $tugaspub->ajuanSurat;
        $ajuan->update([
            'status' => $request->status,
        ]);
        Riwayat::create([
            'user_id' => Auth::id(),
            'ajuan_surat_id' => $tugaspub->ajuan_surat_id,
            'aksi' => 'Verifikasi Surat HKI',
            'waktu_perubahan' => now(),
            'catatan' => 'Status diubah menjadi: ' . ucfirst($status),
        ]);

        // Cek jika status "disetujui" dan kirim WA ke ketua
        if ($status === 'disetujui') {
            if ($ketua && $ketua->nomor_telepon) {
                $message = View::make('user.notif.whatsapp', [
                    'judul_notifikasi' => 'Surat Tugas Publikasi Telah Disetujui',
                    'data' => [
                        'Judul'   => $tugaspub->judul,
                        'Pemohon' => $tugaspub->user->name ?? 'N/A',
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
                    'judul_notifikasi' => 'Surat Tugas Publikasi Dapat Diambil',
                    'data' => [
                        'Judul'   => $tugaspub->judul,
                        'Pemohon' => $tugaspub->user->name ?? 'N/A',
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
        return redirect()->route('admin.tugaspubView')->with('success', 'Data berhasil diupdate');
    }
    //end tugaspub controller
    
    public function downloadWord(string $id)
    {
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor('suratTugasPub.docx');

        $tugaspub = Tugaspub::findOrFail($id);
        $formattedDate = Carbon::parse($tugaspub->tanggal)->translatedFormat('j F Y');
        $year = Carbon::parse($tugaspub->tanggal)->translatedFormat('Y');

        $phpWord->setValues([
            'nomorSurat' => $tugaspub->nomorSurat ?: '-',
            'namaPublikasi' => $tugaspub->namaPublikasi,
            'kategori_jurnal' => $tugaspub->kategori_jurnal,
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
            $phpWord->setValue("jurusanProdi#{$row}", $penulis->prodi->nama ?: '-');
        }

        $tandaTanganPath = public_path('storage/' . $tugaspub->tanda_tangan);

        if (file_exists($tandaTanganPath)) {
            $phpWord->setImageValue('tanda_tangan_ketua', [
                'path' => $tandaTanganPath,
                'width' => 150,
                'height' => 80,
                'ratio' => true,
            ]);
        }

        $fileName = 'Surat_Tugas_Publikasi_' . $tugaspub->namaPenulis1 . '.docx';
        $phpWord->saveAs($fileName);

        // Return the file for download
        return response()->download($fileName)->deleteFileAfterSend(true);
    }
}
