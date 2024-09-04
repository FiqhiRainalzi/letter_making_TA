<?php

namespace App\Http\Controllers;

use App\Models\Hki;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HkiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hki = Hki::latest()->paginate(5);
        $today = date('Y-m-d');
        return view('user.dosen.hki.index', compact('today', 'hki'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.dosen.hki.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'namaPemHki'    => 'required',
            'alamatPemHki'  => 'required|min:5',
            'judulInvensi'  => 'required|min:5',
            'namaInventor1' => 'required|min:5',
            'bidangStudi1'  => 'required|min:5'
        ]);

        Hki::create([
            'namaPemHki'    => $request->namaPemHki,
            'alamatPemHki'  => $request->alamatPemHki,
            'judulInvensi'  => $request->judulInvensi,
            'namaInventor1' => $request->namaInventor1,
            'bidangStudi1'  => $request->bidangStudi1,
            'namaInventor2' => $request->namaInventor2,
            'bidangStudi2'  => $request->bidangStudi2,
            'namaInventor3' => $request->namaInventor3,
            'bidangStudi3'  => $request->bidangStudi3,
            'namaInventor4' => $request->namaInventor4,
            'bidangStudi4'  => $request->bidangStudi4,
            'namaInventor5' => $request->namaInventor5,
            'bidangStudi5'  => $request->bidangStudi5,
            'namaInventor6' => $request->namaInventor6,
            'bidangStudi6'  => $request->bidangStudi6,
            'namaInventor7' => $request->namaInventor7,
            'bidangStudi7'  => $request->bidangStudi7,
            'namaInventor8' => $request->namaInventor8,
            'bidangStudi8'  => $request->bidangStudi8,
            'tanggalPemHki' => $request->tanggalPemHki
        ]);

        return redirect()->route('hki.index')->with(['success' => 'Data berhasil Disimpan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $hki = Hki::findOrFail($id);
        return view('user.dosen.hki.show', compact('hki'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $hki = Hki::findOrFail($id);
        return view('user.dosen.hki.edit', compact('hki'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'namaPemHki'    => 'required',
            'alamatPemHki'  => 'required|min:5',
            'judulInvensi'  => 'required|min:5',
            'namaInventor1' => 'required|min:5',
            'bidangStudi1'  => 'required|min:5'
        ]);

        $hki = HKI::find($id);
        $hki->update($request->all());

        return redirect()->route('hki.index')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Temukan dan hapus data
        $hki = Hki::findOrFail($id);
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

    public function downloadWord($id)
    {
        // Load the Word template
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor('suratHki.docx');

        // Retrieve the data using the ID
        $hki = Hki::findOrFail($id);

        //format tanggal
        $formattedDate = Carbon::parse($hki->tanggalPemHki)->translatedFormat('j F Y');

        // Set the values for the placeholders in the template
        $phpWord->setValues([
            'namaPemHki' => $hki->namaPemHki,        // Match this with the placeholder in your Word template
            'alamatPemHki' => $hki->alamatPemHki,
            'judulInvensi' => $hki->judulInvensi,    // Another example of a placeholder
            'namaInventor1' => $hki->namaInventor1,
            'bidangStudi1'  => $hki->bidangStudi1,
            'namaInventor2' => $hki->namaInventor2,
            'bidangStudi2'  => $hki->bidangStudi2,
            'namaInventor3' => $hki->namaInventor3,
            'bidangStudi3'  => $hki->bidangStudi3,
            'namaInventor4' => $hki->namaInventor4,
            'bidangStudi4'  => $hki->bidangStudi4,
            'namaInventor5' => $hki->namaInventor5,
            'bidangStudi5'  => $hki->bidangStudi5,
            'namaInventor6' => $hki->namaInventor6,
            'bidangStudi6'  => $hki->bidangStudi6,
            'tanggalPemHki' => $formattedDate,  // And so on for other fields
            // Add more fields as needed
        ]);

        // Save the modified Word document to download
        $fileName = 'Surat_HKI_' . $hki->namaPemHki . '.docx';
        $phpWord->saveAs($fileName);

        // Return the file for download
        return response()->download($fileName)->deleteFileAfterSend(true);
    }
}
