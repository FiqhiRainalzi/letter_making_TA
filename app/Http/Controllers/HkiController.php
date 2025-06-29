<?php

namespace App\Http\Controllers;

use CURLFile;
use Carbon\Carbon;
use App\Models\Hki;
use App\Models\User;
use App\Models\Prodi;
use GuzzleHttp\Client;
use App\Models\Riwayat;
use App\Models\Inventor;
use App\Models\KodeSurat;
use App\Models\AjuanSurat;
use App\Models\Verifikasi;
use App\Models\Notification;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Helpers\ValidasiNomorSuratHelper;

class HkiController extends BaseController
{
    public function index()
    {
        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Mengambil data HKI yang terkait dengan user
        $hki = Hki::where('user_id', $user->id)->with('inventor')->get();
        $title = 'Surat HKI';
        $today = date('Y-m-d');
        //menghitung hari proses pengajuan
        $hki->transform(function ($item) {
            $status = $item->ajuanSurat?->status;
            if (in_array($status, ['disetujui', 'siap diambil', 'sudah diambil', 'sudah ditandatangani'])){
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
        $prodis = Prodi::all();
        return view('user.dosen.hki.create', compact('title', 'prodis'));
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
            'nama' => 'null',
            'prodi_id' => 'null',
        ]);

        $ajuan = AjuanSurat::create([
            'user_id' => Auth::id(),
            'jenis_surat' => 'hki',
            'status' => 'diajukan',
        ]);

        // Simpan data HKI
        $hki = Hki::create([
            'user_id' => Auth::id(),
            'ajuan_surat_id' => $ajuan->id,
            'namaPemegang' => $request->namaPemegang,
            'alamat' => $request->alamat,
            'judul' => $request->judul,
            'tanggal' => $request->tanggal,
            'statusSurat' => 'draft',
            'tanggal'   => $request->tanggal,
        ]);

        // Simpan inventor
        foreach ($request->inventor as $inventor) {
            if (!empty($inventor['nama'])) {
                $hki->inventor()->create([
                    'nama' => $inventor['nama'],
                    'prodi_id' => $inventor['prodi_id'],
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
        $hki = Hki::latest()->first();
        $admins = User::where('role', 'admin')->get();
        $client = new Client();
        Log::info($admins);
        // Siapkan datanya untuk message
        $message = View::make('user.notif.whatsapp', [
            'judul_notifikasi' => 'Surat Tugas HKI Baru Saja Dibuat',
            'data' => [
                'Judul'   => $hki->judul,
                'Pemohon' => $hki->user->name ?? 'N/A',
                'Tanggal' => now()->format('d M Y'),
                'Status'  => ucfirst($request->status ?? 'Belum Ditentukan'),
            ],
            'footer' => 'Silakan cek sistem untuk melakukan tanda tangan.',
        ])->render();

        foreach ($admins as $admin) {
            Log::info($admin);
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
        return redirect()->route('hki.index')->with(['success' => 'Data berhasil disimpan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Muat relasi 'user' dan 'inventors'
        $hki = Hki::with('kodeSurat')->findOrFail($id);
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
            'tanggal' => $request->tanggal,
        ]);
        // Tambahkan Riwayat
        $user = Auth::user();
        $ajuan = $hki->ajuanSurat;
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

        return redirect()->route('hki.index')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Temukan dan hapus data
        $hki = Hki::findOrFail($id);
        // Simpan referensi sebelum dihapus
        $ajuan = $hki->ajuanSurat;
        // Catat ke riwayat
        Riwayat::create([
            'user_id' => Auth::id(), // siapa yang menghapus
            'ajuan_surat_id' => $ajuan->id,
            'aksi' => 'Menghapus Surat Tugas HKI',
            'catatan' => 'Data HKI dengan judul "' . $hki->judul . '" telah dihapus.',
            'waktu_perubahan' => now(),
        ]);
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

    //hki controller
    public function verifikaiHkiView()
    {
        $hki = Hki::get();
        $today = date('Y-m-d');
        $title = 'Surat HKI';
        //menghitung hari proses pengajuan
        $hki->transform(function ($item) {
            if (in_array($item->statusSurat, ['approved', 'ready_to_pickup', 'picked_up', 'rejected'])) {
                $item->lama_proses = Carbon::parse($item->created_at)->diffInDays(Carbon::parse($item->updated_at));
            } else {
                $item->lama_proses = Carbon::parse($item->created_at)->diffInDays(Carbon::now());
            }
            return $item;
        });
        return view('user.admin.hki.index', compact('hki', 'today', 'title'));
    }

    public function verifikaiHkiEdit($id)
    {
        $hki = Hki::findOrFail($id);
        $kodeSurats = KodeSurat::all();
        $title = 'Edit Surat HKI';

        return view('user.admin.hki.edit', compact('hki', 'title', 'kodeSurats'));
    }

    public function verifikaiHkiUpdate(Request $request, string $id)
    {
        $hki = Hki::findOrFail($id);
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

                        $max = ValidasiNomorSuratHelper::maxNomorTerakhir($request->kode_surat_id, $id, 'hki');

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

        // UPDATE DATA HKI
        $hki->update([
            'kode_surat_id' => $request->kode_surat_id,
            'nomorSurat' => $request->nomorSurat,
        ]);

        // UPDATE STATUS DI AJUAN_SURAT
        $ajuan = $hki->ajuanSurat;
        $ajuan->update([
            'status' => $request->status,
        ]);


        // Simpan data ke tabel verifikasi
        Verifikasi::create([
            'ajuan_surat_id' => $hki->ajuan_surat_id,
            'petugas_id' => Auth::id(), // user yang sedang login (harus petugas)
            'verified_at' => now(),
        ]);

        //simpan riwayat
        Riwayat::create([
            'user_id' => Auth::id(),
            'ajuan_surat_id' => $hki->ajuan_surat_id,
            'aksi' => 'Verifikasi Surat HKI',
            'waktu_perubahan' => now(),
            'catatan' => 'Status diubah menjadi: ' . ucfirst($status),
        ]);

        // Cek jika status "disetujui" dan kirim WA ke ketua
        if ($status === 'disetujui') {
            if ($ketua && $ketua->nomor_telepon) {
                $message = View::make('user.notif.whatsapp', [
                    'judul_notifikasi' => 'Surat Tugas HKI Telah Disetujui',
                    'data' => [
                        'Judul'   => $hki->judul,
                        'Pemohon' => $hki->user->name ?? 'N/A',
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
                    'judul_notifikasi' => 'Surat Tugas HKI Dapat Diambil',
                    'data' => [
                        'Judul'   => $hki->judul,
                        'Pemohon' => $hki->user->name ?? 'N/A',
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

        return redirect()->route('admin.hkiView')->with('success', 'Surat HKI berhasil diverifikasi.');
    }
    //end hki controller

    public function downloadWord($id)
    {
        // Load template Word
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor('suratHki.docx');

        // Ambil data HKI beserta relasi inventors
        $hki = Hki::with('inventor', 'kodeSurat')->findOrFail($id);

        // Format tanggal
        $formattedDate = Carbon::parse($hki->tanggalPemHki)->translatedFormat('j F Y');

        // Set data utama di Word
        $phpWord->setValues([
            'namaPemHki'   => $hki->namaPemegang,
            'nomorSurat'   => $hki->nomorSurat ?: '-',
            'alamatPemHki' => $hki->alamat ?: '-',
            'judulInvensi' => $hki->judul ?: '-',
            'tanggalPemHki' => $formattedDate,
        ]);

        //nomor urut
        $nomorUrut = $hki->nomorSurat ?? 'XXX'; // fallback kalau null
        //kode surat
        $kodeInstansi = $hki->kodeSurat->kode_instansi ?? 'INST';
        $kodeLayanan = $hki->kodeSurat->kode_layanan ?? 'LAY';
        //YEAR
        $year = Carbon::parse($hki->tanggalPemHki)->translatedFormat('Y');
        // nomor surat lengkap
        $kodeSuratLengkap = "$nomorUrut/$kodeInstansi/$kodeLayanan/$year";
        //set value nomor surat lengkap
        $phpWord->setValue('kodeSurat', $kodeSuratLengkap);

        // Hitung jumlah inventor
        $inventors = $hki->inventor;

        // Clone row untuk data inventor
        $phpWord->cloneRow('no', $inventors->count());

        foreach ($inventors as $index => $inventor) {
            $row = $index + 1;

            $phpWord->setValue("no#{$row}", $row);
            $phpWord->setValue("namaInventor#{$row}", $inventor->nama ?: '-');
            $phpWord->setValue("bidangStudi#{$row}", $inventor->prodi ? $inventor->prodi->nama : '-');
        }
        $tandaTanganPath = public_path('storage/' . $hki->tanda_tangan);

        if (file_exists($tandaTanganPath)) {
            $phpWord->setImageValue('tanda_tangan_ketua', [
                'path' => $tandaTanganPath,
                'width' => 150,
                'height' => 80,
                'ratio' => true,
            ]);
        }
        // Simpan dan download file
        $fileName = 'Surat_HKI_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $hki->namaPemHki) . '.docx';
        $phpWord->saveAs($fileName);

        return response()->download($fileName)->deleteFileAfterSend(true);
    }
}
