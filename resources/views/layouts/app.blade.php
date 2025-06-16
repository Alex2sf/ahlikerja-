<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ahli Kerja')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBxvfg7jQfL7g8bV7M7N2q2jW2jY7g5v4w9v5g6u7v8w9v5g6u7v8w9v5g6u" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: #333;
            line-height: 1.6;
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
            flex: 1;
        }

        /* Header Styles */
        .header {
            background-color: #ffffff;
            padding: 15px 0;
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
            align-items: center;
        }

        .nav ul li {
            margin-left: 20px;
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

        .logout-item {
            margin-left: 20px;
        }

        .logout-form button {
            background-color: #d4af37;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .logout-form button:hover {
            background-color: #b5942e;
        }

        /* Hamburger Menu */
        .menu-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
            gap: 5px;
        }

        .menu-toggle span {
            height: 3px;
            width: 25px;
            background-color: #333;
            transition: all 0.3s ease;
        }

        .menu-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .menu-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -7px);
        }

        /* Dropdown Styles */
        .dropdown {
            position: relative;
        }

        .dropbtn {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
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
            top: 100%;
            left: 0;
        }

        .dropdown-content a {
            color: #333;
            padding: 10px 15px;
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

        /* Notification Bell */
        .notification-bell {
            position: relative;
            display: flex;
            align-items: center;
        }

        .notification-bell i {
            font-size: 18px;
        }

        .notification-count {
            position: absolute;
            top: -6px;
            right: -6px;
            background-color: #dc3545;
            color: #fff;
            font-size: 10px;
            font-weight: bold;
            padding: 2px 5px;
            border-radius: 50%;
        }

        .notification-dropdown {
            max-height: 250px;
            overflow-y: auto;
            width: 280px;
            right: 0;
            left: auto;
        }

        .notification-item {
            padding: 10px 15px;
            border-bottom: 1px solid #e0d8c9;
            transition: background-color 0.3s ease;
        }

        .notification-item.unread {
            background-color: #f8d7da;
        }

        .notification-item.read {
            background-color: #fff;
        }

        .notification-item:hover {
            background-color: #f5f5f5;
        }

        .notification-item p {
            margin: 0;
            font-size: 13px;
            color: #5a3e36;
        }

        .notification-item small {
            display: block;
            color: #6b5848;
            font-size: 11px;
            margin-top: 5px;
        }

        .no-notification {
            padding: 10px 15px;
            text-align: center;
            color: #6b5848;
            font-size: 13px;
        }

        .mark-all-read {
            display: block;
            text-align: center;
            padding: 8px;
            color: #a8c3b8;
            font-size: 13px;
            text-decoration: none;
            border-top: 1px solid #e0d8c9;
        }

        .mark-all-read:hover {
            background-color: #f5f5f5;
        }

        /* Footer Styles */
        .footer {
            background-color: #a8c3b8;
            padding: 30px 0 20px;
            color: #fff;
            margin-top: auto;
        }

        .footer .container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            align-items: flex-start;
        }

        .footer-info h3, .footer-social h3 {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            margin-bottom: 15px;
        }

        .footer-info p {
            font-size: 14px;
            margin-bottom: 8px;
        }

        .footer-social .social-icons {
            display: flex;
            gap: 12px;
        }

        .footer-social .social-icons img {
            width: 22px;
            height: 22px;
            transition: transform 0.3s ease;
        }

        .footer-social .social-icons img:hover {
            transform: translateY(-3px);
        }

        .footer-copyright {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .footer-copyright p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .container {
                padding: 0 10px;
            }

            .logo-text {
                font-size: 22px;
            }

            .logo img {
                height: 35px;
            }

            .nav ul li {
                margin-left: 15px;
            }

            .nav ul li a {
                font-size: 15px;
            }

            .logout-form button {
                padding: 7px 14px;
                font-size: 13px;
            }

            .footer .container {
                grid-template-columns: 1fr 1fr;
                gap: 15px;
            }

            .footer-info h3, .footer-social h3 {
                font-size: 20px;
            }

            .footer-info p {
                font-size: 13px;
            }

            .footer-social .social-icons img {
                width: 20px;
                height: 20px;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 10px 0;
            }

            .nav {
                display: none;
                position: absolute;
                top: 60px;
                right: 15px;
                background-color: #ffffff;
                width: 250px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                border-radius: 5px;
                padding: 10px 0;
                z-index: 999;
            }

            .nav.active {
                display: block;
            }

            .nav ul {
                flex-direction: column;
                align-items: flex-start;
                padding: 10px;
            }

            .nav ul li {
                margin: 8px 0;
                width: 100%;
            }

            .nav ul li a {
                display: block;
                padding: 8px 10px;
                font-size: 14px;
            }

            .logout-item {
                margin-left: 0;
                margin-top: 10px;
                width: 100%;
            }

            .logout-form button {
                width: 100%;
                padding: 10px;
                font-size: 14px;
            }

            .menu-toggle {
                display: flex;
            }

            .dropdown-content {
                position: static;
                box-shadow: none;
                background-color: #f5f5f5;
                padding: 5px 10px;
                width: 100%;
                border-radius: 0;
            }

            .dropdown-content a {
                font-size: 13px;
                padding: 8px 15px;
            }

            .notification-dropdown {
                width: 100%;
                max-height: 200px;
                right: 0;
            }

            .notification-item p {
                font-size: 12px;
            }

            .notification-item small {
                font-size: 10px;
            }

            .footer .container {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .footer-info, .footer-social {
                text-align: center;
            }

            .footer-social .social-icons {
                justify-content: center;
            }

            .footer-info h3, .footer-social h3 {
                font-size: 18px;
            }

            .footer-info p {
                font-size: 12px;
            }

            .footer-copyright p {
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            .logo-text {
                font-size: 20px;
            }

            .logo img {
                height: 30px;
            }

            .nav {
                width: 100%;
                right: 0;
                top: 50px;
            }

            .nav ul li a {
                font-size: 13px;
            }

            .logout-form button {
                font-size: 13px;
                padding: 8px;
            }

            .notification-bell i {
                font-size: 16px;
            }

            .notification-count {
                font-size: 9px;
                padding: 1px 4px;
            }

            .footer {
                padding: 20px 0 15px;
            }

            .footer-info h3, .footer-social h3 {
                font-size: 16px;
            }

            .footer-info p {
                font-size: 11px;
            }

            .footer-social .social-icons img {
                width: 18px;
                height: 18px;
            }

            .footer-copyright p {
                font-size: 11px;
            }
        }
    </style>
</head>
<body>
    @include('layouts.partials.header')

    @yield('content')

    @include('layouts.partials.footer')

    <script>
        // Toggle menu for mobile (already handled in header.blade.php)
    </script>
</body>
</html>
