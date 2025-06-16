<?php

namespace App\Http\Controllers;

use App\Models\ContractorProfile;
use App\Notifications\ContractorApprovalNotification;
use Illuminate\Http\Request;

class AdminContractorController extends Controller
{
    public function index()
    {
        $contractors = ContractorProfile::with('user')->where('approved', false)
                                                      ->orderBy('created_at','desc')
                                                      ->get();
        return view('admin.contractor.index', compact('contractors'));
    }

    public function approve(Request $request, $id)
    {
        $profile = ContractorProfile::findOrFail($id);
        $request->validate([
            'approved' => 'required|boolean',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $profile->update([
            'approved' => $request->approved,
            'admin_note' => $request->admin_note,
        ]);

        // Kirim notifikasi ke kontraktor
        $profile->user->notify(new ContractorApprovalNotification($request->approved, $request->admin_note));

        if ($request->approved) {
            return redirect()->route('admin.contractors.index')->with('success', 'Kontraktor disetujui.');
        } else {
            return redirect()->route('admin.contractors.index')->with('success', 'Kontraktor ditolak.');
        }
    }
}
