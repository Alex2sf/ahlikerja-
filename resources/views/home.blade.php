<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>
    <h1>Selamat Datang, {{ auth()->user()->name }}</h1>
    <p>Role: {{ auth()->user()->role }}</p>
    <form method="POST" action="/logout">
        @csrf
        <button type="submit">Logout</button>
    </form>

    @if (auth()->user()->role === 'kontraktor')
        <a href="{{ route('contractor.profile.show') }}">
            <button>Lihat Profil Kontraktor</button>
        </a>
        <a href="{{ route('contractor.profile.edit') }}">
            <button>Edit Profil Kontraktor</button>
        </a>
    @else
        <a href="{{ route('profile.show') }}">
            <button>Lihat Profil</button>
        </a>
        <a href="{{ route('profile.edit') }}">
            <button>Edit Profil</button>
        </a>
    @endif

    <a href="{{ route('posts.create') }}">
        <button>Buat Postingan Tugas</button>
    </a>
    <a href="{{ route('posts.index') }}">
        <button>Lihat Postingan Saya</button>
    </a>
    <a href="{{ route('posts.all') }}">
        <button>Lihat Semua Postingan</button>
    </a>
    <a href="{{ route('contractors.index') }}">
        <button>Lihat Semua Kontraktor</button>
    </a>
    <a href="{{ route('chats.index') }}">
        <button>Lihat Chat Saya</button>
    </a>
</body>
</html>
