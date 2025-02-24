<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Like;
use App\Models\Comment;
class PostController extends Controller
{


    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|array',
            'gambar.*' => 'image|max:2048', // Maks 2MB per gambar
            'lokasi' => 'required|string|max:255',
            'estimasi_anggaran' => 'required|numeric',
            'durasi' => 'required|string|max:100'
        ]);

        $data = $request->only(['judul', 'deskripsi', 'lokasi', 'estimasi_anggaran', 'durasi']);

// Handle gambar (multiple files)
        if ($request->hasFile('gambar')) {
            $gambarPaths = [];
            foreach ($request->file('gambar') as $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->extension();
                $file->storeAs('posts', $fileName, 'public');
                $gambarPaths[] = $fileName;
            }
            $data['gambar'] = $gambarPaths;
        }

        $data['user_id'] = Auth::id();
        Post::create($data);

        return redirect()->route('home')->with('success', 'Tugas berhasil diposting!');
    }

    public function index()
    {
        $posts = Post::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('posts.index', compact('posts'));
    }

    public function all()
    {
        // Ambil semua postingan, muat relasi user, dan data profil berdasarkan role
        $posts = Post::with('user')->orderBy('created_at', 'desc')->get();

        // Loop untuk menambahkan nama panggilan dan foto profil berdasarkan role user
        foreach ($posts as $post) {
            if ($post->user) {
                if ($post->user->role === 'kontraktor' && $post->user->contractorProfile) {
                    $post->user->nama_panggilan = $post->user->contractorProfile->nama_panggilan;
                    $post->user->foto_profile = $post->user->contractorProfile->foto_profile;
                } elseif ($post->user->role === 'user' && $post->user->profile) {
                    $post->user->nama_panggilan = $post->user->profile->nama_panggilan;
                    $post->user->foto_profile = $post->user->profile->foto_profile;
                } else {
                    $post->user->nama_panggilan = null;
                    $post->user->foto_profile = null;
                }
            }
        }

        return view('posts.all', compact('posts'));
    }

    public function edit($id)
    {
        $post = Post::where('user_id', Auth::id())->findOrFail($id);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|array',
            'gambar.*' => 'image|max:2048',
            'lokasi' => 'required|string|max:255',
            'estimasi_anggaran' => 'required|numeric',
            'durasi' => 'required|string|max:100'
        ]);

        $data = $request->only(['judul', 'deskripsi', 'lokasi', 'estimasi_anggaran', 'durasi']);

        // Handle gambar (multiple files): tambah baru atau hapus yang sudah ada
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($post->gambar && count($post->gambar) > 0) {
                foreach ($post->gambar as $oldImage) {
                    Storage::delete('public/posts/' . $oldImage);
                }
            }
            $gambarPaths = [];
            foreach ($request->file('gambar') as $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->extension();
                $file->storeAs('public/posts', $fileName);
                $gambarPaths[] = $fileName;
            }
            $data['gambar'] = $gambarPaths;
        }

        $post->update($data);

        return redirect()->route('posts.index')->with('success', 'Postingan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $post = Post::where('user_id', Auth::id())->findOrFail($id);

        // Hapus gambar jika ada
        if ($post->gambar && count($post->gambar) > 0) {
            foreach ($post->gambar as $image) {
                Storage::delete('public/posts/' . $image);
            }
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Postingan berhasil dihapus!');
    }

    public function like($id)
    {
        $post = Post::findOrFail($id);
        $user = Auth::user();

        // Cek apakah user sudah like postingan ini
        $existingLike = Like::where('user_id', $user->id)->where('post_id', $post->id)->first();
        if ($existingLike) {
            // Unlike (hapus like)
            $existingLike->delete();
            return redirect()->back()->with('success', 'Like dihapus!');
        } else {
            // Like (tambahkan like)
            Like::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
            return redirect()->back()->with('success', 'Postingan berhasil di-like!');
        }
    }

    public function comment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $post = Post::findOrFail($id);
        $user = Auth::user();

        Comment::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'content' => $request->content
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
    }
}
