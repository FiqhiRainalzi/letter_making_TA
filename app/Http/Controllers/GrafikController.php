<?php

namespace App\Http\Controllers;

use App\Models\Ketpub;
use Illuminate\Http\Request;

class GrafikController extends Controller
{
    public function grafikPublikasi()
    {
        // Ambil data publikasi dengan kategori, prodi, dan jumlah anggota
        $data = Ketpub::with(['penulis'])
            ->get()
            ->map(function ($ketpub) {
                return [
                    'prodi' => $ketpub->penulis->first()->jurusan_prodi ?? 'Tidak Diketahui', // Ambil prodi dari penulis pertama
                    'kategori_publikasi' => $ketpub->kategori_publikasi,
                    'jumlah_anggota' => $ketpub->penulis->count(), // Hitung jumlah penulis
                ];
            });

        // Format data untuk grafik
        $grafikData = [
            'prodi' => $data->groupBy('prodi')->map->count(),
            'kategori_publikasi' => $data->groupBy('kategori_publikasi')->map->count(),
            'anggota' => $data->pluck('jumlah_anggota')->sum(),
        ];

        // Kirim data ke view
        return view('grafik.publikasi', ['grafikData' => $grafikData]);
    }
}
