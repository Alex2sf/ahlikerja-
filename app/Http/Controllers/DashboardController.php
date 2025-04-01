<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ContractorProfile;
use App\Models\Post;
use App\Models\Booking;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
public function index()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak, hanya admin yang diperbolehkan.');
        }

        $totalUsers = User::count();
        $pendingContractors = ContractorProfile::where('approved', false)->count();
        $totalPosts = Post::count();
        $activeOffers = Booking::whereNull('status')->orWhere('status', 'pending')->count();
        $postsToday = Post::whereDate('created_at', today())->count();
        $pendingContractorsList = ContractorProfile::with('user')->where('approved', false)->limit(5)->get();
        $activityLogs = Activity::orderBy('created_at', 'desc')->limit(5)->get();

        // Daftar Penawaran Aktif dengan relasi user (client) dan kontraktor
        $activeOffersList = Booking::with(['user', 'contractor'])
            ->where(function ($query) {
                $query->whereNull('status')->orWhere('status', 'pending');
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'pendingContractors',
            'totalPosts',
            'activeOffers',
            'postsToday',
            'pendingContractorsList',
            'activityLogs',
            'activeOffersList'
        ));
    }


    public function approve(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        $contractor = ContractorProfile::findOrFail($id);
        $contractor->update([
            'approved' => true,
            'admin_note' => $request->input('admin_note', 'Disetujui oleh admin.')
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Kontraktor disetujui.');
    }

    public function reject(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        $contractor = ContractorProfile::findOrFail($id);
        $contractor->update([
            'approved' => false,
            'admin_note' => $request->input('admin_note', 'Ditolak oleh admin.')
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Kontraktor ditolak.');
    }

    public function showBooking($id)
{
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        return redirect('/')->with('error', 'Akses ditolak.');
    }

    $booking = Booking::with('user')->findOrFail($id);
    return view('admin.bookings.show', compact('booking'));
}

public function indexBookings()
{
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        return redirect('/')->with('error', 'Akses ditolak.');
    }

    $bookings = Booking::with('user')->whereNull('status')->orWhere('status', 'pending')->get();
    return view('admin.bookings.index', compact('bookings'));
}
}
