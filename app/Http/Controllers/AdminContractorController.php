<?php

namespace App\Http\Controllers;

use App\Models\ContractorProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminContractorController extends Controller
{


    public function index()
    {
        $contractors = ContractorProfile::with('user')->where('approved', false)->get();
        return view('admin.contractor.index', compact('contractors'));
    }

    public function approve(Request $request, $id)
    {
        $profile = ContractorProfile::findOrFail($id);
        $request->validate([
            'approved' => 'required|boolean',
            'admin_note' => 'nullable|string|max:1000'
        ]);

        $profile->update([
            'approved' => $request->approved,
            'admin_note' => $request->admin_note
        ]);

        if ($request->approved) {
            return redirect()->route('admin.contractors.index')->with('success', 'Kontraktor disetujui.');
        } else {
            return redirect()->route('admin.contractors.index')->with('success', 'Kontraktor ditolak.');
        }
    }
}
