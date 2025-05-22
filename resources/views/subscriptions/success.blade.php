<!DOCTYPE html>
<html>
<head>
    <title>Berlangganan Berhasil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        h1 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 1rem;
        }
        p {
            font-size: 1rem;
            color: #666;
            margin-bottom: 1rem;
        }
        a {
            color: #4CAF50;
            text-decoration: none;
            font-size: 0.9rem;
            margin: 0.5rem;
        }
        a:hover {
            text-decoration: underline;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Berlangganan Berhasil</h1>
        @if (session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif
        <p>Anda sekarang dapat melihat semua postingan tugas selama 1 bulan.</p>
        <a href="{{ route('posts.all') }}">Lihat Semua Postingan</a>
        <a href="{{ route('home') }}">Kembali ke Home</a>
    </div>
</body>
</html>
