<!DOCTYPE html>
<html>
<head>
    <title>Daftar Chat Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .chat-container {
            display: flex;
            height: 80vh;
        }
        .chat-list {
            width: 30%;
            overflow-y: auto;
            border-right: 1px solid #ddd;
            padding: 10px;
        }
        .chat-content {
            width: 70%;
            display: flex;
            flex-direction: column;
            padding: 10px;
        }
        .messages {
            flex-grow: 1;
            overflow-y: auto;
            margin-bottom: 10px;
        }
        .message {
            margin: 10px 0;
        }
        .message.sent {
            text-align: right;
        }
        .message.received {
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>Daftar Chat Saya</h1>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="chat-container">
            <!-- Sidebar: Daftar Chat -->
            <div class="chat-list">
                @if ("")
                    <p>Tidak ada chat.</p>
                @else
                    @foreach ($chats as $chat)
                        <?php
                        $otherUser = $chat->sender_id === Auth::id() ? $chat->receiver : $chat->sender;
                        $isActive = $receiver && $receiver->id === $otherUser->id;
                        ?>
                        <div class="card mb-2 {{ $isActive ? 'bg-light' : '' }}">
                            <div class="card-body p-2">
                                <a href="{{ route('chats.index', $otherUser->id) }}" style="text-decoration: none; color: inherit;">
                                    <div class="d-flex align-items-center">
                                        @if ($otherUser->role === 'kontraktor' && $otherUser->contractorProfile->foto_profile)
                                            <img src="{{ asset('storage/contractors/' . $otherUser->contractorProfile->foto_profile) }}" width="40" class="rounded-circle me-2" alt="Foto Profil">
                                        @elseif ($otherUser->role === 'user' && $otherUser->profile->foto_profile)
                                            <img src="{{ asset('storage/foto_profil/' . $otherUser->profile->foto_profile) }}" width="40" class="rounded-circle me-2" alt="Foto Profil">
                                        @endif
                                        <div>
                                            <strong>{{ $otherUser->name }}</strong>
                                            @if ($otherUser->nama_panggilan || ($otherUser->role === 'kontraktor' && $otherUser->contractorProfile->nama_panggilan))
                                                ({{ $otherUser->role === 'kontraktor' ? $otherUser->contractorProfile->nama_panggilan : $otherUser->profile->nama_panggilan }})
                                            @endif
                                            <p class="small mb-0">{{ Str::limit($chat->message, 30) }}</p>
                                            <small>{{ $chat->created_at->format('d F Y H:i') }}</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Area Percakapan -->
            <div class="chat-content">
                @if ($receiver)
                    <h2 class="mb-3">Chat dengan {{ $receiver->name }}</h2>
                    <div class="messages">
                        @foreach ($selectedChats as $chat)
                            <div class="message {{ $chat->sender_id === Auth::id() ? 'sent' : 'received' }}">
                                <strong>{{ $chat->sender->name }}</strong>
                                @if ($chat->sender->nama_panggilan || ($chat->sender->role === 'kontraktor' && $chat->sender->contractorProfile->nama_panggilan))
                                    ({{ $chat->sender->role === 'kontraktor' ? $chat->sender->contractorProfile->nama_panggilan : $chat->sender->profile->nama_panggilan }})
                                @endif
                                <p>{{ $chat->message }}</p>
                                <small>{{ $chat->is_read ? 'Sudah dibaca' : 'Belum dibaca' }} - {{ $chat->created_at->format('d F Y H:i') }}</small>
                            </div>
                        @endforeach
                    </div>
                    <form action="{{ route('chats.store', $receiver->id) }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <textarea name="message" class="form-control" placeholder="Tulis pesan..." required></textarea>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </div>
                    </form>
                @else
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <p class="text-muted">Pilih chat untuk memulai percakapan.</p>
                    </div>
                @endif
            </div>
        </div>

        <a href="{{ route('home') }}" class="btn btn-secondary mt-3">Kembali ke Home</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
