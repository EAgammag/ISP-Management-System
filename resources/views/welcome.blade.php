<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CyberNet ISP - High-Speed Internet Solutions</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=orbitron:400,500,600,700,800,900|inter:400,500,600" rel="stylesheet" />
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --navy-blue: #001F3F;
            --navy-blue-dark: #001429;
            --navy-blue-light: #003366;
            --cyber-blue: #00D9FF;
            --black: #000000;
            --electric-blue: #0099FF;
            --accent-glow: #00FFFF;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--black) 0%, var(--navy-blue-dark) 50%, var(--navy-blue) 100%);
            min-height: 100vh;
            color: #ffffff;
            overflow-x: hidden;
            position: relative;
        }
        
        /* Animated background grid */
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
        }
        
        @keyframes gridScroll {
            0% { transform: translateY(0); }
            100% { transform: translateY(50px); }
        }
        
        /* Glowing particles */
        .particle {
            position: fixed;
            width: 4px;
            height: 4px;
            background: var(--cyber-blue);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--cyber-blue);
            opacity: 0;
            animation: float 8s infinite;
        }
        
        @keyframes float {
            0%, 100% { opacity: 0; transform: translateY(0) translateX(0); }
            50% { opacity: 1; }
        }
        
        /* Navigation */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 1.5rem 5%;
            background: rgba(0, 20, 41, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 217, 255, 0.2);
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--cyber-blue);
            text-shadow: 0 0 20px var(--cyber-blue);
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }
        
        .nav-links a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            padding: 0.5rem 1.5rem;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        
        .nav-links a:hover {
            color: var(--cyber-blue);
            border-color: var(--cyber-blue);
            box-shadow: 0 0 15px rgba(0, 217, 255, 0.3);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--electric-blue), var(--cyber-blue));
            border: none !important;
            padding: 0.75rem 2rem;
            border-radius: 4px;
            font-weight: 600;
            color: var(--black) !important;
            box-shadow: 0 0 20px rgba(0, 217, 255, 0.5);
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 25px rgba(0, 217, 255, 0.6);
        }
        
        /* Hero Section */
        .hero {
            padding: 10rem 5% 5rem;
            position: relative;
            z-index: 1;
        }
        
        .hero-content {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }
        
        .hero-text h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 4rem;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #ffffff, var(--cyber-blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero-text .tagline {
            font-size: 1.4rem;
            color: var(--cyber-blue);
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .hero-text p {
            font-size: 1.2rem;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 2.5rem;
        }
        
        .hero-buttons {
            display: flex;
            gap: 1.5rem;
        }
        
        .btn {
            padding: 1rem 2.5rem;
            border-radius: 4px;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
            cursor: pointer;
            border: none;
        }
        
        .btn-cyber {
            background: linear-gradient(135deg, var(--electric-blue), var(--cyber-blue));
            color: var(--black);
            box-shadow: 0 0 30px rgba(0, 217, 255, 0.5);
        }
        
        .btn-cyber:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 35px rgba(0, 217, 255, 0.7);
        }
        
        .btn-outline {
            background: transparent;
            color: var(--cyber-blue);
            border: 2px solid var(--cyber-blue);
        }
        
        .btn-outline:hover {
            background: var(--cyber-blue);
            color: var(--black);
            box-shadow: 0 0 20px rgba(0, 217, 255, 0.5);
        }
        
        /* Hero Visual */
        .hero-visual {
            position: relative;
            height: 500px;
        }
        
        .cyber-circle {
            position: absolute;
            border: 2px solid var(--cyber-blue);
            border-radius: 50%;
            box-shadow: 0 0 30px rgba(0, 217, 255, 0.3);
            animation: pulse 3s infinite;
        }
        
        .cyber-circle:nth-child(1) {
            width: 400px;
            height: 400px;
            top: 50px;
            left: 50px;
            animation-delay: 0s;
        }
        
        .cyber-circle:nth-child(2) {
            width: 300px;
            height: 300px;
            top: 100px;
            left: 100px;
            animation-delay: 1s;
        }
        
        .cyber-circle:nth-child(3) {
            width: 200px;
            height: 200px;
            top: 150px;
            left: 150px;
            animation-delay: 2s;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.8;
            }
        }
        
        /* Features Section */
        .features {
            padding: 5rem 5%;
            background: rgba(0, 20, 41, 0.6);
        }
        
        .features-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .section-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 3rem;
            font-weight: 800;
            text-align: center;
            color: var(--cyber-blue);
            margin-bottom: 1rem;
        }
        
        .section-subtitle {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.2rem;
            margin-bottom: 4rem;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .feature-card {
            background: rgba(0, 31, 63, 0.8);
            border: 1px solid rgba(0, 217, 255, 0.3);
            border-radius: 12px;
            padding: 2.5rem;
            transition: all 0.3s;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            border-color: var(--cyber-blue);
            box-shadow: 0 10px 40px rgba(0, 217, 255, 0.3);
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1.5rem;
        }
        
        .feature-card h3 {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.5rem;
            color: var(--cyber-blue);
            margin-bottom: 1rem;
        }
        
        .feature-card p {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
        }
        
        /* Footer */
        footer {
            background: rgba(0, 20, 41, 0.9);
            padding: 3rem 5%;
            border-top: 1px solid rgba(0, 217, 255, 0.2);
            text-align: center;
        }
        
        footer p {
            color: rgba(255, 255, 255, 0.6);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .hero-text h1 {
                font-size: 2.5rem;
            }
            
            .hero-visual {
                height: 300px;
            }
            
            .cyber-circle:nth-child(1) {
                width: 250px;
                height: 250px;
            }
            
            .cyber-circle:nth-child(2) {
                width: 180px;
                height: 180px;
            }
            
            .cyber-circle:nth-child(3) {
                width: 110px;
                height: 110px;
            }
            
            .nav-links {
                gap: 1rem;
            }
            
            .hero-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="cyber-grid"></div>
    
    <!-- Particles -->
    <div class="particle" style="top: 10%; left: 20%; animation-delay: 0s;"></div>
    <div class="particle" style="top: 30%; left: 70%; animation-delay: 2s;"></div>
    <div class="particle" style="top: 60%; left: 40%; animation-delay: 4s;"></div>
    <div class="particle" style="top: 80%; left: 80%; animation-delay: 6s;"></div>
    
    <!-- Navigation -->
    <nav>
        <div class="logo">CYBERNET ISP</div>
        <div class="nav-links">
            @auth
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: #ffffff; cursor: pointer; font: inherit; font-weight: 500; padding: 0.5rem 1.5rem;">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}" class="btn-primary">Get Started</a>
            @endauth
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <div class="tagline">🚀 NEXT-GEN CONNECTIVITY</div>
                <h1>Lightning-Fast Internet for the Digital Age</h1>
                <p>Experience blazing speeds up to 1 Gbps with CyberNet ISP. Premium fiber-optic technology delivering seamless connectivity for your home and business.</p>
                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn btn-cyber">Start Free Trial</a>
                    <a href="#features" class="btn btn-outline">Explore Features</a>
                </div>
            </div>
            <div class="hero-visual">
                <div class="cyber-circle"></div>
                <div class="cyber-circle"></div>
                <div class="cyber-circle"></div>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section id="features" class="features">
        <div class="features-container">
            <h2 class="section-title">Why Choose CyberNet?</h2>
            <p class="section-subtitle">Industry-leading technology and unmatched customer service</p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Ultra-Fast Speeds</h3>
                    <p>Experience lightning-fast download and upload speeds up to 1 Gbps. Stream, game, and work without interruption.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🛡️</div>
                    <h3>Secure Connection</h3>
                    <p>Advanced encryption and security protocols keep your data safe. Browse with confidence and peace of mind.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">📡</div>
                    <h3>99.9% Uptime</h3>
                    <p>Industry-leading reliability ensures you're always connected. Backed by our service level agreement.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">💬</div>
                    <h3>24/7 Support</h3>
                    <p>Expert technical support available around the clock. We're here whenever you need assistance.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">💰</div>
                    <h3>Competitive Pricing</h3>
                    <p>Transparent pricing with no hidden fees. Get premium service at an affordable price point.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🌐</div>
                    <h3>Wide Coverage</h3>
                    <p>Expanding fiber network reaching more areas every month. Check if we're available in your location.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer>
        <p>&copy; {{ date('Y') }} CyberNet ISP. All rights reserved. Powering the future of connectivity.</p>
    </footer>
</body>
</html>
