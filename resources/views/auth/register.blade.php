<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CyberNet ISP</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem 0;
            color: #ffffff;
            position: relative;
            overflow: hidden;
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
            z-index: 0;
        }
        
        @keyframes gridScroll {
            0% { transform: translateY(0); }
            100% { transform: translateY(50px); }
        }
        
        .register-container {
            background: #F2F3F4;
            border: 2px solid var(--cyber-blue);
            border-radius: 20px;
            padding: 3rem 7rem;
            width: 90%;
            max-width: 1000px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 217, 255, 0.5);
            position: relative;
            z-index: 1;
        }

        .register-container::-webkit-scrollbar {
            width: 8px;
        }

        .register-container::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .register-container::-webkit-scrollbar-thumb {
            background: var(--cyber-blue);
            border-radius: 10px;
        }

        .register-container::-webkit-scrollbar-thumb:hover {
            background: var(--electric-blue);
        }
        
        .logo {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--navy-blue);
            text-shadow: 0 2px 4px rgba(0, 153, 255, 0.3);
            text-align: center;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        
        h2 {
            font-family: 'Orbitron', sans-serif;
            font-size: 2rem;
            color: var(--navy-blue);
            margin-bottom: 0.75rem;
            text-align: center;
        }
        
        .subtitle {
            color: #666666;
            margin-bottom: 2.5rem;
            text-align: center;
            font-size: 1.05rem;
        }
        
        .error {
            background: rgba(255, 0, 0, 0.1);
            border: 1px solid rgba(255, 0, 0, 0.3);
            color: #ff6b6b;
            padding: 1rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            align-items: start;
            margin-bottom: 1.5rem;
        }

        .form-row .form-group {
            margin-bottom: 0;
        }
        
        .form-group label {
            display: block;
            color: var(--navy-blue);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-group label.hidden {
            visibility: hidden;
            margin-bottom: 0.5rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #f8f9fa;
            border: 2px solid #d1d5db;
            border-radius: 8px;
            color: #1f2937;
            font-size: 1rem;
            line-height: 1.5;
            transition: all 0.3s;
            outline: none;
        }

        .form-group input::placeholder {
            font-size: 0.95rem;
            color: #9ca3af;
        }

        .form-group select {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #f8f9fa;
            border: 2px solid #d1d5db;
            border-radius: 8px;
            color: #1f2937;
            font-size: 1rem;
            line-height: 1.5;
            transition: all 0.3s;
            outline: none;
            cursor: pointer;
        }
        
        .form-group input.error,
        .form-group select.error {
            border-color: rgba(255, 0, 0, 0.5);
        }
        
        .field-error {
            color: #ff6b6b;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: block;
            padding-left: 0.25rem;
        }
        
        .success {
            background: rgba(0, 255, 0, 0.1);
            border: 1px solid rgba(0, 255, 0, 0.3);
            color: #4ade80;
            padding: 1rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        
        .password-requirements {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 0.5rem;
            padding-left: 0.25rem;
            display: block;
        }
        
        .form-group input:focus {
            border-color: var(--cyber-blue);
            box-shadow: 0 0 0 3px rgba(0, 217, 255, 0.2);
            background: #ffffff;
        }

        .form-group select:focus {
            border-color: var(--cyber-blue);
            box-shadow: 0 0 0 3px rgba(0, 217, 255, 0.2);
            background: #ffffff;
        }
        
        .btn-register {
            width: 100%;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--electric-blue), var(--cyber-blue));
            border: none;
            border-radius: 12px;
            color: var(--black);
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            box-shadow: 0 4px 20px rgba(0, 217, 255, 0.4);
            margin-top: 1.5rem;
            position: relative;
            z-index: 10;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 30px rgba(0, 217, 255, 0.7);
        }
        
        .login-link {
            text-align: center;
            color: #4b5563;
            margin-top: 1.5rem;
            font-size: 1rem;
            padding: 0.5rem 0;
        }
        
        .login-link a, .back-link {
            color: var(--cyber-blue);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .login-link a:hover, .back-link:hover {
            color: var(--navy-blue);
            text-decoration: underline;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .register-container {
                padding: 2.5rem 2rem;
                max-width: 95%;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }
            
            .logo {
                font-size: 1.5rem;
            }
            
            h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="cyber-grid"></div>
    
    <div class="register-container">
        <div class="logo">CYBERNET ISP</div>
        <h2>Internet Registration</h2>
        <p class="subtitle">Thank you for choosing us.</p>
        
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
        
        <form method="POST" action="{{ route('register') }}" novalidate>
            @csrf
            
            <div class="form-group">
                <label for="name">Full Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}" 
                    placeholder="Enter your full name" 
                    required 
                    autofocus
                    minlength="3"
                    maxlength="255"
                    class="@error('name') error @enderror"
                >
                <small class="password-requirements">At least 3 characters, letters and spaces only</small>
                @error('name')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    placeholder="example@email.com" 
                    required
                    maxlength="255"
                    class="@error('email') error @enderror"
                >
                @error('email')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="display: none;">
                <select 
                    id="state" 
                    name="state" 
                    class="@error('state') error @enderror"
                >
                    <option value="">State / Province</option>
                    <option value="Abra" {{ old('state') == 'Abra' ? 'selected' : '' }}>Abra</option>
                    <option value="Agusan del Norte" {{ old('state') == 'Agusan del Norte' ? 'selected' : '' }}>Agusan del Norte</option>
                    <option value="Agusan del Sur" {{ old('state') == 'Agusan del Sur' ? 'selected' : '' }}>Agusan del Sur</option>
                    <option value="Aklan" {{ old('state') == 'Aklan' ? 'selected' : '' }}>Aklan</option>
                    <option value="Albay" {{ old('state') == 'Albay' ? 'selected' : '' }}>Albay</option>
                    <option value="Antique" {{ old('state') == 'Antique' ? 'selected' : '' }}>Antique</option>
                    <option value="Apayao" {{ old('state') == 'Apayao' ? 'selected' : '' }}>Apayao</option>
                    <option value="Aurora" {{ old('state') == 'Aurora' ? 'selected' : '' }}>Aurora</option>
                    <option value="Basilan" {{ old('state') == 'Basilan' ? 'selected' : '' }}>Basilan</option>
                    <option value="Bataan" {{ old('state') == 'Bataan' ? 'selected' : '' }}>Bataan</option>
                    <option value="Batanes" {{ old('state') == 'Batanes' ? 'selected' : '' }}>Batanes</option>
                    <option value="Batangas" {{ old('state') == 'Batangas' ? 'selected' : '' }}>Batangas</option>
                    <option value="Benguet" {{ old('state') == 'Benguet' ? 'selected' : '' }}>Benguet</option>
                    <option value="Biliran" {{ old('state') == 'Biliran' ? 'selected' : '' }}>Biliran</option>
                    <option value="Bohol" {{ old('state') == 'Bohol' ? 'selected' : '' }}>Bohol</option>
                    <option value="Bukidnon" {{ old('state') == 'Bukidnon' ? 'selected' : '' }}>Bukidnon</option>
                    <option value="Bulacan" {{ old('state') == 'Bulacan' ? 'selected' : '' }}>Bulacan</option>
                    <option value="Cagayan" {{ old('state') == 'Cagayan' ? 'selected' : '' }}>Cagayan</option>
                    <option value="Camarines Norte" {{ old('state') == 'Camarines Norte' ? 'selected' : '' }}>Camarines Norte</option>
                    <option value="Camarines Sur" {{ old('state') == 'Camarines Sur' ? 'selected' : '' }}>Camarines Sur</option>
                    <option value="Camiguin" {{ old('state') == 'Camiguin' ? 'selected' : '' }}>Camiguin</option>
                    <option value="Capiz" {{ old('state') == 'Capiz' ? 'selected' : '' }}>Capiz</option>
                    <option value="Catanduanes" {{ old('state') == 'Catanduanes' ? 'selected' : '' }}>Catanduanes</option>
                    <option value="Cavite" {{ old('state') == 'Cavite' ? 'selected' : '' }}>Cavite</option>
                    <option value="Cebu" {{ old('state') == 'Cebu' ? 'selected' : '' }}>Cebu</option>
                    <option value="Cotabato" {{ old('state') == 'Cotabato' ? 'selected' : '' }}>Cotabato</option>
                    <option value="Davao de Oro" {{ old('state') == 'Davao de Oro' ? 'selected' : '' }}>Davao de Oro</option>
                    <option value="Davao del Norte" {{ old('state') == 'Davao del Norte' ? 'selected' : '' }}>Davao del Norte</option>
                    <option value="Davao del Sur" {{ old('state') == 'Davao del Sur' ? 'selected' : '' }}>Davao del Sur</option>
                    <option value="Davao Occidental" {{ old('state') == 'Davao Occidental' ? 'selected' : '' }}>Davao Occidental</option>
                    <option value="Davao Oriental" {{ old('state') == 'Davao Oriental' ? 'selected' : '' }}>Davao Oriental</option>
                    <option value="Dinagat Islands" {{ old('state') == 'Dinagat Islands' ? 'selected' : '' }}>Dinagat Islands</option>
                    <option value="Eastern Samar" {{ old('state') == 'Eastern Samar' ? 'selected' : '' }}>Eastern Samar</option>
                    <option value="Guimaras" {{ old('state') == 'Guimaras' ? 'selected' : '' }}>Guimaras</option>
                    <option value="Ifugao" {{ old('state') == 'Ifugao' ? 'selected' : '' }}>Ifugao</option>
                    <option value="Ilocos Norte" {{ old('state') == 'Ilocos Norte' ? 'selected' : '' }}>Ilocos Norte</option>
                    <option value="Ilocos Sur" {{ old('state') == 'Ilocos Sur' ? 'selected' : '' }}>Ilocos Sur</option>
                    <option value="Iloilo" {{ old('state') == 'Iloilo' ? 'selected' : '' }}>Iloilo</option>
                    <option value="Isabela" {{ old('state') == 'Isabela' ? 'selected' : '' }}>Isabela</option>
                    <option value="Kalinga" {{ old('state') == 'Kalinga' ? 'selected' : '' }}>Kalinga</option>
                    <option value="La Union" {{ old('state') == 'La Union' ? 'selected' : '' }}>La Union</option>
                    <option value="Laguna" {{ old('state') == 'Laguna' ? 'selected' : '' }}>Laguna</option>
                    <option value="Lanao del Norte" {{ old('state') == 'Lanao del Norte' ? 'selected' : '' }}>Lanao del Norte</option>
                    <option value="Lanao del Sur" {{ old('state') == 'Lanao del Sur' ? 'selected' : '' }}>Lanao del Sur</option>
                    <option value="Leyte" {{ old('state') == 'Leyte' ? 'selected' : '' }}>Leyte</option>
                    <option value="Maguindanao" {{ old('state') == 'Maguindanao' ? 'selected' : '' }}>Maguindanao</option>
                    <option value="Marinduque" {{ old('state') == 'Marinduque' ? 'selected' : '' }}>Marinduque</option>
                    <option value="Masbate" {{ old('state') == 'Masbate' ? 'selected' : '' }}>Masbate</option>
                    <option value="Metro Manila" {{ old('state') == 'Metro Manila' ? 'selected' : '' }}>Metro Manila</option>
                    <option value="Misamis Occidental" {{ old('state') == 'Misamis Occidental' ? 'selected' : '' }}>Misamis Occidental</option>
                    <option value="Misamis Oriental" {{ old('state') == 'Misamis Oriental' ? 'selected' : '' }}>Misamis Oriental</option>
                    <option value="Mountain Province" {{ old('state') == 'Mountain Province' ? 'selected' : '' }}>Mountain Province</option>
                    <option value="Negros Occidental" {{ old('state') == 'Negros Occidental' ? 'selected' : '' }}>Negros Occidental</option>
                    <option value="Negros Oriental" {{ old('state') == 'Negros Oriental' ? 'selected' : '' }}>Negros Oriental</option>
                    <option value="Northern Samar" {{ old('state') == 'Northern Samar' ? 'selected' : '' }}>Northern Samar</option>
                    <option value="Nueva Ecija" {{ old('state') == 'Nueva Ecija' ? 'selected' : '' }}>Nueva Ecija</option>
                    <option value="Nueva Vizcaya" {{ old('state') == 'Nueva Vizcaya' ? 'selected' : '' }}>Nueva Vizcaya</option>
                    <option value="Occidental Mindoro" {{ old('state') == 'Occidental Mindoro' ? 'selected' : '' }}>Occidental Mindoro</option>
                    <option value="Oriental Mindoro" {{ old('state') == 'Oriental Mindoro' ? 'selected' : '' }}>Oriental Mindoro</option>
                    <option value="Palawan" {{ old('state') == 'Palawan' ? 'selected' : '' }}>Palawan</option>
                    <option value="Pampanga" {{ old('state') == 'Pampanga' ? 'selected' : '' }}>Pampanga</option>
                    <option value="Pangasinan" {{ old('state') == 'Pangasinan' ? 'selected' : '' }}>Pangasinan</option>
                    <option value="Quezon" {{ old('state') == 'Quezon' ? 'selected' : '' }}>Quezon</option>
                    <option value="Quirino" {{ old('state') == 'Quirino' ? 'selected' : '' }}>Quirino</option>
                    <option value="Rizal" {{ old('state') == 'Rizal' ? 'selected' : '' }}>Rizal</option>
                    <option value="Romblon" {{ old('state') == 'Romblon' ? 'selected' : '' }}>Romblon</option>
                    <option value="Samar" {{ old('state') == 'Samar' ? 'selected' : '' }}>Samar</option>
                    <option value="Sarangani" {{ old('state') == 'Sarangani' ? 'selected' : '' }}>Sarangani</option>
                    <option value="Siquijor" {{ old('state') == 'Siquijor' ? 'selected' : '' }}>Siquijor</option>
                    <option value="Sorsogon" {{ old('state') == 'Sorsogon' ? 'selected' : '' }}>Sorsogon</option>
                    <option value="South Cotabato" {{ old('state') == 'South Cotabato' ? 'selected' : '' }}>South Cotabato</option>
                    <option value="Southern Leyte" {{ old('state') == 'Southern Leyte' ? 'selected' : '' }}>Southern Leyte</option>
                    <option value="Sultan Kudarat" {{ old('state') == 'Sultan Kudarat' ? 'selected' : '' }}>Sultan Kudarat</option>
                    <option value="Sulu" {{ old('state') == 'Sulu' ? 'selected' : '' }}>Sulu</option>
                    <option value="Surigao del Norte" {{ old('state') == 'Surigao del Norte' ? 'selected' : '' }}>Surigao del Norte</option>
                    <option value="Surigao del Sur" {{ old('state') == 'Surigao del Sur' ? 'selected' : '' }}>Surigao del Sur</option>
                    <option value="Tarlac" {{ old('state') == 'Tarlac' ? 'selected' : '' }}>Tarlac</option>
                    <option value="Tawi-Tawi" {{ old('state') == 'Tawi-Tawi' ? 'selected' : '' }}>Tawi-Tawi</option>
                    <option value="Zambales" {{ old('state') == 'Zambales' ? 'selected' : '' }}>Zambales</option>
                    <option value="Zamboanga del Norte" {{ old('state') == 'Zamboanga del Norte' ? 'selected' : '' }}>Zamboanga del Norte</option>
                    <option value="Zamboanga del Sur" {{ old('state') == 'Zamboanga del Sur' ? 'selected' : '' }}>Zamboanga del Sur</option>
                    <option value="Zamboanga Sibugay" {{ old('state') == 'Zamboanga Sibugay' ? 'selected' : '' }}>Zamboanga Sibugay</option>
                </select>
                @error('state')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input 
                    type="text" 
                    id="zip_code" 
                    name="zip_code" 
                    value="{{ old('zip_code') }}" 
                    placeholder="Postal / Zip Code" 
                    required
                    class="@error('zip_code') error @enderror"
                >
                @error('zip_code')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="display: none;">
                <label for="service_plan">Preferred Internet Plan</label>
                <select 
                    id="service_plan" 
                    name="service_plan" 
                    class="@error('service_plan') error @enderror"
                >
                    <option value="">Select your desired internet speed</option>
                    <option value="basic" {{ old('service_plan') == 'basic' ? 'selected' : '' }}>Basic - 50 Mbps ($29.99/mo)</option>
                    <option value="standard" {{ old('service_plan') == 'standard' ? 'selected' : '' }}>Standard - 100 Mbps ($49.99/mo)</option>
                    <option value="premium" {{ old('service_plan') == 'premium' ? 'selected' : '' }}>Premium - 500 Mbps ($79.99/mo)</option>
                    <option value="ultra" {{ old('service_plan') == 'ultra' ? 'selected' : '' }}>Ultra - 1 Gbps ($99.99/mo)</option>
                </select>
                @error('service_plan')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Create a secure password" 
                        required
                        minlength="8"
                        class="@error('password') error @enderror"
                    >
                    <small class="password-requirements">At least 8 characters with uppercase, lowercase, and number</small>
                    @error('password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        placeholder="Re-enter your password" 
                        required
                        minlength="8"
                        class="@error('password_confirmation') error @enderror"
                    >
                    @error('password_confirmation')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <button type="submit" class="btn-register">Register for Internet Service</button>
            
            <div class="login-link">
                Already have an account? <a href="{{ route('login') }}">Sign in</a>
            </div>
            
            <div class="login-link">
                <a href="/" class="back-link">← Back to Home</a>
            </div>
        </form>
    </div>

    <script>
        // Client-side validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            
            // Real-time validation
            if(nameInput) {
                nameInput.addEventListener('input', function() {
                const value = this.value.trim();
                const regex = /^[a-zA-Z\s]+$/;
                
                if (value.length < 3) {
                    this.classList.add('error');
                } else if (!regex.test(value)) {
                    this.classList.add('error');
                } else {
                    this.classList.remove('error');
                }
            });
            }
            
            if(emailInput) {
                emailInput.addEventListener('input', function() {
                const value = this.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (!emailRegex.test(value)) {
                    this.classList.add('error');
                } else {
                    this.classList.remove('error');
                }
            });
            }
            
            if(passwordInput) {
                passwordInput.addEventListener('input', function() {
                const value = this.value;
                const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
                
                if (!passwordRegex.test(value)) {
                    this.classList.add('error');
                } else {
                    this.classList.remove('error');
                }
                
                // Also check confirm password match
                if (confirmPasswordInput && confirmPasswordInput.value && confirmPasswordInput.value !== value) {
                    confirmPasswordInput.classList.add('error');
                } else if (confirmPasswordInput && confirmPasswordInput.value) {
                    confirmPasswordInput.classList.remove('error');
                }
            });
            }
            
            if(confirmPasswordInput) {
                confirmPasswordInput.addEventListener('input', function() {
                    if (this.value !== passwordInput.value) {
                        this.classList.add('error');
                    } else {
                        this.classList.remove('error');
                    }
                });
            }
            
            // Form submission validation
            form.addEventListener('submit', function(e) {
                let isValid = true;
                
                // Validate name
                if(nameInput) {
                    const nameValue = nameInput.value.trim();
                    const nameRegex = /^[a-zA-Z\s]+$/;
                    if (nameValue.length < 3 || !nameRegex.test(nameValue)) {
                        nameInput.classList.add('error');
                        isValid = false;
                    }
                }
                
                // Validate email
                if(emailInput) {
                    const emailValue = emailInput.value.trim();
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(emailValue)) {
                        emailInput.classList.add('error');
                        isValid = false;
                    }
                }
                
                // Validate password
                if(passwordInput) {
                    const passwordValue = passwordInput.value;
                    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
                    if (!passwordRegex.test(passwordValue)) {
                        passwordInput.classList.add('error');
                        isValid = false;
                    }
                    
                    // Validate password confirmation
                    if (confirmPasswordInput && confirmPasswordInput.value !== passwordValue) {
                        confirmPasswordInput.classList.add('error');
                        isValid = false;
                    }
                }
                
                if (!isValid) {
                    e.preventDefault();
                    // Scroll to first error
                    const firstError = form.querySelector('.error');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus();
                    }
                }
            });
        });
    </script>
</body>
</html>
