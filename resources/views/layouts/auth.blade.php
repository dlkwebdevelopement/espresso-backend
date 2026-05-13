<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Espresso</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
    <style>
        :root {
            --primary: #5d4037;
            --primary-light: #8b6b61;
            --secondary: #d7ccc8;
            --dark: #1a1a1a;
            --dark-alt: #252525;
            --text: #ffffff;
            --text-muted: #a0a0a0;
            --border: rgba(255, 255, 255, 0.1);
        }
        body {
            background: #000;
            color: var(--text);
            font-family: 'Inter', system-ui, sans-serif;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
        }
        .auth-card {
            background: rgba(26, 26, 26, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 32px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            border: 1px solid var(--border);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .auth-header { text-align: center; margin-bottom: 30px; }
        .auth-logo { font-size: 32px; font-weight: 800; color: var(--primary); letter-spacing: -1px; margin-bottom: 10px; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase; }
        .form-control {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            padding: 12px 16px;
            border-radius: 12px;
            color: #fff;
            transition: all 0.3s;
            box-sizing: border-box;
        }
        .form-control:focus { outline: none; border-color: var(--primary); background: rgba(255,255,255,0.1); }
        .btn-auth {
            width: 100%;
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        .btn-auth:hover { background: var(--primary-light); transform: translateY(-1px); }
        .auth-footer { text-align: center; margin-top: 25px; font-size: 14px; color: var(--text-muted); }
        .auth-link { color: var(--primary); text-decoration: none; font-weight: 600; }
        .tabs { display: flex; gap: 10px; margin-bottom: 25px; background: rgba(255,255,255,0.05); padding: 5px; border-radius: 14px; }
        .tab { flex: 1; text-align: center; padding: 10px; border-radius: 10px; cursor: pointer; font-size: 13px; font-weight: 600; transition: all 0.3s; }
        .tab.active { background: var(--primary); color: #fff; }
    </style>
</head>
<body>
    @yield('content')
    @yield('scripts')
</body>
</html>
