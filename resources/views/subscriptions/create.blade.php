<!DOCTYPE html>
<html>
<head>
    <title>Berlangganan untuk Melihat Semua Postingan Tugas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .error {
            color: red;
            margin: 10px 0;
            text-align: center;
        }
        button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 20px auto;
        }
        button:hover {
            background-color: #0056b3;
        }
        a {
            display: block;
            text-align: center;
            color: #007BFF;
            text-decoration: none;
            margin-top: 10px;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Berlangganan untuk Melihat Semua Postingan Tugas</h1>

    @if (session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <p>Dengan berlangganan sebesar Rp1 per bulan, Anda dapat melihat semua postingan tugas selama 1 bulan.</p>

    <form action="{{ route('subscriptions.store') }}" method="POST">
        @csrf
        <input type="hidden" name="plan_id" value="1"> <!-- Ganti "1" sesuai ID plan yang ada -->
        <button type="submit">Berlangganan Sekarang (Rp1/Bulan)</button>
    </form>

    <a href="{{ route('home') }}">Kembali ke Home</a>
</body>
</html>
