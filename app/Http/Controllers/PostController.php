<?php
namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\Subscription;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\PostDeletedByAdminNotification;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'user') {
                return redirect()->route('home')->with('error', 'Hanya user yang dapat membuat postingan.');
            }
            return $next($request);
        })->only(['create', 'store']);
    }

    public function create()
    {
        if (!ProfileController::isProfileComplete(Auth::user())) {
            return redirect()->route('profile.edit')->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }
        return view('posts.create');
    }


    public function getLatestTenders()
{
    $latestTenders = Post::with('user')
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
    return view('home', compact('latestTenders'));
}

public function index(Request $request)
{
    $user = Auth::user();
    $query = Post::where('user_id', $user->id)
                 ->with('likes', 'comments')
                 ->orderBy('created_at', 'desc');

    // Filter berdasarkan judul
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('judul', 'like', "%{$search}%")
              ->orWhere('deskripsi', 'like', "%{$search}%");
        });
    }

    // Filter berdasarkan lokasi
    if ($request->filled('lokasi')) {
        $query->where('lokasi', 'like', "%{$request->input('lokasi')}%");
    }

    // Filter berdasarkan estimasi anggaran
    if ($request->filled('anggaran_min')) {
        $query->where('estimasi_anggaran', '>=', $request->input('anggaran_min'));
    }
    if ($request->filled('anggaran_max')) {
        $query->where('estimasi_anggaran', '<=', $request->input('anggaran_max'));
    }

    // Filter berdasarkan durasi
    if ($request->filled('durasi')) {
        $query->where('durasi', 'like', "%{$request->input('durasi')}%");
    }

    // Filter berdasarkan status
    if ($request->filled('status')) {
        $query->where('status', $request->input('status'));
    }

    $posts = $query->get();

    return view('posts.index', compact('posts'));
}

    public function all(Request $request)
    {
        $user = Auth::user();
        $limit = 10;

        $query = Post::with(['user', 'likes', 'comments'])->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
        if ($request->filled('lokasi')) {
            $query->where('lokasi', 'like', "%{$request->input('lokasi')}%");
        }
        if ($request->filled('anggaran_min')) {
            $query->where('estimasi_anggaran', '>=', $request->input('anggaran_min'));
        }
        if ($request->filled('anggaran_max')) {
            $query->where('estimasi_anggaran', '<=', $request->input('anggaran_max'));
        }
        if ($request->filled('durasi')) {
            $query->where('durasi', 'like', "%{$request->input('durasi')}%");
        }

        if ($user->role === 'kontraktor') {
            $subscription = Subscription::where('contractor_id', $user->id)
                                       ->where('is_active', true)
                                       ->where('start_date', '<=', now())
                                       ->where('end_date', '>=', now())
                                       ->first();

            if (!$subscription) {
                $posts = $query->take($limit)->get();
                $totalPosts = Post::count();
                return view('posts.all', compact('posts', 'totalPosts', 'limit'));
            }
        }

        $posts = $query->get();
        $totalPosts = $posts->count();

        return view('posts.all', compact('posts', 'totalPosts', 'limit'));
    }

    public function store(Request $request)
    {
        if (!ProfileController::isProfileComplete(Auth::user())) {
            return redirect()->route('profile.edit')->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        if (Auth::user()->role === 'kontraktor') {
            return redirect()->back()->with('error', 'Kontraktor tidak dapat membuat postingan tugas.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'lokasi' => 'required|string|max:255',
            'estimasi_anggaran' => 'required|numeric|min:10000', // Minimal 5 digit (Rp 10,000)
            'durasi' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    // Cek apakah mengandung salah satu satuan durasi
                    if (!preg_match('/\b\d+\s*(hari|minggu|bulan)\b/i', $value)) {
                        $fail('Durasi harus menyertakan satuan "hari", "minggu", atau "bulan" (contoh: 3 hari, 1 minggu, 2 bulan).');
                    }
                },
            ],
        ], [
            'estimasi_anggaran.min' => 'Estimasi anggaran minimal Rp 10,000.',
            'gambar.*.mimes' => 'Gambar hanya boleh berformat JPEG, PNG, atau JPG.',
            'durasi.regex' => 'Durasi harus menyertakan satuan "hari", "minggu", atau "bulan" (contoh: 3 hari, 1 minggu, 2 bulan).',
        ]);

        $gambarPaths = [];
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->extension();
                $path = $file->storeAs('posts', $fileName, 'public');
                $gambarPaths[] = $path;
            }
        }

        $dokumenPath = null;
        if ($request->hasFile('dokumen')) {
            $dokumen = $request->file('dokumen');
            $fileName = time() . '_' . uniqid() . '.' . $dokumen->extension();
            $dokumenPath = $dokumen->storeAs('posts/dokumen', $fileName, 'public');
        }

        Post::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambarPaths,
            'dokumen' => $dokumenPath,
            'lokasi' => $request->lokasi,
            'estimasi_anggaran' => $request->estimasi_anggaran,
            'durasi' => $request->durasi,
            'status' => 'open',
        ]);

        return redirect()->route('posts.all')->with('success', 'Postingan berhasil dibuat!');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $post = Post::with('likes', 'comments')->findOrFail($id);

        // Hanya user (bukan admin) yang perlu profil lengkap untuk edit
        if ($user->role !== 'admin' && !ProfileController::isProfileComplete($user)) {
            return redirect()->route('profile.edit')->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        if ($user->role !== 'admin' && $user->id !== $post->user_id) {
            return redirect()->route('posts.all')->with('error', 'Anda tidak memiliki izin untuk mengedit postingan ini.');
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $post = Post::findOrFail($id);

        // Hanya user (bukan admin) yang perlu profil lengkap untuk update
        if ($user->role !== 'admin' && !ProfileController::isProfileComplete($user)) {
            return redirect()->route('profile.edit')->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        if ($user->role !== 'admin' && $user->id !== $post->user_id) {
            return redirect()->route('posts.all')->with('error', 'Anda tidak memiliki izin untuk mengupdate postingan ini.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|array',
            'gambar.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'lokasi' => 'required|string|max:255',
            'estimasi_anggaran' => 'required|numeric|min:10000', // Minimal 5 digit (Rp 10,000)
            'durasi' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    // Cek apakah mengandung salah satu satuan durasi
                    if (!preg_match('/\b\d+\s*(hari|minggu|bulan)\b/i', $value)) {
                        $fail('Durasi harus menyertakan satuan "hari", "minggu", atau "bulan" (contoh: 3 hari, 1 minggu, 2 bulan).');
                    }
                },
            ],
        ], [
            'estimasi_anggaran.min' => 'Estimasi anggaran minimal Rp 10,000.',
            'gambar.*.mimes' => 'Gambar hanya boleh berformat JPEG, PNG, atau JPG.',
            'durasi.regex' => 'Durasi harus menyertakan satuan "hari", "minggu", atau "bulan" (contoh: 3 hari, 1 minggu, 2 bulan).',
        ]);

        $data = $request->only(['judul', 'deskripsi', 'lokasi', 'estimasi_anggaran', 'durasi']);

        if ($request->hasFile('gambar')) {
            if ($post->gambar && is_array($post->gambar) && count($post->gambar) > 0) {
                foreach ($post->gambar as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $gambarPaths = [];
            foreach ($request->file('gambar') as $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->extension();
                $path = $file->storeAs('posts', $fileName, 'public');
                $gambarPaths[] = $path;
            }
            $data['gambar'] = $gambarPaths;
        }

        if ($request->hasFile('dokumen')) {
            if ($post->dokumen) {
                Storage::disk('public')->delete($post->dokumen);
            }

            $dokumen = $request->file('dokumen');
            $fileName = time() . '_' . uniqid() . '.' . $dokumen->extension();
            $path = $dokumen->storeAs('posts/dokumen', $fileName, 'public');
            $data['dokumen'] = $path;
        }

        $post->update($data);

        return redirect()->route('posts.all')->with('success', 'Postingan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $post = Post::findOrFail($id);

        // Hanya user (bukan admin) yang perlu profil lengkap untuk hapus
        if ($user->role !== 'admin' && !ProfileController::isProfileComplete($user)) {
            return redirect()->route('profile.edit')->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        if ($user->role !== 'admin' && $user->id !== $post->user_id) {
            return redirect()->route('posts.all')->with('error', 'Anda tidak memiliki izin untuk menghapus postingan ini.');
        }

        // Kirim notifikasi ke user jika dihapus oleh admin
        if ($user->role === 'admin' && $user->id !== $post->user_id) {
            $post->user->notify(new PostDeletedByAdminNotification($post));
        }

        if ($post->gambar && is_array($post->gambar) && count($post->gambar) > 0) {
            foreach ($post->gambar as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        if ($post->dokumen) {
            Storage::disk('public')->delete($post->dokumen);
        }

        $post->delete();

        return redirect()->route('posts.all')->with('success', 'Postingan berhasil dihapus!');
    }

    public function like($id)
    {
        // if (!ProfileController::isProfileComplete(Auth::user())) {
        //     return redirect()->route('profile.edit')->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        // }

        $post = Post::findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'kontraktor' && (!$user->contractorProfile || !$user->contractorProfile->approved)) {
            return redirect()->back()->with('error', 'Anda harus disetujui oleh admin terlebih dahulu untuk melakukan tindakan ini.');
        }

        $existingLike = Like::where('user_id', $user->id)
                           ->where('post_id', $post->id)
                           ->first();

        if ($existingLike) {
            $existingLike->delete();
            return redirect()->back()->with('success', 'Like dihapus!');
        } else {
            Like::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
            return redirect()->back()->with('success', 'Postingan berhasil di-like!');
        }
    }

    public function comment(Request $request, $id)
    {
        // if (!ProfileController::isProfileComplete(Auth::user())) {
        //     return redirect()->route('profile.edit')->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        // }

        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $post = Post::findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'kontraktor' && (!$user->contractorProfile || !$user->contractorProfile->approved)) {
            return redirect()->back()->with('error', 'Anda harus disetujui oleh admin terlebih dahulu untuk mengirim komentar.');
        }

        Comment::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'content' => $request->content
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
    }
}
