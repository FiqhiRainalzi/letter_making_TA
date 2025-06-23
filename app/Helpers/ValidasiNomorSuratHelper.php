<?php

namespace App\Helpers;

use App\Models\Hki;
use App\Models\Ketpub;
use App\Models\Penelitian;
use App\Models\Pkm;
use App\Models\Tugaspub;

class ValidasiNomorSuratHelper
{
    public static function isDipakai($kodeSuratId, $nomorSurat, $excludeId = null)
    {
        return
            Hki::where('kode_surat_id', $kodeSuratId)
            ->where('nomorSurat', $nomorSurat)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists()
            ||
            Penelitian::where('kode_surat_id', $kodeSuratId)
            ->where('nomorSurat', $nomorSurat)
            ->exists()
            ||
            Pkm::where('kode_surat_id', $kodeSuratId)
            ->where('nomorSurat', $nomorSurat)
            ->exists()
            ||
            Tugaspub::where('kode_surat_id', $kodeSuratId)
            ->where('nomorSurat', $nomorSurat)
            ->exists()
            ||
            Ketpub::where('kode_surat_id', $kodeSuratId)
            ->where('nomorSurat', $nomorSurat)
            ->exists();
    }

    public static function maxNomorTerakhir($kodeSuratId, $excludeId = null, $tipe = null)
    {
        // Ambil nomor max dari HKI
        $queryHki = Hki::where('kode_surat_id', $kodeSuratId);
        if ($excludeId && $tipe === 'hki') {
            $queryHki->where('id', '!=', $excludeId);
        }
        $maxHki = $queryHki->max('nomorSurat') ?? 0;

        // Ambil nomor max dari Penelitian
        $queryPenelitian = Penelitian::where('kode_surat_id', $kodeSuratId);
        if ($excludeId && $tipe === 'penelitian') {
            $queryPenelitian->where('id', '!=', $excludeId);
        }
        $maxPenelitian = $queryPenelitian->max('nomorSurat') ?? 0;

        // Ambil nomor max dari Pengabdian
        $queryPengabdian = Pkm::where('kode_surat_id', $kodeSuratId);
        if ($excludeId && $tipe === 'pengabdian') {
            $queryPengabdian->where('id', '!=', $excludeId);
        }
        $maxPengabdian = $queryPengabdian->max('nomorSurat') ?? 0;

        // Ambil nomor max dari Tugaspub
        $queryTugaspub = Tugaspub::where('kode_surat_id', $kodeSuratId);
        if ($excludeId && $tipe === 'Tugaspub') {
            $queryTugaspub->where('id', '!=', $excludeId);
        }
        $maxTugaspub = $queryTugaspub->max('nomorSurat') ?? 0;

        // Ambil nomor max dari Ketpub
        $queryKetpub = Ketpub::where('kode_surat_id', $kodeSuratId);
        if ($excludeId && $tipe === 'Ketpub') {
            $queryKetpub->where('id', '!=', $excludeId);
        }
        $maxKetpub = $queryKetpub->max('nomorSurat') ?? 0;

        // Ambil nilai paling besar dari ketiganya
        return max([
            intval($maxHki),
            intval($maxPenelitian),
            intval($maxPengabdian),
            intval($maxTugaspub),
            intval($maxKetpub),
        ]);
    }
}
