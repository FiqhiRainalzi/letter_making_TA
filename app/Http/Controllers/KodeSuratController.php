<?php

namespace App\Http\Controllers;

use App\Models\KodeSurat;
use Illuminate\Http\Request;

class KodeSuratController extends Controller
{
    public function index()
    {
        $title = 'Kode Surat';
        $kodesurat = KodeSurat::latest()->get();
        return view('user.admin.kode_surat.index', compact('kodesurat', 'title'));
    }

    public function create()
    {
        $title = 'Kode Surat';
        return view('user.admin.kode_surat.create', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_instansi' => 'required|string',
            'kode_layanan' => 'required|string',
        ]);

        KodeSurat::create($request->only(['kode_instansi', 'kode_layanan']));

        return redirect()->route('kode_surat.index')->with('success', 'Kode Surat berhasil ditambahkan.');
    }

    public function edit(KodeSurat $kode_surat)
    {
        $title = 'Kode Surat';
        return view('user.admin.kode_surat.edit', compact('kode_surat', 'title'));
    }

    public function update(Request $request, KodeSurat $kode_surat)
    {
        $request->validate([
            'kode_instansi' => 'required|string',
            'kode_layanan' => 'required|string',
        ]);

        $kode_surat->update($request->only(['kode_instansi', 'kode_layanan']));

        return redirect()->route('kode_surat.index')->with('success', 'Kode Surat berhasil diperbarui.');
    }

    public function destroy(KodeSurat $kode_surat)
    {
        $kode_surat->delete();
        return redirect()->route('kode_surat.index')->with('success', 'Kode Surat berhasil dihapus.');
    }
}
