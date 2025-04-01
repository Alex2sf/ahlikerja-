<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nama Brand')</title>
    <style>
        /* General Styles */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: #333;
            line-height: 1.6;
            background-color: #f5f5f5; /* Latar seragam */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            flex: 1;
        }

        /* Header Styles */
        .header {
            background-color: #ffffff;
            padding: 20px 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 40px;
            margin-right: 10px;
        }

        .logo-text {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            color: #5a3e36;
            font-weight: bold;
        }

        .nav {
            display: flex;
            align-items: center;
        }

        .nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .nav ul li {
            margin-left: 25px;
        }

        .nav ul li a {
            text-decoration: none;
            color: #333;
            font-size: 16px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav ul li a:hover {
            color: #d4af37;
        }

        .logout-form {
            margin-left: 25px;
        }

        .logout-form button {
            background-color: #d4af37;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .logout-form button:hover {
            background-color: #b5942e;
        }

        /* Hamburger Menu for Mobile */
        .menu-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }

        .menu-toggle span {
            height: 3px;
            width: 25px;
            background-color: #333;
            margin: 4px 0;
            transition: all 0.3s ease;
        }

        /* Dropdown Styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #ffffff;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 5px;
            padding: 10px 0;
        }

        .dropdown-content a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .dropdown-content a:hover {
            background-color: #f5f5f5;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropbtn {
            cursor: pointer;
        }

        /* Responsive Design untuk Navbar */
        @media (max-width: 768px) {
            .nav {
                display: none;
                flex-direction: column;
                background-color: #ffffff;
                position: absolute;
                top: 70px;
                right: 20px;
                width: 200px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                border-radius: 5px;
                padding: 10px 0;
            }

            .nav.active {
                display: flex;
            }

            .nav ul {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav ul li {
                margin: 10px 0;
            }

            .logout-form {
                margin-left: 0;
                margin-top: 10px;
            }

            .menu-toggle {
                display: flex;
            }
        }

        /* Footer Styles */
        .footer {
            background-color: #a8c3b8;
            padding: 40px 0 20px 0;
            color: #fff;
            margin-top: auto;
        }

        .footer .container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .footer-info h3, .footer-social h3 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .footer-info p {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .footer-social .social-icons {
            display: flex;
            gap: 15px;
        }

        .footer-social .social-icons img {
            width: 24px;
            height: 24px;
            transition: transform 0.3s ease;
        }

        .footer-social .social-icons img:hover {
            transform: translateY(-5px);
        }

        .footer-copyright {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .footer-copyright p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Responsive Design untuk Footer */
        @media (max-width: 768px) {
            .footer .container {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    @include('layouts.partials.header')

    @yield('content')

    @include('layouts.partials.footer')

    <script>
        // Toggle menu for mobile
        const menuToggle = document.getElementById('menuToggle');
        const nav = document.getElementById('nav');

        menuToggle.addEventListener('click', () => {
            nav.classList.toggle('active');
        });
    </script>
</body>
</html>
