<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DosenController extends BaseController
{
    public function profilView(string $id)
    {
        $user = User::findOrFail($id);
        $title = 'Profil';
        return view('user.dosen.profil.index', compact('user', 'title'));
    }

    public function updateProfile(Request $request, string $id)
    {
        $user = User::find($id);

        //Validasi input
        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id), // Abaikan email pengguna saat ini
            ],
            'name' => 'min:8'
        ]);

        //update profil
        $user->update($request->all());

        // Redirect dengan pesan sukses
        return back()->with('success', 'Password berhasil diubah.');
    }

    public function updatePassword(Request $request, string $id)
    {

        // Validasi input
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'renew_password' => 'required|same:new_password',
        ]);

        // Mendapatkan pengguna yang sedang login
        $user = User::find($id);

        // Verifikasi kata sandi saat ini
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Redirect dengan pesan sukses
        return back()->with('success', 'Password berhasil diubah.');
    }
}
