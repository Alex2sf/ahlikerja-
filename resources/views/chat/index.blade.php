<!DOCTYPE html>
<html>
<head>
    <title>Daftar Chat Saya</title>
</head>
<body>
    <h1>Daftar Chat Saya</h1>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif
    @if ($chats->isEmpty())
        <p>Tidak ada chat.</p>
    @else
        @foreach ($chats as $chat)
            <?php
            $otherUser = $chat->sender_id === $user->id ? $chat->receiver : $chat->sender;
            ?>
            <div>
                <h2>
                    <a href="{{ route('chats.show', $otherUser->id) }}">
                        {{ $otherUser->name }}
                        @if ($otherUser->nama_panggilan || ($otherUser->role === 'kontraktor' && $otherUser->contractorProfile->nama_panggilan))
                            ({{ $otherUser->role === 'kontraktor' ? $otherUser->contractorProfile->nama_panggilan : $otherUser->profile->nama_panggilan }})
                        @endif
                    </a>
                </h2>
                @if ($otherUser->role === 'kontraktor' && $otherUser->contractorProfile->foto_profile)
                    <a href="{{ route('chats.show', $otherUser->id) }}">
                        <img src="{{ Storage::url('contractors/' . $otherUser->contractorProfile->foto_profile) }}" width="50" alt="Foto Profil">
                    </a>
                @elseif ($otherUser->role === 'user' && $otherUser->profile->foto_profile)
                    <a href="{{ route('chats.show', $otherUser->id) }}">
                        <img src="{{ Storage::url('profiles/' . $otherUser->profile->foto_profile) }}" width="50" alt="Foto Profil">
                    </a>
                @endif
                <p>Pesan Terbaru: {{ $chat->message }}</p>
                <p>Dikirim pada: {{ $chat->created_at->format('d F Y H:i') }}</p>
                <hr>
            </div>
        @endforeach
    @endif
    <a href="{{ route('home') }}">Kembali ke Home</a>
</body>
</html>
