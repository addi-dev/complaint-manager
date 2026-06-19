<?php
require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../core/CSRF.php';

if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $redirects = [
        'admin' => '/complaint-manager/views/admin',
        'superviseur' => '/complaint-manager/views/supervisor',
        'agent' => '/complaint-manager/views/agent',
        'client' => '/complaint-manager/views/client',
    ];

    $role = $_SESSION['user_role'] ?? '';
    $url = $redirects[$role] ?? '/complaint-manager/views/auth/connexion.php';

    header('Location: ' . $url);
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Connexion | <?php echo APP_NAME ?></title>
    <?php echo CSRF::metaTag() ?>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
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
            object-position: left center;
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

        /* Alert */
        .alert {
            width: 100%;
            max-width: 460px;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 1rem;
            display: none;
            align-items: center;
            gap: 0.5rem;
        }

        .alert.show {
            display: flex;
        }

        .alert-error {
            background: #fef2f2;
            color: #ef4444;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #f0fdf4;
            color: #22c55e;
            border: 1px solid #bbf7d0;
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

        /* Forgot */
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

        /* Submit button */
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
        }

        .btn-submit:hover {
            background: var(--brand-dark);
            box-shadow: 0 6px 24px rgba(108, 78, 248, 0.3);
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Spinner */
        .spinner {
            width: 17px;
            height: 17px;
            border: 2.5px solid rgba(255, 255, 255, 0.35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.65s linear infinite;
            display: none;
            flex-shrink: 0;
        }

        .btn-submit.loading .spinner {
            display: block;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
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
        <img src="../../assets/images/loginbg2.png" alt="" />
    </div>

    <!-- Right form panel -->
    <div class="form-panel">

        <h1>Bienvenue</h1>
        <p class="subtitle">Connectez-vous à votre compte</p>

        <!-- Alert box -->
        <div class="alert" id="alert">
            <span id="alert-msg"></span>
        </div>

        <!-- Email -->
        <div class="field">
            <label for="email">Adresse e-mail</label>
            <div class="input-wrap">
                <input type="email" id="email" placeholder="Entrez votre adresse e-mail" />
            </div>
        </div>

        <!-- Password -->
        <div class="field">
            <label for="password">Mot de passe</label>
            <div class="input-wrap">
                <input type="password" id="password" placeholder="Entrez votre mot de passe" />
                <button class="eye-btn" onclick="togglePassword()" type="button"
                    aria-label="Afficher ou masquer le mot de passe">
                    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94" />
                        <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19" />
                        <line x1="1" y1="1" x2="23" y2="23" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="forgot-wrap">
            <a href="reinitialiser_mot_de_passe.php">Mot de passe oublié ?</a>
        </div>

        <button class="btn-submit" id="loginBtn" onclick="handleLogin()">
            <div class="spinner"></div>
            <span class="btn-text">Connexion</span>
        </button>

    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.innerHTML = isHidden ?
                `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>` :
                `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                   <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                   <line x1="1" y1="1" x2="23" y2="23"/>`;
        }

        function showAlert(message, type = 'error') {
            const box = document.getElementById('alert');
            const msg = document.getElementById('alert-msg');
            box.className = 'alert alert-' + type + ' show';
            msg.textContent = message;
        }

        function hideAlert() {
            document.getElementById('alert').className = 'alert';
        }

        function setLoading(state) {
            const btn = document.getElementById('loginBtn');
            btn.disabled = state;
            btn.classList.toggle('loading', state);
            btn.querySelector('.btn-text').textContent = state ? 'Connexion en cours...' : 'Connexion';
        }

        async function handleLogin() {
            hideAlert();

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            if (!email) {
                showAlert('Veuillez entrer votre adresse e-mail.');
                return;
            }
            if (!password) {
                showAlert('Veuillez entrer votre mot de passe.');
                return;
            }

            setLoading(true);

            try {
                const res = await fetch('../../actions/auth/connexion.php', {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json',
                        "X-CSRF-Token": csrfToken

                    },
                    body: JSON.stringify({
                        email,
                        password
                    })
                });

                const data = await res.json();

                if (data.success) {
                    showAlert('Connexion réussie ! Redirection…', 'success');
                    setTimeout(() => window.location.href = data.redirect, 1000);
                } else {
                    showAlert(data.message || 'Email ou mot de passe invalide.');
                }

            } catch (err) {
                showAlert('Erreur réseau. Veuillez réessayer.');
                console.error(err);
            } finally {
                setLoading(false);
            }
        }

        // Allow Enter key to submit
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') handleLogin();
        });
    </script>

</body>

</html>