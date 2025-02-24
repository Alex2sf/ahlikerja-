<?php

namespace App\Http\Controllers;

use App\Models\ContractorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContractorProfileController extends Controller
{
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
            'bidang_usaha.*' => 'nullable|string|max:255', // Validasi untuk array bidang usaha
            'dokumen_pendukung' => 'nullable|array',
            'dokumen_pendukung.*' => 'file|max:20480', // Maks 20MB per file
            'portofolio' => 'nullable|array',
            'portofolio.*' => 'file|max:20480' // Maks 20MB per file
        ]);

        $data = $request->only([
            'nama_depan', 'nama_belakang', 'nomor_telepon', 'alamat',
            'perusahaan', 'nomor_npwp'
        ]);

    // Handle foto profile
    if ($request->hasFile('foto_profile')) {
        if ($user->contractorProfile && $user->contractorProfile->foto_profile) {
            Storage::disk('public')->delete('contractors/' . $user->contractorProfile->foto_profile);
        }
        $fileName = time() . '.' . $request->foto_profile->extension();
        $request->file('foto_profile')->storeAs('contractors', $fileName, 'public');
        $data['foto_profile'] = $fileName;
    }

        // Handle bidang usaha (maks 10)
        $bidangUsaha = array_filter($request->input('bidang_usaha', []));
        $data['bidang_usaha'] = array_slice($bidangUsaha, 0, 10); // Batasi hingga 10 item

    // Handle dokumen pendukung (multiple files)
    if ($request->hasFile('dokumen_pendukung')) {
        $dokumenPaths = [];
        foreach ($request->file('dokumen_pendukung') as $file) {
            $fileName = time() . '_' . uniqid() . '.' . $file->extension();
            $file->storeAs('contractors/documents', $fileName, 'public');
            $dokumenPaths[] = $fileName;
        }
        $data['dokumen_pendukung'] = $dokumenPaths;
    }


        // Handle portofolio (multiple files)
        if ($request->hasFile('portofolio')) {
            $portofolioPaths = [];
            foreach ($request->file('portofolio') as $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->extension();
                $file->storeAs('contractors/portfolios', $fileName, 'public');
                $portofolioPaths[] = $fileName;
            }
            $data['portofolio'] = $portofolioPaths;
        }


        if ($user->contractorProfile) {
            $user->contractorProfile->update($data);
        } else {
            $data['user_id'] = $user->id;
            ContractorProfile::create($data);
        }

        return redirect()->back()->with('success', 'Profil kontraktor berhasil diperbarui!');
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
        $contractors = \App\Models\User::where('role', 'kontraktor')->with('contractorProfile')->get();
        return view('contractor.index', compact('contractors'));
    }
}
