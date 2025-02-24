<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function __construct()
    {
        // Middleware sudah dipindahkan ke routes, jadi constructor ini bisa kosong
    }

    public function show()
    {
        $profile = Auth::user()->profile;
        if (!$profile) {
            return redirect()->route('profile.edit')->with('info', 'Silakan isi profil Anda terlebih dahulu.');
        }
        return view('profile.show', compact('profile'));
    }

    public function edit()
    {
        $profile = Auth::user()->profile ?? new Profile();
        return view('profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'foto_profile' => 'nullable|image|max:2048',
            'nama_lengkap' => 'required|string|max:255',
            'nama_panggilan' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:laki-laki,perempuan',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir' => 'nullable|string|max:255',
            'alamat_lengkap' => 'nullable|string',
            'nomor_telepon' => 'nullable|string|max:15',
            'email' => 'required|email|max:255|unique:profiles,email,' . ($user->profile->id ?? ''),
            'media_sosial.*' => 'nullable|string|max:255'
        ]);

        $data = $request->only([
            'nama_lengkap', 'nama_panggilan', 'jenis_kelamin', 'tanggal_lahir',
            'tempat_lahir', 'alamat_lengkap', 'nomor_telepon', 'email'
        ]);
        if ($request->hasFile('foto_profile')) {
            $fotoProfilPath = $request->file('foto_profile')->store('foto_profil', 'public');
            $data['foto_profile'] = $fotoProfilPath; // Simpan path ke dalam database
        }
        $data['media_sosial'] = $request->input('media_sosial', []);

        if ($user->profile) {
            $user->profile->update($data);
        } else {
            $data['user_id'] = $user->id;
            Profile::create($data);
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
    public function showPublic($id)
    {
        $user = \App\Models\User::findOrFail($id);
        if ($user->role !== 'user' || !$user->profile) {
            return redirect()->route('home')->with('error', 'Profil tidak ditemukan atau user bukan user biasa.');
        }
        $profile = $user->profile;
        return view('profile.public', compact('profile', 'user'));
    }
}
