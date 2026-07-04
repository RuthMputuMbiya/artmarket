<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Marché Artistiques')</title>

    <!-- ===== FONTS ===== -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- ===== FONT AWESOME ===== -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- ===== VITE ===== -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ============================================
                   RESET & BASE
                ============================================ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #0a0a0a;
            color: #ffffff;
            min-height: 100vh;
        }

        /* ============================================
                   COULEURS
                ============================================ */
        :root {
            --primary: #6C3CE1;
            --primary-dark: #5A2FC2;
            --primary-light: #8B6CE6;
            --secondary: #FF6B6B;
            --success: #2ECC71;
            --danger: #E74C3C;
            --gray: #8899AA;
        }

        /* ============================================
                   ALERTES
                ============================================ */
        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: rgba(46, 204, 113, 0.15);
            border: 1px solid rgba(46, 204, 113, 0.2);
            color: #2ECC71;
        }

        .alert-danger {
            background: rgba(231, 76, 60, 0.15);
            border: 1px solid rgba(231, 76, 60, 0.2);
            color: #E74C3C;
        }

        .alert-warning {
            background: rgba(243, 156, 18, 0.15);
            border: 1px solid rgba(243, 156, 18, 0.2);
            color: #F39C12;
        }

        /* ============================================
                   BOUTONS
                ============================================ */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 14px 36px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 30px rgba(108, 60, 225, 0.35);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(108, 60, 225, 0.5);
            color: white;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.15);
            padding: 14px 36px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--primary-light);
            transform: translateY(-3px);
        }

        /* ============================================
                   CARTE
                ============================================ */
        .card {
            background: #14141e;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
        }

        /* ============================================
                   RESPONSIVE
                ============================================ */
        @media (max-width: 768px) {
            .card {
                padding: 24px;
            }
        }

        @media (max-width: 480px) {
            .card {
                padding: 16px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- ===== ALERTES ===== -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
        </div>
    @endif

    <!-- ===== CONTENU ===== -->
    @yield('content')

    @stack('scripts')

</body>
</html>