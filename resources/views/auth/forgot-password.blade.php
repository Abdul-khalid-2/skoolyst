<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SKOOLYST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --text-color: #333;
            --light-gray: #f8f9fa;
            --border-color: #e0e0e0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: var(--text-color);
            margin: 0;
            padding: 0;
        }

        .login-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 40px 0;
        }

        .login-container {
            display: flex;
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .login-left {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-right {
            flex: 1;
            padding: 50px;
        }

        .login-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--secondary-color);
        }

        .login-subtitle {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            color: #666;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
        }

        .login-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .login-btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .auth-session-status {
            padding: 12px 15px;
            background-color: #d4edda;
            color: #155724;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border: 1px solid #c3e6cb;
        }

        .input-error {
            color: #e74c3c;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .register-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                margin: 20px;
            }

            .login-left,
            .login-right {
                padding: 30px;
            }

            .login-left {
                order: 2;
            }

            .login-right {
                order: 1;
            }
        }
    </style>
</head>

<body>
    <!-- ==================== PASSWORD RESET SECTION ==================== -->
    <section class="login-section">
        <div class="container">
            <div class="login-container">
                <div class="login-left">
                    <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1.5rem;">Reset Your Password</h2>
                    <p style="font-size: 1.2rem; margin-bottom: 2rem; opacity: 0.9;">
                        Enter your email address and we'll send you a link to reset your password.
                    </p>
                    <div style="margin-top: 2rem;">
                        <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                            <i class="fas fa-shield-alt" style="font-size: 1.5rem; margin-right: 1rem;"></i>
                            <div>
                                <h4 style="margin-bottom: 0.3rem;">Secure Process</h4>
                                <p style="opacity: 0.8;">Your account security is our top priority</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                            <i class="fas fa-envelope" style="font-size: 1.5rem; margin-right: 1rem;"></i>
                            <div>
                                <h4 style="margin-bottom: 0.3rem;">Email Verification</h4>
                                <p style="opacity: 0.8;">We'll send a secure link to your registered email</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center;">
                            <i class="fas fa-clock" style="font-size: 1.5rem; margin-right: 1rem;"></i>
                            <div>
                                <h4 style="margin-bottom: 0.3rem;">Quick & Easy</h4>
                                <p style="opacity: 0.8;">Reset your password in just a few minutes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="login-right">
                    <h2 class="login-title">Reset Password</h2>
                    <p class="login-subtitle">Enter your email to receive a password reset link</p>

                    <!-- Session Status -->
                    <x-auth-session-status class="auth-session-status" :status="session('status')" />

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email address">
                            <x-input-error :messages="$errors->get('email')" class="input-error" />
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                            <a class="forgot-password" href="{{ route('login') }}">
                                Back to Login
                            </a>

                            <button type="submit" class="login-btn">
                                {{ __('Send Reset Link') }}
                            </button>
                        </div>
                    </form>

                    <div class="register-link">
                        Don't have an account? <a href="{{ route('register') }}">Register now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>