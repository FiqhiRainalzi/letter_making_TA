<?php

namespace App\Http\Controllers;

use App\Models\Hki;
use App\Models\Pkm;
use App\Models\User;
use App\Models\Ketua;
use App\Models\Ketpub;
use GuzzleHttp\Client;
use App\Models\Riwayat;
use App\Models\Inventor;
use App\Models\Tugaspub;
use App\Models\KodeSurat;
use App\Models\AjuanSurat;
use App\Models\Penelitian;
use App\Models\Verifikasi;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Helpers\KodeSuratHelper;
use App\Helpers\NomorSuratHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Helpers\ValidasiNomorSuratHelper;
use marineusde\LarapexCharts\LarapexChart;
use marineusde\LarapexCharts\Charts\BarChart;

class AdminController extends BaseController
{
    public function home()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $countHki = Hki::count();
            $countKetPub = Ketpub::count();
            $countPenelitian = Penelitian::count();
            $countPkm = Pkm::count();
            $countTugaspub = Tugaspub::count();
        } else {
            $countHki = Hki::where('user_id', $user->id)->count();
            $countKetPub = Ketpub::where('user_id', $user->id)->count();
            $countPenelitian = Penelitian::where('user_id', $user->id)->count();
            $countPkm = Pkm::where('user_id', $user->id)->count();
            $countTugaspub = Tugaspub::where('user_id', $user->id)->count();
        }

        // Grafik jumlah surat per jenis
        $labels = ['hki', 'penelitian', 'pkm', 'tugaspub', 'ketpub'];
        $data = [];

        foreach ($labels as $label) {
            $count = AjuanSurat::where('jenis_surat', strtolower($label))->count();
            $data[] = $count;
        }

        $chart = (new BarChart)
            ->setTitle('Jumlah Surat per Jenis')
            ->setDataset([['name' => 'Total Surat', 'data' => $data]])
            ->setLabels($labels);

        // Grafik jumlah surat per bulan tahun ini
        $bulanLabels = [];
        $dataPerBulan = [];

        for ($i = 1; $i <= 12; $i++) {
            $bulan = Carbon::create()->month($i)->translatedFormat('F');
            $bulanLabels[] = $bulan;

            $jumlah = AjuanSurat::whereYear('created_at', now()->year)
                ->whereMonth('created_at', $i)
                ->count();

            $dataPerBulan[] = $jumlah;
        }

        $chartBulanan = (new BarChart)
            ->setTitle('Jumlah Surat per Bulan (' . now()->year . ')')
            ->setDataset([['name' => 'Total Surat', 'data' => $dataPerBulan]])
            ->setLabels($bulanLabels);

        // Grafik top 5 user terbanyak mengajukan surat
        $topUsers = AjuanSurat::select('user_id', DB::raw('count(*) as total'))
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->with('user') // make sure relasi 'user' ada di model AjuanSurat
            ->take(5)
            ->get();

        $userLabels = $topUsers->pluck('user.name')->toArray();
        $userCounts = $topUsers->pluck('total')->toArray();

        $chartTopUser = (new BarChart)
            ->setTitle('Top 5 Pengaju Surat')
            ->setDataset([['name' => 'Jumlah Surat', 'data' => $userCounts]])
            ->setLabels($userLabels);

        $title = 'Dashboard';

        return view('user.admin.dashboard', compact(
            'countHki',
            'countKetPub',
            'countPenelitian',
            'countPkm',
            'countTugaspub',
            'title',
            'chart',
            'chartBulanan',
            'chartTopUser'
        ));
    }   

    //akun pengguna controller
    public function akunPenggunaView()
    {
        $akunPengguna = User::get();
        $title = 'Akun Pengguna ';
        return view('user.admin.akunPengguna.index', compact('akunPengguna', 'title'));
    }

    public function akunPenggunaCreate()
    {
        $title = 'Tambah Akun Pengguna';
        return view('user.admin.akunPengguna.create', compact('title'));
    }

    public function akunPenggunaStore(Request $request)
    {

        $request->validate([
            'name'    => 'required',
            'email' => 'required|email|min:5|unique:users,email',
            'password'  => 'required|min:8',
            'role' => 'required|min:5',
            'nomor_telepon' => 'required|regex:/^[0-9]{10,15}$/',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'password' => $request->password,
            'role' => $request->role
        ]);
        // Jika role adalah ketua, tambahkan ke tabel ketuas
        if ($request->role === 'ketua') {
            Ketua::create([
                'user_id' => $user->id,
                // kolom lain bisa ditambahkan nanti (misal nama lengkap, nip, dsb)
            ]);
        }
        Riwayat::create([
            'user_id' => Auth::id(), // admin yang sedang login
            'aksi' => 'Menambahkan akun pengguna baru',
            'catatan' => 'Nama: ' . $request->name . ', Role: ' . $request->role,
            'waktu_perubahan' => now(),
        ]);

        return redirect()->route('admin.akunPengguna')->with(['success' => 'Data berhasil Disimpan']);
    }

    public function akunPenggunaEdit(string $id)
    {
        $user = User::findOrFail($id);
        $title = 'Edit Akun Pengguna';
        return view('user.admin.akunPengguna.edit', compact('user', 'title'));
    }

    public function akunPenggunaUpdate(Request $request, string $id)
    {

        $user = User::findOrFail($id);

        $request->validate([
            'name'    => 'required',
            'email' => 'required|email|min:5|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'role' => 'required|min:5',
            'nomor_telepon' => 'required|regex:/^[0-9]{10,15}$/',
        ]);

        // Ambil semua input kecuali password
        $data = $request->only('name', 'email', 'role', 'nomor_telepon');

        // Jika password diisi, hash password baru, jika tidak gunakan password lama
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        } else {
            $data['password'] = $user->password; // Tetap gunakan password lama
        }

        // Update data ke database
        $user->update($data);
        Riwayat::create([
            'user_id' => Auth::id(), // admin yang sedang login
            'aksi' => 'Mengupdate akun pengguna',
            'catatan' => 'Nama: ' . $request->name . ', Role: ' . $request->role,
            'waktu_perubahan' => now(),
        ]);

        return redirect()->route('admin.akunPengguna')->with(['success' => 'Data berhasil Disimpan']);
    }

    public function akunPenggunaDestroy(string $id)
    {
        $user = User::findOrFail($id);
        // Catat ke riwayat dulu sebelum hapus
        Riwayat::create([
            'user_id' => Auth::id(), // admin yang sedang login
            'aksi' => 'Menghapus akun pengguna',
            'catatan' => 'Nama: ' . $user->name . ', Role: ' . $user->role,
            'waktu_perubahan' => now(),
        ]);
        $user->delete();
        return redirect()->route('admin.akunPengguna')->with(['success' => 'Data berhasil Disimpan']);
    }
    //end controller akun pengguna
}
