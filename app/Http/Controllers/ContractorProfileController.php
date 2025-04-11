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
        $profile = $user->contractorProfile ?? new ContractorProfile();

        $request->validate([
            'foto_profile' => 'nullable|image|max:2048',
            'nomor_telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'perusahaan' => 'required|string|max:255',
            'nomor_npwp' => 'required|string|max:255',
            'bidang_usaha.*' => 'nullable|string|max:255',
            'dokumen_pendukung.*' => 'nullable|file|max:2048',
            'portofolio.*' => 'nullable|file|max:2048',
            'identity_images.*' => 'nullable|image|max:2048',
            'bio' => 'nullable|string|max:500',
        ]);

        $data = $request->only([
            'nomor_telepon', 'alamat', 'perusahaan', 'nomor_npwp', 'bio'
        ]);

        // Handle foto profil
        if ($request->hasFile('foto_profile')) {
            if ($profile->foto_profile) {
                Storage::disk('public')->delete($profile->foto_profile);
            }
            $data['foto_profile'] = $request->file('foto_profile')->store('contractors', 'public');
        }

        // Handle bidang usaha
        $data['bidang_usaha'] = array_filter($request->input('bidang_usaha', []));

        // Handle dokumen pendukung
        if ($request->hasFile('dokumen_pendukung')) {
            $dokumenPendukung = $profile->dokumen_pendukung ?? [];
            foreach ($request->file('dokumen_pendukung') as $file) {
                $dokumenPendukung[] = $file->store('contractors/documents', 'public');
            }
            $data['dokumen_pendukung'] = $dokumenPendukung;
        }

        // Handle portofolio
        if ($request->hasFile('portofolio')) {
            $portofolio = $profile->portofolio ?? [];
            foreach ($request->file('portofolio') as $file) {
                $portofolio[] = $file->store('contractors/portfolios', 'public');
            }
            $data['portofolio'] = $portofolio;
        }

        // Handle identity images
        if ($request->hasFile('identity_images')) {
            $identityImages = $profile->identity_images ?? [];
            foreach ($request->file('identity_images') as $image) {
                $identityImages[] = $image->store('contractors/identity', 'public');
            }
            $data['identity_images'] = $identityImages;
        }

        if ($profile->exists) {
            $profile->update($data);
        } else {
            $data['user_id'] = $user->id;
            ContractorProfile::create($data);
        }

        return redirect()->route('contractor.profile.show')->with('success', 'Profil berhasil diperbarui!');
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

    public function index(Request $request)
    {
        $bidangUsahaOptions = ContractorProfile::whereNotNull('bidang_usaha')
            ->pluck('bidang_usaha')
            ->flatten()
            ->unique()
            ->values()
            ->all();

        $query = User::where('role', 'kontraktor')
                     ->with(['contractorProfile', 'reviews'])
                     ->whereHas('contractorProfile', function ($query) {
                         $query->where('approved', true);
                     });

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('contractorProfile', function ($q) use ($search) {
                      $q->where('perusahaan', 'like', "%{$search}%");
                  });
            });
        }

        if ($lokasi = $request->query('lokasi')) {
            $query->whereHas('contractorProfile', function ($q) use ($lokasi) {
                $q->where('alamat', 'like', "%{$lokasi}%");
            });
        }

        if ($bidangUsaha = $request->query('bidang_usaha')) {
            $query->whereHas('contractorProfile', function ($q) use ($bidangUsaha) {
                $q->whereJsonContains('bidang_usaha', $bidangUsaha);
            });
        }

        $contractors = $query->get();

        return view('contractor.index', compact('contractors', 'bidangUsahaOptions'));
    }

    /**
     * Menghapus file dari dokumen pendukung, portofolio, atau gambar data diri
     *
     * @param Request $request
     * @param string $type Tipe file (dokumen, portofolio, atau gambar)
     * @param int $index Indeks file dalam array
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteFile(Request $request, $type, $index)
    {
        $profile = Auth::user()->contractorProfile;
        if (!$profile) {
            return redirect()->route('contractor.profile.show')->with('error', 'Profil tidak ditemukan.');
        }

        $field = null;
        $storagePathPrefix = '';
        switch (strtolower($type)) {
            case 'dokumen':
                $field = 'dokumen_pendukung';
                $storagePathPrefix = 'contractors/documents/';
                break;
            case 'portofolio':
                $field = 'portofolio';
                $storagePathPrefix = 'contractors/portfolios/';
                break;
            case 'gambar':
                $field = 'identity_images';
                $storagePathPrefix = 'contractors/identity/';
                break;
            default:
                return redirect()->route('contractor.profile.show')->with('error', 'Tipe file tidak valid.');
        }

        // Ambil data dari kolom JSON sebagai array
        $fileArray = $profile->$field ?? [];
        if (!is_array($fileArray) || !array_key_exists($index, $fileArray)) {
            return redirect()->route('contractor.profile.show')->with('error', 'File tidak ditemukan.');
        }

        // Hapus file dari storage
        $filePath = $fileArray[$index];
        Storage::disk('public')->delete($storagePathPrefix . $filePath);

        // Hapus elemen dari array
        unset($fileArray[$index]);
        $fileArray = array_values($fileArray); // Reindex array

        // Simpan kembali array ke kolom JSON
        $profile->$field = $fileArray;
        $profile->save();

        return redirect()->route('contractor.profile.show')->with('success', 'File berhasil dihapus.');
    }
}
