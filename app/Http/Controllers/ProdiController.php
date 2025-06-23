<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdiController extends BaseController
{
    public function index()
    {
        $prodi = Prodi::get();
        $title = 'Program Studi';
        return view('user.admin.prodi.index', compact('title', 'prodi'));
    }

    public function create()
    {
        $title = 'Tambah Program Studi';
        return view('user.admin.prodi.create', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'    => 'required',
        ]);

        // Simpan data HKI
        Prodi::create([
            'nama'    => $request->nama,
        ]);

        return redirect()->route('prodi.index')->with(['success' => 'Data berhasil disimpan']);
    }

    public function edit(string $id)
    {
        $title = 'Edit Program Studi';
        $prodi = Prodi::findOrFail($id);
        return view('user.admin.prodi.edit', compact('prodi', 'title'));
    }

    public function update(Request $request, string $id)
    {
        $prodi = Prodi::findOrFail($id);
        $request->validate([
            'nama'    => 'required',
        ]);

        // Simpan data HKI
        $prodi->update($request->all());

        return redirect()->route('prodi.index')->with(['success' => 'Data berhasil diupdate']);
    }

    public function destroy(string $id)
    {
        $prodi = Prodi::findOrFail($id);
        $prodi->delete();

        return redirect()->route('prodi.index')->with('success', 'Data Berhasil di Hapus');
    }
}
