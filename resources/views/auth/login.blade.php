
    <style>
        /* Modern styling and 3D elements */
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #ffffff 0%, #f0fffe 100%);
        }

        .login-container {
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-card {
            display: flex;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            min-height: 550px;
        }

        .login-form {
            flex: 1;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-visual {
            flex: 1;
            background: linear-gradient(135deg, #2D5A27 0%, #40E0D0 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2D5A27;
            margin-bottom: 2rem;
            text-align: center;
        }

        /* 3D Visual Elements */
        .visual-container {
            position: relative;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
            animation: float 6s ease-in-out infinite;
        }

        .chart-bars {
            position: absolute;
            display: flex;
            gap: 15px;
            align-items: flex-end;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .bar {
            width: 30px;
            background: linear-gradient(45deg, #FFD700, #FFA500);
            border-radius: 5px 5px 0 0;
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.3);
            animation: growBar 2s ease-out forwards;
            transform-origin: bottom;
        }

        .bar:nth-child(1) { height: 60px; animation-delay: 0.2s; }
        .bar:nth-child(2) { height: 90px; animation-delay: 0.4s; }
        .bar:nth-child(3) { height: 120px; animation-delay: 0.6s; }
        .bar:nth-child(4) { height: 150px; animation-delay: 0.8s; }
        .bar:nth-child(5) { height: 180px; animation-delay: 1s; }

        .floating-coins {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .coin {
            position: absolute;
            width: 50px;
            height: 50px;
            background: linear-gradient(45deg, #FFD700, #FFA500);
            border-radius: 50%;
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.3);
            animation: floatCoin 4s ease-in-out infinite;
        }

        .coin::before {
            content: '$';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 20px;
            font-weight: bold;
            color: #fff;
        }

        .coin:nth-child(1) { top: 20%; right: 10%; animation-delay: 0s; }
        .coin:nth-child(2) { top: 60%; right: 20%; animation-delay: 1s; }
        .coin:nth-child(3) { top: 40%; right: 5%; animation-delay: 2s; }

        /* Form styling */
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #2D5A27;
            box-shadow: 0 0 0 3px rgba(45, 90, 39, 0.2);
            outline: none;
        }

        label {
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 0.5rem;
            display: block;
        }

        .login-button {
            background: #2D5A27;
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 1.5rem;
        }

        .login-button:hover {
            background: #40E0D0;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(64, 224, 208, 0.4);
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-top: 1rem;
        }

        .remember-me input {
            margin-right: 0.5rem;
            width: 1rem;
            height: 1rem;
        }

        .remember-me span {
            font-size: 0.875rem;
            color: #4a5568;
        }

        .error-message {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotateY(0deg); }
            50% { transform: translateY(-20px) rotateY(10deg); }
        }

        @keyframes growBar {
            0% { transform: scaleY(0); }
            100% { transform: scaleY(1); }
        }

        @keyframes floatCoin {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(180deg); }
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
            }
            
            .login-visual {
                min-height: 200px;
            }
            
            .login-form {
                padding: 2rem;
            }
        }
    </style>

    <div class="login-container">
        <div class="login-card">
            <div class="login-form">
                <h1 class="login-title">Traders Family</h1>
                
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />

                        <x-text-input id="password" class="block mt-1 w-full"
                                        type="password"
                                        name="password"
                                        required autocomplete="current-password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="remember-me">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </div>

                    <button type="submit" class="login-button">
                        {{ __('Log in') }}
                    </button>
                </form>
            </div>
            
            <div class="login-visual">
                <div class="visual-container">
                    <div class="chart-bars">
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                    </div>
                    <div class="floating-coins">
                        <div class="coin"></div>
                        <div class="coin"></div>
                        <div class="coin"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>