<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --brand: #6c4ef8;
            --brand-dark: #5a3de0;
            --main-bg: #f4f6fb;
            --card-bg: #ffffff;
            --text-primary: #1a1d2e;
            --text-muted: #8c93a8;
            --text-label: #5b6278;
            --border: #e8eaf2;
            --shadow-md: 0 4px 20px rgba(59, 110, 248, 0.12);
        }

        body {
            font-family: "Plus Jakarta Sans", sans-serif;
            height: 100vh;
            display: flex;
            overflow: hidden;
            background: var(--main-bg);
            color: var(--text-primary);
        }

        /* ── Left: photo panel ── */
        .photo-panel {
            flex: 0 0 50%;
            position: relative;
            overflow: hidden;
            border-radius: 0 2.5rem 2.5rem 0;
        }

        .photo-panel img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center center;
            display: block;
        }

        /* ── Right: form panel ── */
        .form-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 3rem;
            background: var(--main-bg);
            overflow-y: auto;
        }

        /* Logo */
        .logo {
            width: 52px;
            height: 52px;
            margin-bottom: 1.25rem;
        }

        h1 {
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.3px;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .subtitle {
            font-size: 0.88rem;
            color: var(--text-muted);
            margin-bottom: 2rem;
            text-align: center;
        }

        /* Field group */
        .field {
            width: 100%;
            max-width: 460px;
            margin-bottom: 1rem;
        }

        .field label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-label);
            margin-bottom: 0.4rem;
        }

        .input-wrap {
            position: relative;
        }

        .field input {
            width: 100%;
            padding: 0.85rem 1rem;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 0.9rem;
            color: var(--text-primary);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: var(--card-bg);
        }

        .field input::placeholder {
            color: var(--text-muted);
        }

        .field input:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(108, 78, 248, 0.1);
        }

        /* Eye toggle */
        .eye-btn {
            position: absolute;
            right: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            padding: 0;
            display: flex;
            align-items: center;
        }

        .eye-btn:hover {
            color: var(--text-label);
        }

        /* Forgot password */
        .forgot-wrap {
            width: 100%;
            max-width: 460px;
            text-align: right;
            margin-top: -0.4rem;
            margin-bottom: 1.25rem;
        }

        .forgot-wrap a {
            font-size: 0.8rem;
            color: var(--brand);
            font-weight: 600;
            text-decoration: none;
        }

        .forgot-wrap a:hover {
            text-decoration: underline;
        }

        /* Submit */
        .btn-submit {
            width: 100%;
            max-width: 460px;
            padding: 1rem;
            background: var(--brand);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.15s, transform 0.1s, box-shadow 0.15s;
            box-shadow: var(--shadow-md);
        }

        .btn-submit:hover {
            background: var(--brand-dark);
            box-shadow: 0 6px 24px rgba(108, 78, 248, 0.3);
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .photo-panel {
                display: none;
            }

            .form-panel {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>

<body>

    <!-- Left photo panel -->
    <div class="photo-panel">
        <img src="../../assets/images/loginbg.jpg" alt="" />
    </div>

    <!-- Right form panel -->
    <div class="form-panel">

        <!-- Logo -->
        <svg class="logo" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="52" height="52" rx="14" fill="transparent" />
            <path d="M13 38V20C13 13.373 18.373 8 25 8h2C33.627 8 39 13.373 39 20v18l-4-3-4 3-4-3-4 3-4-3-4 3Z" fill="#5B21B6" />
            <circle cx="21" cy="22" r="2.5" fill="white" />
            <circle cx="31" cy="22" r="2.5" fill="white" />
            <circle cx="32" cy="40" r="6" fill="#F97316" />
        </svg>

        <h1>Welcome back</h1>
        <p class="subtitle">Sign in to your account</p>

        <!-- Email -->
        <div class="field">
            <label for="email">Email Address</label>
            <div class="input-wrap">
                <input type="email" id="email" placeholder="Enter your email address" />
            </div>
        </div>

        <!-- Password -->
        <div class="field">
            <label for="password">Password</label>
            <div class="input-wrap">
                <input type="password" id="password" placeholder="Enter your password" />
                <button class="eye-btn" onclick="togglePassword()" type="button" aria-label="Toggle password visibility">
                    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94" />
                        <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19" />
                        <line x1="1" y1="1" x2="23" y2="23" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="forgot-wrap">
            <a href="#">Forgot password?</a>
        </div>

        <button class="btn-submit">Login</button>

    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.innerHTML = isHidden ?
                `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>` :
                `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>`;
        }
    </script>

</body>

</html>