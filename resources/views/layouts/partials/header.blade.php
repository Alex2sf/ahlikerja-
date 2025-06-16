<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Tambahkan style untuk menu aktif */
        .nav ul li a.active {
            position: relative;
            color: #28a745; /* Warna bisa disesuaikan */
            font-weight: bold;
        }

        .nav ul li a.active:after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #28a745;
            border-radius: 3px;
        }

        /* Style untuk dropdown yang aktif */
        .dropdown.active .dropbtn {
            color: #28a745;
            font-weight: bold;
        }

        .dropdown.active .dropbtn:after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #28a745;
            border-radius: 3px;
        }
    </style>
</head>
<header class="header">
    <div class="container">
        <a href="{{ route('home') }}" class="logo" style="text-decoration: none; color: inherit;">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <span class="logo-text">Ahli Kerja</span>
        </a>

        <nav class="nav" id="nav">
            <ul>
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                @if (auth()->user()->role === 'kontraktor')
                    <li class="dropdown {{ request()->routeIs('contractor.profile.*') ? 'active' : '' }}">
                        <a href="javascript:void(0)" class="dropbtn">Profil <i class="fas fa-chevron-down"></i></a>
                        <div class="dropdown-content">
                            <a href="{{ route('contractor.profile.show') }}" class="{{ request()->routeIs('contractor.profile.show') ? 'active' : '' }}">Lihat Profil</a>
                            <a href="{{ route('contractor.profile.edit') }}" class="{{ request()->routeIs('contractor.profile.edit') ? 'active' : '' }}">Edit Profil</a>
                        </div>
                    </li>
                    <li class="dropdown {{ request()->routeIs('orders.index') || request()->routeIs('bookings.index') || request()->routeIs('offers.my-offers') ? 'active' : '' }}">
                        <a href="javascript:void(0)" class="dropbtn">Pesanan <i class="fas fa-chevron-down"></i></a>
                        <div class="dropdown-content">
                            <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.index') ? 'active' : '' }}">Keranjang Tender</a>
                            <a href="{{ route('bookings.index') }}" class="{{ request()->routeIs('bookings.index') ? 'active' : '' }}">Detail Booking</a>
                            <a href="{{ route('offers.my-offers') }}" class="{{ request()->routeIs('offers.my-offers') ? 'active' : '' }}">List Penawaran</a>
                        </div>
                    </li>

                    <li><a href="{{ route('subscriptions.create') }}" class="{{ request()->routeIs('subscriptions.create') ? 'active' : '' }}">Berlangganan</a></li>
                    <li><a href="{{ route('posts.all') }}" class="{{ request()->routeIs('posts.all') ? 'active' : '' }}">Open Tender</a></li>
                @elseif (auth()->user()->role === 'admin')
                    <li><a href="{{ route('admin.contractors.index') }}" class="{{ request()->routeIs('admin.contractors.index') ? 'active' : '' }}">Kelola Kontraktor</a></li>
                    <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard Admin</a></li>
                    <li><a href="{{ route('contractors.index') }}" class="{{ request()->routeIs('contractors.index') ? 'active' : '' }}">Kontraktor</a></li>
                    <li><a href="{{ route('posts.all') }}" class="{{ request()->routeIs('posts.all') ? 'active' : '' }}">Tender</a></li>
                @else
                    <li class="dropdown {{ request()->routeIs('profile.show') || request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <a href="javascript:void(0)" class="dropbtn">Profil <i class="fas fa-chevron-down"></i></a>
                        <div class="dropdown-content">
                            <a href="{{ route('profile.show') }}" class="{{ request()->routeIs('profile.show') ? 'active' : '' }}">Lihat Profil</a>
                            <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">Edit Profil</a>
                        </div>
                    </li>
                    <li class="dropdown {{ request()->routeIs('posts.index') || request()->routeIs('posts.all') ? 'active' : '' }}">
                        <a href="javascript:void(0)" class="dropbtn">Tender <i class="fas fa-chevron-down"></i></a>
                        <div class="dropdown-content">
                            <a href="{{ route('posts.index') }}" class="{{ request()->routeIs('posts.index') ? 'active' : '' }}">Tender Saya</a>
                            <a href="{{ route('posts.all') }}" class="{{ request()->routeIs('posts.all') ? 'active' : '' }}">Semua Tender</a>
                        </div>
                    </li>
                    <li class="dropdown {{ request()->routeIs('orders.index') || request()->routeIs('bookings.index') ? 'active' : '' }}">
                        <a href="javascript:void(0)" class="dropbtn">Pesanan <i class="fas fa-chevron-down"></i></a>
                        <div class="dropdown-content">
                            <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.index') ? 'active' : '' }}">Pembayaran</a>
                            <a href="{{ route('bookings.index') }}" class="{{ request()->routeIs('bookings.index') ? 'active' : '' }}">Booking Saya</a>
                        </div>
                    </li>
                @endif
                @if (auth()->user()->role !== 'kontraktor' && auth()->user()->role !== 'admin')
                    <li class="dropdown {{ request()->routeIs('contractors.index') || request()->routeIs('recommendations.index') ? 'active' : '' }}">
                        <a href="javascript:void(0)" class="dropbtn">Kontraktor <i class="fas fa-chevron-down"></i></a>
                        <div class="dropdown-content">
                            <a href="{{ route('contractors.index') }}" class="{{ request()->routeIs('contractors.index') ? 'active' : '' }}">List Kontraktor</a>
                            <a href="{{ route('recommendations.index') }}" class="{{ request()->routeIs('recommendations.index') ? 'active' : '' }}">List Rekomendasi Kontraktor</a>
                        </div>
                    </li>
                @endif
                @if (auth()->user()->role === 'user' || auth()->user()->role === 'kontraktor')
                    <li><a href="{{ route('chats.index') }}" class="{{ request()->routeIs('chats.index') ? 'active' : '' }}">Chat</a></li>
                @endif
                <!-- Lonceng notifikasi hanya ditampilkan untuk non-admin -->
                @if (auth()->user()->role !== 'admin')
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropbtn notification-bell">
                            <i class="fas fa-bell" style="font-size: 25px;"></i>
                            @if (auth()->user()->unreadNotifications->count() > 0)
                                <span class="notification-count">{{ auth()->user()->unreadNotifications->count() }}</span>
                            @endif
                        </a>
                        <div class="dropdown-content notification-dropdown">
                            @if (auth()->user()->notifications->isEmpty())
                                <p class="no-notification">Tidak ada notifikasi.</p>
                            @else
                                @foreach (auth()->user()->notifications as $notification)
                                    <a href="{{ isset($notification->data['url']) ? $notification->data['url'] : route('home') }}"
                                       class="notification-item {{ $notification->read_at ? 'read' : 'unread' }}"
                                       data-id="{{ $notification->id }}">
                                        <p>{{ $notification->data['message'] }}</p>
                                        <small>{{ $notification->created_at->diffForHumans() }}</small>
                                    </a>
                                @endforeach
                                <a href="javascript:void(0)" class="mark-all-read">Tandai semua sebagai dibaca</a>
                            @endif
                        </div>
                    </li>
                @endif
                <li class="logout-item">
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </li>
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
    const menuToggle = document.getElementById('menuToggle');
    const nav = document.getElementById('nav');

    menuToggle.addEventListener('click', function () {
        nav.classList.toggle('active');
        this.classList.toggle('active');
    });

    // Toggle dropdowns on mobile
    document.querySelectorAll('.dropbtn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                const dropdown = this.parentElement;
                const content = dropdown.querySelector('.dropdown-content');
                content.style.display = content.style.display === 'block' ? 'none' : 'block';
            }
        });
    });

    // Mark notification as read when clicked
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function (e) {
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
                    if (countElement) {
                        const currentCount = parseInt(countElement.textContent) - 1;
                        if (currentCount > 0) {
                            countElement.textContent = currentCount;
                        } else {
                            countElement.remove();
                        }
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

    // Close dropdowns when clicking outside
    document.addEventListener('click', function (e) {
        if (window.innerWidth <= 768) {
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-content').forEach(content => {
                    content.style.display = 'none';
                });
            }
        }
    });
</script>
