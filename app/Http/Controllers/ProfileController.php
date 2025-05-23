<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil pengguna.
     *
     * @return \Illuminate\View\View
     */
    public function showProfileForm()
    {
        return view('accounts.profile', ['user' => Auth::user()]);
    }

    /**
     * Perbarui informasi profil pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
        ]);

        $user = User::find(Auth::id());
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);


        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Menampilkan halaman formulir ganti kata sandi.
     *
     * @return \Illuminate\View\View
     */
    public function showChangePasswordForm()
    {
        return view('accounts.password');
    }

    /**
     * Memproses pembaruan kata sandi pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        // Validasi input dari pengguna
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Ambil data pengguna yang sedang login
        $user = User::find(Auth::id());

        // Periksa apakah kata sandi lama sesuai dengan yang ada di database
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Kata sandi lama tidak sesuai.');
        }

        // Update kata sandi baru ke dalam database setelah di-hash
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        // Redirect kembali dengan pesan sukses
        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }
}
