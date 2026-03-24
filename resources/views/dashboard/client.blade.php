<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CyberNet ISP</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=orbitron:400,500,600,700,800,900|inter:400,500,600" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
            --navy-blue: #001F3F;
            --navy-blue-dark: #001429;
            --cyber-blue: #00D9FF;
            --black: #000000;
            --electric-blue: #0099FF;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--black) 0%, var(--navy-blue-dark) 50%, var(--navy-blue) 100%);
            min-height: 100vh;
            color: #ffffff;
            padding: 2rem;
        }
        
        .cyber-grid {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(0, 217, 255, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 217, 255, 0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            opacity: 0.3;
            animation: gridScroll 20s linear infinite;
            pointer-events: none;
            z-index: 0;
        }
        
        @keyframes gridScroll {
            0% { transform: translateY(0); }
            100% { transform: translateY(50px); }
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            background: rgba(0, 31, 63, 0.8);
            padding: 1.5rem 2rem;
            border-radius: 15px;
            border: 2px solid var(--cyber-blue);
            box-shadow: 0 10px 30px rgba(0, 217, 255, 0.3);
        }
        
        .logo {
            font-family: 'Orbitron', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            color: var(--cyber-blue);
            text-shadow: 0 0 20px rgba(0, 217, 255, 0.5);
        }
        
        .nav {
            display: flex;
            gap: 2rem;
            align-items: center;
        }
        
        .nav a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }
        
        .nav a:hover {
            color: var(--cyber-blue);
            background: rgba(0, 217, 255, 0.1);
        }
        
        .logout-btn {
            background: linear-gradient(135deg, #dc2626, #991b1b);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 38, 38, 0.4);
        }
        
        h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--cyber-blue);
            text-shadow: 0 0 20px rgba(0, 217, 255, 0.5);
        }
        
        .welcome-text {
            font-size: 1.5rem;
            margin-bottom: 3rem;
            color: #e5e7eb;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .card {
            background: rgba(0, 31, 63, 0.8);
            border: 2px solid var(--cyber-blue);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 217, 255, 0.2);
            transition: all 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 217, 255, 0.4);
        }
        
        .card-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .card h3 {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--cyber-blue);
        }
        
        .card p {
            color: #9ca3af;
            line-height: 1.6;
        }
        
        .info-section {
            background: rgba(0, 31, 63, 0.8);
            border: 2px solid var(--cyber-blue);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 217, 255, 0.2);
        }
        
        .info-section h2 {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            color: var(--cyber-blue);
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .info-label {
            color: #9ca3af;
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .info-value {
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .action-btn {
            display: inline-block;
            background: linear-gradient(135deg, var(--electric-blue), var(--cyber-blue));
            color: var(--black);
            padding: 1rem 2rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
            margin-top: 1rem;
            box-shadow: 0 5px 15px rgba(0, 217, 255, 0.3);
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 217, 255, 0.5);
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 1.5rem;
                text-align: center;
            }
            
            .nav {
                flex-direction: column;
                gap: 1rem;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .welcome-text {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="cyber-grid"></div>
    
    <div class="container">
        <div class="header">
            <div class="logo">CYBERNET ISP</div>
            <nav class="nav">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </nav>
        </div>
        
        <h1>Welcome Back!</h1>
        <p class="welcome-text">Hello, {{ $user->name }}</p>
        
        <div class="dashboard-grid">
            <div class="card">
                <div class="card-icon">📊</div>
                <h3>My Account</h3>
                <p>View and manage your account information, update your profile, and change your password.</p>
            </div>
            
            <div class="card">
                <div class="card-icon">🌐</div>
                <h3>Internet Services</h3>
                <p>View your current internet plan, check usage statistics, and upgrade your service.</p>
            </div>
            
            <div class="card">
                <div class="card-icon">💳</div>
                <h3>Billing & Payments</h3>
                <p>Review your billing history, make payments, and manage payment methods.</p>
            </div>
            
            <div class="card">
                <div class="card-icon">📞</div>
                <h3>Support</h3>
                <p>Contact our support team, submit tickets, and get help with your service.</p>
            </div>
        </div>
        
        <div class="info-section">
            <h2>Account Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Full Name</span>
                    <span class="info-value">{{ $user->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email Address</span>
                    <span class="info-value">{{ $user->email }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Account Type</span>
                    <span class="info-value">{{ ucfirst($user->role) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Member Since</span>
                    <span class="info-value">{{ $user->created_at->format('F d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
