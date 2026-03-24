<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CyberNet ISP</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=orbitron:400,500,600,700,800,900|inter:400,500,600" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #000000 0%, #001429 50%, #001F3F 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #ffffff;
        }
        
        .login-container {
            background: rgba(0, 31, 63, 0.95);
            border: 2px solid #00D9FF;
            border-radius: 16px;
            padding: 3rem;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 20px 60px rgba(0, 217, 255, 0.3);
        }
        
        .logo {
            font-family: 'Orbitron', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            color: #00D9FF;
            text-shadow: 0 0 20px #00D9FF;
            text-align: center;
            margin-bottom: 0.5rem;
        }
        
        h2 {
            font-family: 'Orbitron', sans-serif;
            font-size: 2rem;
            color: #00D9FF;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        
        .subtitle {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .error {
            background: rgba(255, 0, 0, 0.1);
            border: 1px solid rgba(255, 0, 0, 0.3);
            color: #ff6b6b;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            color: #00D9FF;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .form-group input {
            width: 100%;
            padding: 1rem;
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(0, 217, 255, 0.3);
            border-radius: 8px;
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s;
            outline: none;
        }
        
        .form-group input:focus {
            border-color: #00D9FF;
            box-shadow: 0 0 15px rgba(0, 217, 255, 0.3);
            background: rgba(0, 0, 0, 0.7);
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .forgot-password {
            color: #00D9FF;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .forgot-password:hover {
            text-shadow: 0 0 10px rgba(0, 217, 255, 0.5);
        }
        
        .btn-login {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #0099FF, #00D9FF);
            border: none;
            border-radius: 8px;
            color: #000000;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 0 20px rgba(0, 217, 255, 0.5);
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 30px rgba(0, 217, 255, 0.7);
        }
        
        .register-link {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 2rem;
            font-size: 0.95rem;
        }
        
        .register-link a, .back-link {
            color: #00D9FF;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .register-link a:hover, .back-link:hover {
            text-shadow: 0 0 10px rgba(0, 217, 255, 0.5);
        }
        
        .back-link {
            display: inline-block;
            margin-top: 1rem;
        }
        
        .success {
            background: rgba(0, 255, 0, 0.1);
            border: 1px solid rgba(0, 255, 0, 0.3);
            color: #4ade80;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">CYBERNET ISP</div>
        <h2>Welcome Back</h2>
        <p class="subtitle">Access your CyberNet account</p>
        
        @if (session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif
        
        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            
            <div class="remember-forgot">
                <label class="remember-me">
                    <input type="checkbox" name="remember">
                    <span>Remember me</span>
                </label>
                <a href="{{ route('password.request') }}" class="forgot-password">Forgot password?</a>
            </div>
            
            <button type="submit" class="btn-login">Sign In</button>
            
            <div class="register-link">
                Don't have an account? <a href="{{ route('register') }}">Create one now</a>
            </div>
            
            <div class="register-link">
                <a href="/" class="back-link">← Back to Home</a>
            </div>
        </form>
    </div>
</body>
</html>
