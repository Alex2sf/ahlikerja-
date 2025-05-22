<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* General Styles */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-image: url('{{ asset('images/register.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #f8f1e9; /* Krem lembut vintage */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-wrapper {
            display: flex;
            width: 100%;
            max-width: 900px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        /* Left Side (Branding) */
        .login-left {
            flex: 1;
            background-color: #a8c3b8; /* Hijau sage natural */
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background: url('/images/leaf-pattern.png') repeat; /* Optional: pola daun subtil */
            opacity: 0.1;
        }

        .login-left .logo {
            margin-bottom: 20px;
        }

        .login-left .logo img {
            width: 150px; /* Ukuran logo diperbesar */
            height: auto;
        }

        .login-left h1 {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            color: #fff;
            margin-bottom: 20px;
            text-align: center;
        }

        .login-left p {
            font-size: 16px;
            color: #f8f1e9; /* Krem */
            text-align: center;
            opacity: 0.9;
        }

        /* Right Side (Form) */
        .login-right {
            flex: 1;
            padding: 40px;
            background-color: #fff;
        }

        .login-right h2 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: #5a3e36; /* Cokelat tua elegan */
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
            color: #6b5848; /* Cokelat lembut */
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        input[type="email"],
        input[type="password"] {
            width: 95%;
            padding: 12px;
            border: 1px solid #d4c8b5; /* Natural beige */
            border-radius: 8px;
            font-size: 16px;
            background-color: #fdfaf6; /* Latar krem */
            color: #333;
            transition: border-color 0.3s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #a8c3b8; /* Hijau sage */
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #a8c3b8; /* Hijau sage */
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #8ba89a; /* Hijau sedikit lebih gelap */
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }

        /* Link to Register */
        .register-link {
            text-align: center;
            margin-top: 20px;
        }

        .register-link a {
            color: #5a3e36;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
            }

            .login-left, .login-right {
                padding: 30px;
            }

            .login-left {
                border-bottom-left-radius: 0;
                border-bottom-right-radius: 0;
            }

            .login-right {
                border-top-left-radius: 0;
                border-top-right-radius: 0;
            }

            .login-left .logo img {
                width: 90px; /* Ukuran lebih kecil di mobile */
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-wrapper">
        <!-- Left Side (Branding) -->
        <div class="login-left">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
            </div>
            <h1>Welcome Back</h1>
            <p>Membangun Kepercayaan, Menyelesaikan Proyek.</p>
        </div>

        <!-- Right Side (Form) -->
        <div class="login-right">
            <h2>Login</h2>
            @if ($errors->any())
                <div class="error-message">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Login</button>
            </form>
            <div class="register-link">
                <p>Don't have an account? <a href="{{ route('register') }}">Sign Up</a></p>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function (e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (!email || !password) {
                e.preventDefault();
                alert('Email dan password harus diisi!');
            }
        });
    </script>
</body>
</html>
