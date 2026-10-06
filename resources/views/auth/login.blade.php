<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>SellMate AI — Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AI Powered Omnichannel Business Operating System" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ===== RESET & BASE ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #7209b7;
            --accent: #4cc9f0;
            --pink: #f72585;
            --dark: #0a0e1a;
            --card-bg: rgba(255, 255, 255, 0.92);
            --glass-border: rgba(255, 255, 255, 0.2);
            --shadow-glow: 0 20px 60px rgba(67, 97, 238, 0.15);
            --radius: 20px;
        }

        html, body {
            height: 100%;
            width: 100%;
            overflow: hidden;
            font-family: 'Inter', -apple-system, sans-serif;
        }

        body {
            background: var(--dark);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }

        /* ===== ANIMATED BACKGROUND ===== */
        .bg-animation {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }

        .bg-animation .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            animation: floatOrb 12s ease-in-out infinite alternate;
        }

        .orb-1 {
            width: 400px;
            height: 400px;
            background: var(--primary);
            top: -100px;
            right: -100px;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 350px;
            height: 350px;
            background: var(--secondary);
            bottom: -80px;
            left: -80px;
            animation-delay: 4s;
        }

        .orb-3 {
            width: 250px;
            height: 250px;
            background: var(--accent);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: 8s;
            opacity: 0.15;
        }

        @keyframes floatOrb {
            0% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(10px, -10px) scale(1.05); }
        }

        /* Grid overlay */
        .bg-grid {
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image: 
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* ===== LOGIN CONTAINER ===== */
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
        }

        .login-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border-radius: var(--radius);
            box-shadow: var(--shadow-glow);
            overflow: hidden;
            border: 1px solid var(--glass-border);
            transition: all 0.4s ease;
        }

        .login-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 30px 80px rgba(67, 97, 238, 0.2);
        }

        /* ===== HEADER ===== */
        .login-header {
            background: linear-gradient(135deg, #0a0e1a 0%, #1a1a3e 50%, #0d1b2a 100%);
            padding: 35px 30px 28px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: -60%;
            right: -30%;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(67,97,238,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .login-header::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -20%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(247,37,133,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .logo-wrapper {
            position: relative;
            z-index: 2;
        }

        .logo-wrapper .logo-img {
            max-width: 200px;
            width: 100%;
            height: auto;
            filter: drop-shadow(0 4px 20px rgba(67,97,238,0.3));
            transition: transform 0.4s ease;
        }

        .logo-wrapper .logo-img:hover {
            transform: scale(1.04);
        }

        .brand-tagline {
            position: relative;
            z-index: 2;
            color: rgba(255,255,255,0.7);
            font-size: 12px;
            letter-spacing: 3px;
            text-transform: uppercase;
            font-weight: 400;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .brand-tagline .dot {
            display: inline-block;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--accent);
            animation: pulseDot 2s ease-in-out infinite;
        }

        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.3; transform: scale(0.6); }
        }

        /* ===== BODY ===== */
        .login-body {
            padding: 32px 28px 28px;
        }

        .login-body .welcome-text {
            font-size: 13px;
            color: #888;
            text-align: center;
            margin-bottom: 24px;
            letter-spacing: 0.3px;
            line-height: 1.6;
        }

        .login-body .welcome-text strong {
            color: var(--primary);
            font-weight: 600;
        }

        /* ===== FORM ===== */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            font-weight: 600;
            font-size: 12px;
            color: #444;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 6px;
            letter-spacing: 0.3px;
        }

        .form-label i {
            color: var(--primary);
            font-size: 13px;
            width: 16px;
        }

        .input-group-custom {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            background: #f5f7fb;
            border: 1.5px solid #e8ecf2;
            transition: all 0.3s ease;
        }

        .input-group-custom:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
            background: #fff;
        }

        .input-group-custom .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 14px;
            z-index: 2;
            transition: color 0.3s;
        }

        .input-group-custom:focus-within .input-icon {
            color: var(--primary);
        }

        .input-group-custom .form-control {
            border: none;
            background: transparent;
            padding: 12px 14px 12px 44px;
            height: 48px;
            font-size: 14px;
            font-weight: 400;
            color: #222;
            border-radius: 12px;
            width: 100%;
            transition: all 0.3s;
        }

        .input-group-custom .form-control:focus {
            outline: none;
            box-shadow: none;
            background: transparent;
        }

        .input-group-custom .form-control::placeholder {
            color: #b0b8c8;
            font-size: 13px;
            font-weight: 400;
        }

        .input-group-custom .toggle-pwd {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            font-size: 14px;
            padding: 4px;
            transition: color 0.3s;
            z-index: 2;
        }

        .input-group-custom .toggle-pwd:hover {
            color: var(--primary);
        }

        /* ===== PASSWORD STRENGTH ===== */
        .password-strength {
            margin-top: 6px;
            height: 3px;
            border-radius: 2px;
            background: #e8ecf2;
            overflow: hidden;
            display: none;
        }

        .password-strength .bar {
            height: 100%;
            width: 0%;
            border-radius: 2px;
            transition: width 0.4s, background 0.4s;
        }

        /* ===== REMEMBER & FORGOT ===== */
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 16px 0 22px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .form-check-custom {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .form-check-custom input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            border-radius: 4px;
            cursor: pointer;
        }

        .form-check-custom label {
            font-size: 12.5px;
            color: #555;
            cursor: pointer;
            font-weight: 400;
            margin: 0;
        }

        .forgot-link {
            font-size: 12.5px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .forgot-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* ===== LOGIN BUTTON ===== */
        .btn-login {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border: none;
            color: white;
            padding: 13px 20px;
            font-weight: 600;
            font-size: 14px;
            border-radius: 12px;
            width: 100%;
            transition: all 0.4s ease;
            box-shadow: 0 4px 20px rgba(67, 97, 238, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--pink) 100%);
            opacity: 0;
            transition: opacity 0.4s;
            border-radius: 12px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(67, 97, 238, 0.4);
        }

        .btn-login:hover::before {
            opacity: 1;
        }

        .btn-login span {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
        }

        .btn-login .spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        .btn-login.loading .spinner {
            display: inline-block;
        }

        .btn-login.loading .btn-text {
            display: none;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ===== ALERTS ===== */
        .alert-custom {
            border-radius: 12px;
            border: none;
            padding: 10px 14px;
            font-size: 12.5px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            line-height: 1.5;
        }

        .alert-custom i {
            font-size: 16px;
            flex-shrink: 0;
        }

        .alert-custom-danger {
            background: #fef0f0;
            color: #c0392b;
            border-left: 3px solid #c0392b;
        }

        .alert-custom-success {
            background: #edfaf3;
            color: #0a7c3a;
            border-left: 3px solid #0a7c3a;
        }

        .alert-custom .close-btn {
            background: none;
            border: none;
            margin-left: auto;
            font-size: 16px;
            opacity: 0.5;
            cursor: pointer;
            padding: 0 4px;
            color: inherit;
            transition: opacity 0.3s;
        }

        .alert-custom .close-btn:hover {
            opacity: 1;
        }

        /* ===== FOOTER ===== */
        .login-footer {
            text-align: center;
            padding: 16px 28px 20px;
            border-top: 1px solid rgba(0,0,0,0.05);
            background: rgba(255,255,255,0.4);
        }

        .login-footer p {
            font-size: 11px;
            color: #999;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .login-footer p i {
            color: var(--pink);
            font-size: 12px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 480px) {
            body { padding: 16px; }
            .login-body { padding: 24px 20px 20px; }
            .login-header { padding: 28px 20px 20px; }
            .logo-wrapper .logo-img { max-width: 160px; }
            .input-group-custom .form-control { height: 44px; font-size: 13px; padding: 10px 12px 10px 40px; }
            .input-group-custom .input-icon { left: 12px; font-size: 13px; }
            .btn-login { padding: 11px 16px; font-size: 13px; }
            .login-footer { padding: 14px 20px 16px; }
        }

        @media (max-width: 380px) {
            .login-body { padding: 20px 16px; }
            .login-header { padding: 22px 16px 18px; }
            .logo-wrapper .logo-img { max-width: 140px; }
            .brand-tagline { font-size: 10px; letter-spacing: 2px; }
            .form-actions { flex-direction: column; align-items: flex-start; gap: 6px; }
        }

        @media (max-height: 700px) {
            body { padding-top: 10px; align-items: flex-start; }
            .login-header { padding: 22px 20px 16px; }
            .login-body { padding: 20px 24px 16px; }
            .logo-wrapper .logo-img { max-width: 150px; }
            .form-group { margin-bottom: 12px; }
            .form-actions { margin: 10px 0 16px; }
            .btn-login { padding: 11px; }
            .login-footer { padding: 12px 20px 14px; }
        }

        @media (max-height: 600px) {
            .login-header { padding: 16px 20px 12px; }
            .login-body { padding: 16px 20px 12px; }
            .logo-wrapper .logo-img { max-width: 120px; }
            .brand-tagline { font-size: 10px; margin-top: 4px; }
            .form-group { margin-bottom: 10px; }
            .input-group-custom .form-control { height: 38px; font-size: 12.5px; padding: 8px 10px 8px 36px; }
            .input-group-custom .input-icon { left: 10px; font-size: 12px; }
            .btn-login { padding: 9px; font-size: 12px; }
            .login-footer { padding: 10px 20px; }
        }
    </style>
</head>
<body>

    <!-- ===== ANIMATED BACKGROUND ===== -->
    <div class="bg-animation">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>
    <div class="bg-grid"></div>

    <!-- ===== LOGIN CONTAINER ===== -->
    <div class="login-container">

        <div class="login-card">

            <!-- HEADER -->
            <div class="login-header">
                <div class="logo-wrapper">
                    @php
                        $siteSetting = DB::table('site_settings')->first();
                        $logo = $siteSetting && $siteSetting->logo 
                            ? asset('admin_uploads/' . $siteSetting->logo) 
                            : asset('assets/images/sellmate-logo.svg');
                    @endphp
                    <img src="{{ $logo }}" alt="SellMate AI" class="logo-img">
                    <div class="brand-tagline">
                        <span class="dot"></span>
                        YOUR AI SALES ASSISTANT
                        <span class="dot"></span>
                    </div>
                </div>
            </div>

            <!-- BODY -->
            <div class="login-body">

                <div class="welcome-text">
                    <strong>✨ Welcome back!</strong> Login to your dashboard
                </div>

                <!-- ALERTS -->
                <div id="alertContainer">
                    @if(session('error'))
                        <div class="alert-custom alert-custom-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ session('error') }}
                            <button class="close-btn" onclick="this.parentElement.remove()">×</button>
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert-custom alert-custom-success">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                            <button class="close-btn" onclick="this.parentElement.remove()">×</button>
                        </div>
                    @endif
                </div>

                <!-- FORM -->
                <form method="POST" action="{{ route('login.submit') }}" id="loginForm">
                    @csrf

                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label" for="email">
                            <i class="fas fa-envelope"></i> Email Address
                        </label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="fas fa-user"></i></span>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="Enter your email" required autofocus>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-label" for="password">
                            <i class="fas fa-lock"></i> Password
                        </label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="fas fa-key"></i></span>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Enter your password" required>
                            <button type="button" class="toggle-pwd" id="togglePassword" aria-label="Toggle password visibility">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                        <div class="password-strength" id="passwordStrength">
                            <div class="bar" id="strengthBar"></div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="form-actions">
                        <label class="form-check-custom">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">Remember me</label>
                        </label>
                        <a href="#" class="forgot-link">
                            <i class="fas fa-chevron-right" style="font-size: 9px;"></i> Forgot Password?
                        </a>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn-login" id="loginButton">
                        <span>
                            <i class="fas fa-sign-in-alt"></i>
                            <span class="btn-text">Login to Dashboard</span>
                            <span class="spinner"></span>
                        </span>
                    </button>

                </form>
            </div>

            <!-- FOOTER -->
            <div class="login-footer">
                <p>
                    <i class="fas fa-robot"></i> Powered by <strong>SellMate AI</strong> &bull; v2.0
                </p>
            </div>

        </div>
    </div>

    <!-- ===== SCRIPTS ===== -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        (function() {
            'use strict';

            // ===== TOGGLE PASSWORD =====
            const toggleBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            toggleBtn.addEventListener('click', function() {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                toggleIcon.className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
            });

            // ===== PASSWORD STRENGTH =====
            const strengthEl = document.getElementById('passwordStrength');
            const strengthBar = document.getElementById('strengthBar');

            passwordInput.addEventListener('input', function() {
                const val = this.value;
                if (!val) {
                    strengthEl.style.display = 'none';
                    return;
                }
                strengthEl.style.display = 'block';

                let score = 0;
                if (val.length >= 8) score += 25;
                if (/[A-Z]/.test(val)) score += 25;
                if (/[0-9]/.test(val)) score += 25;
                if (/[^A-Za-z0-9]/.test(val)) score += 25;

                strengthBar.style.width = score + '%';
                if (score < 50) strengthBar.style.background = '#e53e3e';
                else if (score < 75) strengthBar.style.background = '#ed8936';
                else strengthBar.style.background = '#38a169';
            });

            // ===== FORM SUBMIT =====
            const form = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginButton');

            form.addEventListener('submit', function(e) {
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value;

                if (!email || !password) {
                    e.preventDefault();
                    showAlert('Please fill in all required fields.', 'danger');
                    return;
                }

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    showAlert('Please enter a valid email address.', 'danger');
                    return;
                }

                // Show loading state
                loginBtn.classList.add('loading');
                loginBtn.disabled = true;

                // Auto-recover if something goes wrong (safety)
                setTimeout(function() {
                    loginBtn.classList.remove('loading');
                    loginBtn.disabled = false;
                }, 8000);
            });

            // ===== SHOW ALERT =====
            function showAlert(message, type) {
                const container = document.getElementById('alertContainer');
                const cls = type === 'danger' ? 'alert-custom-danger' : 'alert-custom-success';
                const icon = type === 'danger' ? 'fa-exclamation-circle' : 'fa-check-circle';

                const div = document.createElement('div');
                div.className = 'alert-custom ' + cls;
                div.innerHTML = `
                    <i class="fas ${icon}"></i>
                    ${message}
                    <button class="close-btn" onclick="this.parentElement.remove()">×</button>
                `;

                // Remove old alerts
                container.innerHTML = '';
                container.appendChild(div);

                // Auto-remove after 5s
                setTimeout(() => {
                    if (div.parentElement) div.remove();
                }, 5000);
            }

            // Auto-remove session alerts after 5s
            setTimeout(() => {
                document.querySelectorAll('.alert-custom').forEach(el => el.remove());
            }, 5000);

            // ===== PREVENT BODY SCROLL ON SMALL SCREENS =====
            const card = document.querySelector('.login-card');
            let isScrollable = card.scrollHeight > card.clientHeight;

            document.body.addEventListener('wheel', function(e) {
                if (!isScrollable && !e.target.closest('.login-card')) {
                    e.preventDefault();
                }
            }, { passive: false });

            // Re-check on resize
            window.addEventListener('resize', function() {
                isScrollable = card.scrollHeight > card.clientHeight;
            });

        })();
    </script>

</body>
</html>