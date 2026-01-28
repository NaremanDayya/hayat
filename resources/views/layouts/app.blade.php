<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'مخيم حياة النويري' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #34495e;
            --accent: #e67e22;
            --bg: #f8f9fa;
            --card-bg: rgba(255, 255, 255, 0.9);
            --text: #2c3e50;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        nav {
            background-color: var(--primary);
            color: white;
            padding: 0.5rem 2rem;
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-bottom: 3px solid var(--accent);
        }

        .nav-brand {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            justify-content: center;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--accent);
        }

        .card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th, td {
            padding: 12px;
            text-align: right;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: var(--secondary);
            color: white;
            position: sticky;
            top: 0;
        }

        tr:hover {
            background-color: rgba(230, 126, 34, 0.05);
        }

        .btn {
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background-color: var(--accent);
            color: white;
        }

        .btn-primary:hover {
            background-color: #d35400;
            transform: translateY(-2px);
        }

        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
        }

        .badge-male { background-color: #3498db; color: white; }
        .badge-female { background-color: #e91e63; color: white; }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .modal-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            z-index: 1000;
            display: none;
        }

        .overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            display: none;
            z-index: 999;
        }

        h1, h2, h3 { color: var(--primary); }

        /* RTL Specifics */
        [dir="rtl"] .nav-links {
            padding: 0;
        }
    </style>
    @livewireStyles
</head>
<body>
    <nav>
        <div class="nav-brand">مخيم حياة النويري</div>
        <div class="nav-links">
            <a href="dashboard/families">العائلات</a>
            <a href="dashboard/sons">الأبناء الذكور</a>
            <a href="dashboard/daughters">الأبناء الإناث</a>
            @auth
            <a href="/create-family" class="btn btn-primary">إضافة عائلة</a>
            @endauth
        </div>
        <div style="text-align: left; display: flex; justify-content: flex-end; gap: 10px;">
            @auth
                <form method="POST" action="/logout" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn" style="background: rgba(255,255,255,0.1); color: white;">تسجيل الخروج</button>
                </form>
            @else
                <a href="/login" class="btn" style="background: rgba(255,255,255,0.1); color: white;">تسجيل الدخول</a>
            @endauth
        </div>
    </nav>

    <div class="container">
        {{ $slot }}
    </div>

    @livewireScripts
    <script>
        window.addEventListener('swal', event => {
            Swal.fire({
                title: event.detail[0].title,
                text: event.detail[0].text,
                icon: event.detail[0].icon,
                confirmButtonText: 'حسناً'
            });
        });
    </script>
</body>
</html>
