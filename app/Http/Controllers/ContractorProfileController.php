<?php

namespace App\Http\Controllers;
use App\Models\User;

use App\Models\ContractorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContractorProfileController extends Controller
{

    public function welcome()
{
    // Ambil kontraktor yang sudah disetujui (approved = true)
    $contractors = User::where('role', 'kontraktor')
                       ->with('contractorProfile')
                       ->whereHas('contractorProfile', function ($query) {
                           $query->where('approved', true);
                       })
                       ->get();

    return view('welcome', compact('contractors'));
}
   public function edit()
    {
        $profile = Auth::user()->contractorProfile ?? new ContractorProfile();
        return view('contractor.profile.edit', compact('profile'));
    }

    public function update(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'foto_profile' => 'nullable|image|max:2048',
        'nama_depan' => 'required|string|max:255',
        'nama_belakang' => 'required|string|max:255',
        'nomor_telepon' => 'nullable|string|max:15',
        'alamat' => 'nullable|string',
        'perusahaan' => 'required|string|max:255',
        'nomor_npwp' => 'required|string|max:255',
        'bidang_usaha.*' => 'nullable|string|max:255',
        'dokumen_pendukung.*' => 'nullable|file|max:2048',
        'portofolio.*' => 'nullable|file|max:2048',
        'identity_images.*' => 'nullable|image|max:2048', // Validasi untuk gambar data diri
    ]);

    $data = $request->only([
        'nama_depan', 'nama_belakang', 'nomor_telepon', 'alamat',
        'perusahaan', 'nomor_npwp',
    ]);

    // Handle foto profil
    if ($request->hasFile('foto_profile')) {
        $fotoProfilPath = $request->file('foto_profile')->store('foto_profil', 'public');
        $data['foto_profile'] = $fotoProfilPath;
    }

    // Handle bidang usaha
    $data['bidang_usaha'] = array_filter($request->input('bidang_usaha', []));

    // Handle dokumen pendukung
    if ($request->hasFile('dokumen_pendukung')) {
        $dokumenPendukung = [];
        foreach ($request->file('dokumen_pendukung') as $file) {
            $dokumenPendukung[] = $file->store('contractors/documents', 'public');
        }
        $data['dokumen_pendukung'] = $dokumenPendukung;
    }

    // Handle portofolio
    if ($request->hasFile('portofolio')) {
        $portofolio = [];
        foreach ($request->file('portofolio') as $file) {
            $portofolio[] = $file->store('contractors/portfolios', 'public');
        }
        $data['portofolio'] = $portofolio;
    }

    // Handle identity images (gambar data diri)
    if ($request->hasFile('identity_images')) {
        $identityImages = [];
        foreach ($request->file('identity_images') as $image) {
            $identityImages[] = $image->store('contractors/identity', 'public');
        }
        $data['identity_images'] = $identityImages;
    }

    if ($user->contractorProfile) {
        $user->contractorProfile->update($data);
    } else {
        $data['user_id'] = $user->id;
        ContractorProfile::create($data);
    }

    return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
}
    public function show()
    {
        $profile = Auth::user()->contractorProfile;
        if (!$profile) {
            return redirect()->route('contractor.profile.edit')->with('info', 'Silakan isi profil kontraktor Anda terlebih dahulu.');
        }
        return view('contractor.profile.show', compact('profile'));
    }

    public function showPublic($id)
    {
        $user = \App\Models\User::findOrFail($id);
        if (!$user->contractorProfile) {
            return redirect()->route('home')->with('error', 'Profil kontraktor tidak ditemukan.');
        }
        $profile = $user->contractorProfile;
        return view('contractor.profile.public', compact('profile', 'user'));
    }
    public function index()
    {
        $contractors = \App\Models\User::where('role', 'kontraktor')
                                      ->with('contractorProfile')
                                      ->whereHas('contractorProfile', function ($query) {
                                          $query->where('approved', true);
                                      })
                                      ->get();
        return view('contractor.index', compact('contractors'));
    }
}
