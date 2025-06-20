@extends('layouts.app')

@section('title', 'Daftar Chat Saya')

@section('content')
    <div class="container">
        <div class="chat-section">
            <!-- Back Link -->
            <div class="back-link-top">
            <a href="{{ url()->previous() }}"
            class="btn"
            style="background-color: #CD853F;  /* coklat muda (peru) */
                    color: white;
                    font-weight: 600;
                    padding: 6px 14px;
                    font-size: 0.95rem;
                    border: none;
                    border-radius: 5px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
            Kembali
            </a>
            </div>

            <h1>Daftar Chat Saya</h1>
            @if (session('success'))
                <div class="notification success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="notification error">{{ session('error') }}</div>
            @endif

            <div class="chat-container">
                <!-- Sidebar: Daftar Chat -->
                <div class="chat-list">
                    @if ($chats->isEmpty())
                        <p class="text-center text-muted">Tidak ada chat.</p>
                    @else
                        @foreach ($chats as $chat)
                            <?php
                            $otherUser = $chat->sender_id === Auth::id() ? $chat->receiver : $chat->sender;
                            $isActive = $receiver && $receiver->id === $otherUser->id;
                            ?>
                            <div class="chat-item {{ $isActive ? 'active' : '' }}">
                                <a href="{{ route('chats.index', $otherUser->id) }}" class="chat-link">
                                    <div class="chat-header">
                                        @if ($otherUser->role === 'kontraktor' && $otherUser->contractorProfile && $otherUser->contractorProfile->foto_profile)
                                            <img src="{{ Storage::url($otherUser->contractorProfile->foto_profile) }}" alt="Foto Profil" class="profile-photo">
                                        @elseif ($otherUser->role === 'user' && $otherUser->profile && $otherUser->profile->foto_profile)
                                            <img src="{{ Storage::url($otherUser->profile->foto_profile) }}" alt="Foto Profile" class="profile-photo">
                                        @else
                                            <img src="{{ asset('images/default-profile.png') }}" alt="Foto Default" class="profile-photo">
                                        @endif
                                        <div class="chat-info">
                                            <strong>{{ $otherUser->name }}</strong>
                                            @if ($otherUser->role === 'kontraktor' && $otherUser->contractorProfile && $otherUser->contractorProfile->nama_panggilan)
                                                ({{ $otherUser->contractorProfile->nama_panggilan }})
                                            @elseif ($otherUser->role === 'user' && $otherUser->profile && $otherUser->profile->nama_panggilan)
                                                ({{ $otherUser->profile->nama_panggilan }})
                                            @endif
                                            <p class="small">{{ Str::limit($chat->message, 30) }}</p>
                                            <small>{{ $chat->created_at->format('d F Y H:i') }}</small>
                                        </div>
                                    </div>
                                </a>
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
                                    @if ($chat->sender->role === 'kontraktor' && $chat->sender->contractorProfile && $chat->sender->contractorProfile->nama_panggilan)
                                        ({{ $chat->sender->contractorProfile->nama_panggilan }})
                                    @elseif ($chat->sender->role === 'user' && $chat->sender->profile && $chat->sender->profile->nama_panggilan)
                                        ({{ $chat->sender->profile->nama_panggilan }})
                                    @endif
                                    <p>{{ $chat->message }}</p>
                                    <!-- Tampilkan Attachment (Foto atau Dokumen) -->
                                    @if ($chat->attachment)
                                        <div class="attachment">
                                            @if (in_array(pathinfo($chat->attachment, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                                <a href="{{ Storage::url($chat->attachment) }}" target="_blank">
                                                    <img src="{{ Storage::url($chat->attachment) }}" alt="Attachment" class="attachment-image">
                                                </a>
                                            @else
                                                <a href="{{ Storage::url($chat->attachment) }}" target="_blank" class="attachment-link">
                                                    <i class="fas fa-file"></i> {{ basename($chat->attachment) }}
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                    <small>{{ $chat->is_read ? 'Sudah dibaca' : 'Belum dibaca' }} - {{ $chat->created_at->format('d F Y H:i') }}</small>
                                </div>
                            @endforeach
                        </div>
                        <form action="{{ route('chats.store', $receiver->id) }}" method="POST" class="message-form" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <textarea name="message" class="message-input" placeholder="Tulis pesan..." required>{{ request()->query('postTitle') ? "Halo, saya tertarik dengan postingan dengan judul \"".request()->query('postTitle')."\". Apakah kita bisa berdiskusi tentang surat perjanjian kontrak ini?" : '' }}</textarea>
                                <div class="attachment-input">
                                    <label for="attachment" class="attachment-label">
                                        <i class="fas fa-paperclip"></i> Lampirkan File
                                    </label>
                                    <input type="file" id="attachment" name="attachment" accept="image/jpeg,image/png,application/pdf,.doc,.docx">
                                </div>
                                <button type="submit" class="btn btn-primary">Kirim</button>
                            </div>
                        </form>
                    @else
                        <div class="no-chat">
                            <p class="text-muted">Pilih chat untuk memulai percakapan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Chat Section */
        .chat-section {
            width: 1200px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0d8c9;
            position: relative; /* Untuk positioning tombol */
        }

        .chat-section h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #5a3e36;
            text-align: center;
            margin-bottom: 30px;
        }

        /* Back Link di Pojok Kiri Atas */
        .back-link-top {
            position: absolute;
            top: 15px;
            left: 15px;
        }

        /* Chat Container */
        .chat-container {
            display: flex;
            height: 70vh;
            gap: 20px;
        }

        /* Chat List (Sidebar) */
        .chat-list {
            width: 30%;
            background-color: #fdfaf6;
            padding: 15px;
            border-radius: 10px;
            overflow-y: auto;
        }

        .chat-item {
            margin-bottom: 10px;
            transition: background-color 0.3s ease;
        }

        .chat-item.active {
            background-color: #f8f1e9;
        }

        .chat-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .chat-header {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px;
        }

        .profile-photo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e0d8c9;
        }

        .chat-info strong {
            font-size: 16px;
            color: #5a3e36;
        }

        .chat-info p.small {
            font-size: 14px;
            color: #555;
            margin: 5px 0 0;
        }

        .chat-info small {
            font-size: 12px;
            color: #6b5848;
        }

        /* Chat Content (Area Percakapan) */
        .chat-content {
            width: 70%;
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
        }

        .chat-content h2 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            color: #5a3e36;
            margin-bottom: 15px;
        }

        /* Messages */
        .messages {
            flex-grow: 1;
            overflow-y: auto;
            padding: 10px;
            background-color: #fdfaf6;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .message {
            margin: 10px 0;
            max-width: 70%;
            padding: 10px;
            border-radius: 10px;
        }

        .message.sent {
            background-color: #a8c3b8;
            color: #fff;
            margin-left: auto;
            text-align: right;
        }

        .message.received {
            background-color: #d4c8b5;
            color: #5a3e36;
            margin-right: auto;
            text-align: left;
        }

        .message strong {
            font-size: 14px;
        }

        .message p {
            margin: 5px 0;
            font-size: 14px;
        }

        .message small {
            font-size: 12px;
            color: #6b5848;
        }

        /* Attachment */
        .attachment {
            margin-top: 10px;
        }

        .attachment-image {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #e0d8c9;
        }

        .attachment-link {
            display: flex;
            align-items: center;
            gap: 5px;
            color: white;
            text-decoration: none;
            font-size: 14px;
        }

        .attachment-link:hover {
            color: #8ba89a;
            text-decoration: underline;
        }

        /* Message Form */
        .message-form {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            width: 100%;
            gap: 10px;
        }

        .message-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #d4c8b5;
            border-radius: 5px;
            font-size: 14px;
            color: #555;
            background-color: #fdfaf6;
            resize: vertical;
        }

        .message-input:focus {
            border-color: #a8c3b8;
            outline: none;
        }

        .attachment-input {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .attachment-label {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #5a3e36;
            font-size: 14px;
            cursor: pointer;
            padding: 5px 10px;
            border: 1px solid #d4c8b5;
            border-radius: 5px;
            background-color: #fdfaf6;
            transition: background-color 0.3s ease;
        }

        .attachment-label:hover {
            background-color: #f8f1e9;
        }

        .attachment-input input[type="file"] {
            display: none;
        }

        /* No Chat */
        .no-chat {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        /* Notification */
        .notification {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }

        .notification.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .notification.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Button Styles */
       .btn {
            background-color: #2E8B57; /* SeaGreen, hijau zamrud yang tegas */
            border: none;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 2px 6px rgba(46, 139, 87, 0.5);
            display: inline-block;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #276749; /* Lebih gelap saat hover */
            box-shadow: 0 4px 12px rgba(39, 103, 73, 0.7);
        }


        .btn-secondary {
            background-color: #d4c8b5; /* Beige */
            color: #5a3e36;
        }

        .btn-secondary:hover {
            background-color: #c7b9a1;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .chat-section {
                padding: 20px;
                margin: 20px;
            }

            .chat-section h1 {
                font-size: 28px;
            }

            .chat-container {
                flex-direction: column;
                height: auto;
            }

            .chat-list {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #e0d8c9;
                margin-bottom: 20px;
            }

            .chat-content {
                width: 100%;
            }

            .profile-photo {
                width: 40px;
                height: 40px;
            }

            .message {
                max-width: 100%;
            }

            .message.sent {
                margin-left: 0;
            }

            .message.received {
                margin-right: 0;
            }

            .attachment-image {
                max-width: 150px;
                max-height: 150px;
            }

            .input-group {
                flex-direction: column;
            }

            .attachment-input {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn {
                width: auto; /* Tombol tidak perlu full width di layar kecil */
                padding: 6px 12px;
            }

            .back-link-top {
                top: 10px;
                left: 10px;
            }
        }
    </style>
@endsection
