<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ahli Kerja</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<header class="header">
    <div class="container">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <span class="logo-text">Ahli Kerja</span>
        </div>
        <nav class="nav" id="nav">
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                @if (auth()->user()->role === 'kontraktor')
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropbtn">Profil▼</a>
                        <div class="dropdown-content">
                            <a href="{{ route('contractor.profile.show') }}">Lihat Profil Kontraktor</a>
                            <a href="{{ route('contractor.profile.edit') }}">Edit Profil Kontraktor</a>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropbtn">Pesanan▼</a>
                        <div class="dropdown-content">
                            <a href="{{ route('orders.index') }}">Lihat Keranjang Pemesanan</a>
                            <a href="{{ route('bookings.index') }}">Lihat Pesanan Saya</a>
                        </div>
                    </li>
                    <li><a href="{{ route('subscriptions.create') }}">Berlangganan</a></li>
                    <li><a href="{{ route('posts.all') }}">Postingan</a></li>
                @elseif (auth()->user()->role === 'admin')
                    <li><a href="{{ route('admin.contractors.index') }}">Kelola Kontraktor</a></li>
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                    <li><a href="{{ route('contractors.index') }}">Kontraktor</a></li>
                    <li><a href="{{ route('posts.all') }}">Postingan</a></li>
                @else
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropbtn">Profil▼</a>
                        <div class="dropdown-content">
                            <a href="{{ route('profile.show') }}">Lihat Profil</a>
                            <a href="{{ route('profile.edit') }}">Edit Profil</a>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropbtn">Postingan▼</a>
                        <div class="dropdown-content">
                            <a href="{{ route('posts.create') }}">Buat Postingan Tugas</a>
                            <a href="{{ route('posts.index') }}">Lihat Postingan Saya</a>
                            <a href="{{ route('posts.all') }}">Lihat Semua Postingan</a>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropbtn">Pesanan▼</a>
                        <div class="dropdown-content">
                            <a href="{{ route('orders.index') }}">Lihat Keranjang Pemesanan</a>
                            <a href="{{ route('bookings.index') }}">Lihat Pesanan Saya</a>
                        </div>
                    </li>
                @endif
                @if (auth()->user()->role !== 'kontraktor' && auth()->user()->role !== 'admin')
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropbtn">Kontraktor▼</a>
                        <div class="dropdown-content">
                            <a href="{{ route('contractors.index') }}">Lihat Semua Kontraktor</a>
                            <a href="{{ route('recommendations.index') }}">Lihat Rekomendasi Kontraktor</a>
                        </div>
                    </li>
                @endif
                <li><a href="{{ route('chats.index') }}">Lihat Chat Saya</a></li>
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropbtn notification-bell">
                        <i class="fas fa-bell"></i>
                        @if (auth()->user()->unreadNotifications->count() > 0)
                            <span class="notification-count">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </a>
                    <div class="dropdown-content notification-dropdown">
                        @if (auth()->user()->notifications->isEmpty())
                            <p class="no-notification">Tidak ada notifikasi.</p>
                        @else
                            @foreach (auth()->user()->notifications as $notification)
                                <a href="{{ $notification->data['url'] }}" class="notification-item {{ $notification->read_at ? 'read' : 'unread' }}"
                                   data-id="{{ $notification->id }}">
                                    <p>{{ $notification->data['message'] }}</p>
                                    <small>{{ $notification->created_at->diffForHumans() }}</small>
                                </a>
                            @endforeach
                            <a href="javascript:void(0)" class="mark-all-read">Tandai semua sebagai dibaca</a>
                        @endif
                    </div>
                </li>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </ul>
        </nav>
        <div class="menu-toggle" id="menuToggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</header>
<script>
    // Toggle menu on mobile
    document.getElementById('menuToggle').addEventListener('click', function () {
        document.getElementById('nav').classList.toggle('active');
        this.classList.toggle('active');
    });

    // Mark notification as read when clicked
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function () {
            const notificationId = this.getAttribute('data-id');
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            }).then(response => response.json()).then(data => {
                if (data.success) {
                    this.classList.remove('unread');
                    this.classList.add('read');
                    const countElement = document.querySelector('.notification-count');
                    const currentCount = parseInt(countElement.textContent) - 1;
                    if (currentCount > 0) {
                        countElement.textContent = currentCount;
                    } else {
                        countElement.remove();
                    }
                }
            });
        });
    });

    // Mark all notifications as read
    document.querySelector('.mark-all-read')?.addEventListener('click', function () {
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        }).then(response => response.json()).then(data => {
            if (data.success) {
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.classList.remove('unread');
                    item.classList.add('read');
                });
                document.querySelector('.notification-count')?.remove();
            }
        });
    });
</script>
