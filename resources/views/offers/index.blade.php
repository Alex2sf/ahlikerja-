<!DOCTYPE html>
<html>
<head>
    <title>Penawaran untuk Postingan "{{ $post->judul }}"</title>
</head>
<body>
    <h1>Penawaran untuk Postingan "{{ $post->judul }}"</h1>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif
    @if ($offers->isEmpty())
        <p>Tidak ada penawaran untuk postingan ini.</p>
    @else
        @foreach ($offers as $offer)
            <div>
                <h2>
                    <a href="{{ route('contractor.profile.showPublic', $offer->contractor->id) }}">
                        {{ $offer->contractor->name }}
                        @if ($offer->contractor->contractorProfile && $offer->contractor->contractorProfile->nama_panggilan)
                            ({{ $offer->contractor->contractorProfile->nama_panggilan }})
                        @endif
                    </a>
                </h2>
                @if ($offer->contractor->contractorProfile && $offer->contractor->contractorProfile->foto_profile)
                    <a href="{{ route('contractor.profile.showPublic', $offer->contractor->id) }}">
                        <img src="{{ Storage::url('contractors/' . $offer->contractor->contractorProfile->foto_profile) }}" width="50" alt="Foto Profil">
                    </a>
                @endif
                <p>Status: {{ $offer->accepted ? 'Diterima' : 'Menunggu' }}</p>
                @if (!$offer->accepted && !$acceptedOffer)
                    <form action="{{ route('offers.accept', $offer->id) }}" method="POST">
                        @csrf
                        <button type="submit">Terima Penawaran</button>
                    </form>
                @endif
                <hr>
            </div>
        @endforeach
    @endif
    <a href="{{ route('posts.all') }}">Kembali ke Semua Postingan</a>
</body>
</html>
