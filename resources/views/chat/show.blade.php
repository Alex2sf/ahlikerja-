<!DOCTYPE html>
<html>
<head>
    <title>Chat dengan {{ $receiver->name }}</title>
</head>
<body>
    <h1>Chat dengan {{ $receiver->name }}</h1>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div>{{ session('error') }}</div>
    @endif

    <!-- Daftar Pesan -->
    <div>
        @foreach ($chats as $chat)
            <div>
                <p><strong>{{ $chat->sender->name }}
                    @if ($chat->sender->nama_panggilan || ($chat->sender->role === 'kontraktor' && $chat->sender->contractorProfile->nama_panggilan))
                        ({{ $chat->sender->role === 'kontraktor' ? $chat->sender->contractorProfile->nama_panggilan : $chat->sender->profile->nama_panggilan }})
                    @endif
                </strong></p>
                <p>{{ $chat->message }}</p>
                <p>{{ $chat->is_read ? 'Sudah dibaca' : 'Belum dibaca' }} - {{ $chat->created_at->format('d F Y H:i') }}</p>
                <hr>
            </div>
        @endforeach
    </div>

    <!-- Form Kirim Pesan -->
    <form action="{{ route('chats.store', $receiver->id) }}" method="POST">
        @csrf
        <textarea name="message" placeholder="Tulis pesan..." required></textarea>
        <button type="submit">Kirim</button>
    </form>
    <a href="{{ route('chats.index') }}">Kembali ke Daftar Chat</a>
</body>
</html>
