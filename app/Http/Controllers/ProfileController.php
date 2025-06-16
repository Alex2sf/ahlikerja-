<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Post;
use App\Models\User;
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
        $user = Auth::user();
        $profile = $user->profile;

        // Periksa apakah profil sudah lengkap
        if (!$this->isProfileComplete($user)) {
            return redirect()->route('profile.edit')->with('info', 'Kamu harus mengisi profil dahulu sebelum melihat profil.');
        }

        // Ambil postingan pengguna yang login
        $posts = Post::with(['likes', 'comments'])
                     ->where('user_id', $user->id)
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view('profile.show', compact('profile', 'posts'));
    }

    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile();

        // Jika profil belum ada email, gunakan email dari tabel users
        if (!$profile->email) {
            $profile->email = $user->email;
        }

        return view('profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile();

        $request->validate([
            'foto_profile' => 'nullable|image|max:2048',
            'nama_lengkap' => 'required|string|max:255',
            'nama_panggilan' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'alamat_lengkap' => 'required|string',
            'nomor_telepon' => 'required|string|max:15|regex:/^[0-9]+$/', // Hanya angka, tanpa tanda - atau string
            'email' => 'required|email|max:255|unique:profiles,email,' . ($profile->id ?? 'NULL'),
            'media_sosial' => 'required|array|min:1', // Minimal 1 media sosial wajib
            'media_sosial.*' => 'nullable|string|max:255', // Setiap field opsional tapi total minimal 1
            'bio' => 'required|string|max:500',
        ], [
            'nomor_telepon.regex' => 'Nomor telepon hanya boleh berisi angka (tanpa tanda - atau huruf).',
            'media_sosial.required' => 'Setidaknya satu media sosial harus diisi.',
            'media_sosial.min' => 'Setidaknya satu media sosial harus diisi.',
        ]);

        $data = $request->only([
            'nama_lengkap', 'nama_panggilan', 'jenis_kelamin', 'tanggal_lahir',
            'tempat_lahir', 'alamat_lengkap', 'nomor_telepon', 'email', 'bio'
        ]);

        if ($request->hasFile('foto_profile')) {
            if ($profile->foto_profile) {
                Storage::disk('public')->delete($profile->foto_profile);
            }
            $data['foto_profile'] = $request->file('foto_profile')->store('profiles', 'public');
        }

        $data['media_sosial'] = array_filter($request->input('media_sosial', []));

        if ($profile->exists) {
            $profile->update($data);
        } else {
            $data['user_id'] = $user->id;
            Profile::create($data);
        }

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui!');
    }

    public function showPublic($id)
    {
        $user = User::with('profile')->findOrFail($id);
        $profile = $user->profile;

        // Ambil postingan pengguna
        $posts = Post::with(['likes', 'comments'])
                     ->where('user_id', $id)
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view('profile.public', compact('user', 'profile', 'posts'));
    }

    // Helper untuk memeriksa apakah profil sudah lengkap
    public static function isProfileComplete($user)
    {
        $profile = $user->profile;
        return $profile &&
               !empty($profile->nama_lengkap) &&
               !empty($profile->nama_panggilan) &&
               !empty($profile->jenis_kelamin) &&
               !empty($profile->tanggal_lahir) &&
               !empty($profile->tempat_lahir) &&
               !empty($profile->alamat_lengkap) &&
               !empty($profile->nomor_telepon) &&
               !empty($profile->email) &&
               !empty($profile->media_sosial) &&
               !empty($profile->bio);
    }
}
