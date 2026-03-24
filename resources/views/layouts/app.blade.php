<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ISP Management System')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|poppins:400,500,600,700" rel="stylesheet" />
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #FFFFFF 0%, #FFFAF0 50%, #FFF5E6 100%);
            min-height: 100vh;
            color: #333333;
        }
        
        /* Header Styles */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid #E8D5C4;
            padding: 1rem 2rem;
            box-shadow: 0 4px 20px rgba(232, 213, 196, 0.2);
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #8B6F47;
            letter-spacing: 1px;
            text-decoration: none;
        }
        
        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }
        
        .nav-links a {
            color: #8B6F47;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }
        
        .nav-links a:hover {
            background: #FFFAF0;
            color: #D4AF76;
        }
        
        .nav-links .btn-primary {
            background: linear-gradient(135deg, #D4AF76, #C19A6B);
            color: #FFFFFF;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            box-shadow: 0 2px 10px rgba(196, 154, 107, 0.3);
        }
        
        .nav-links .btn-primary:hover {
            background: linear-gradient(135deg, #C19A6B, #AA8352);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(196, 154, 107, 0.4);
        }
        
        /* Main Content Container */
        .main-container {
            padding: 2rem;
            min-height: calc(100vh - 80px);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        /* Card Styles */
        .card {
            background: #FFFFFF;
            border: 2px solid #E8D5C4;
            border-radius: 20px;
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 60px rgba(232, 213, 196, 0.3);
        }
        
        .card-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .card-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            color: #8B6F47;
            margin-bottom: 0.5rem;
        }
        
        .card-subtitle {
            color: #9A826C;
            font-size: 0.95rem;
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            color: #8B6F47;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 1rem;
            background: #FFFAF0;
            border: 2px solid #E8D5C4;
            border-radius: 10px;
            color: #333333;
            font-size: 1rem;
            transition: all 0.3s;
            outline: none;
            font-family: 'Inter', sans-serif;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #D4AF76;
            box-shadow: 0 0 15px rgba(212, 175, 118, 0.2);
            background: #FFFFFF;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        /* Button Styles */
        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #D4AF76, #C19A6B);
            color: #FFFFFF;
            box-shadow: 0 4px 15px rgba(196, 154, 107, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(196, 154, 107, 0.5);
            background: linear-gradient(135deg, #C19A6B, #AA8352);
        }
        
        .btn-secondary {
            background: #FFFFFF;
            color: #8B6F47;
            border: 2px solid #E8D5C4;
        }
        
        .btn-secondary:hover {
            background: #FFFAF0;
            border-color: #D4AF76;
        }
        
        .btn-block {
            width: 100%;
        }
        
        /* Alert Styles */
        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        
        .alert-error {
            background: rgba(255, 0, 0, 0.08);
            border: 1px solid rgba(255, 0, 0, 0.2);
            color: #D84315;
        }
        
        .alert-success {
            background: rgba(76, 175, 80, 0.08);
            border: 1px solid rgba(76, 175, 80, 0.2);
            color: #2E7D32;
        }
        
        .alert-info {
            background: rgba(33, 150, 243, 0.08);
            border: 1px solid rgba(33, 150, 243, 0.2);
            color: #1565C0;
        }
        
        /* Link Styles */
        .text-link {
            color: #8B6F47;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .text-link:hover {
            color: #D4AF76;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-muted {
            color: #9A826C;
        }
        
        .mt-1 { margin-top: 0.5rem; }
        .mt-2 { margin-top: 1rem; }
        .mt-3 { margin-top: 1.5rem; }
        .mt-4 { margin-top: 2rem; }
        
        .mb-1 { margin-bottom: 0.5rem; }
        .mb-2 { margin-bottom: 1rem; }
        .mb-3 { margin-bottom: 1.5rem; }
        .mb-4 { margin-bottom: 2rem; }
        
        /* Footer */
        .footer {
            background: rgba(255, 255, 255, 0.95);
            border-top: 2px solid #E8D5C4;
            padding: 2rem;
            text-align: center;
            color: #9A826C;
            margin-top: auto;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }
            
            .nav-links {
                flex-direction: column;
                gap: 1rem;
            }
            
            .card {
                padding: 2rem;
            }
            
            .main-container {
                padding: 1rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    @if(!request()->is('login', 'register', 'forgot-password'))
    <header class="header">
        <div class="header-content">
            <a href="/" class="logo">ISP MANAGEMENT</a>
            <nav class="nav-links">
                @auth
                    <a href="{{ route('clients.index') }}">Clients</a>
                    <a href="{{ route('payments.index') }}">Payments</a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: #8B6F47; cursor: pointer; font: inherit; font-weight: 500;">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}" class="btn-primary">Sign Up</a>
                @endauth
            </nav>
        </div>
    </header>
    @endif
    
    <main class="main-container">
        @yield('content')
    </main>
    
    @stack('scripts')
</body>
</html>
