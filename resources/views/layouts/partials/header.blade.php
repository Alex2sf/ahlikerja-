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
