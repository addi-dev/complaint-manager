<?php
require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../core/CSRF.php';

if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $redirects = [
        'admin'       => '/complaint-manager/views/admin',
        'superviseur' => '/complaint-manager/views/supervisor',
        'agent'       => '/complaint-manager/views/agent',
        'client'      => '/complaint-manager/views/client',
    ];
    $role = $_SESSION['user_role'] ?? '';
    header('Location: ' . ($redirects[$role] ?? '/complaint-manager/views/auth/login.php'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Réinitialisation | <?php echo APP_NAME ?></title>
    <?php echo CSRF::metaTag(); ?>
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--main-bg);
            color: var(--text-primary);
            padding: 2rem;
        }


        .form-panel {
            width: 100%;
            max-width: 540px;
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(108, 78, 248, 0.12), 0 1px 4px rgba(0, 0, 0, 0.05);
            padding: 3rem 3.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
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
            margin-bottom: 1.5rem;
            text-align: center;
        }

        /* Step indicator */
        .steps-track {
            display: flex;
            align-items: center;
            width: 100%;
            max-width: 460px;
            margin-bottom: 1.75rem;
        }

        .step-node {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-muted);
            white-space: nowrap;
        }

        .step-node.active {
            color: var(--brand);
        }

        .step-node.done {
            color: #16a34a;
        }

        .step-circle {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            border: 2px solid currentColor;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.72rem;
            font-weight: 700;
            flex-shrink: 0;
            transition: background 0.2s;
        }

        .step-node.active .step-circle {
            background: var(--brand);
            border-color: var(--brand);
            color: #fff;
        }

        .step-node.done .step-circle {
            background: #16a34a;
            border-color: #16a34a;
            color: #fff;
        }

        .step-connector {
            flex: 1;
            height: 2px;
            background: var(--border);
            margin: 0 10px;
            transition: background 0.3s;
        }

        .step-connector.done {
            background: #16a34a;
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

        /* Back link */
        .back-link {
            width: 100%;
            max-width: 460px;
            text-align: center;
            margin-top: 1.1rem;
        }

        .back-link a {
            font-size: 0.82rem;
            color: var(--text-muted);
            font-weight: 500;
            text-decoration: none;
            transition: color 0.15s;
        }

        .back-link a:hover {
            color: var(--brand);
        }

        @media (max-width: 768px) {
            .form-panel {
                padding: 2rem 1.5rem;
                border-radius: 14px;
            }
        }
    </style>
</head>

<body>

    <div class="form-panel">

        <!-- ── Step 1: verify identity ─────────────────────────────── -->
        <div id="view-step1">

            <h1>Mot de passe oublié</h1>
            <p class="subtitle">Vérifiez votre identité pour continuer</p>

            <div class="steps-track">
                <div class="step-node active">
                    <div class="step-circle">1</div>
                    <span>Identité</span>
                </div>
                <div class="step-connector"></div>
                <div class="step-node">
                    <div class="step-circle">2</div>
                    <span>Nouveau mot de passe</span>
                </div>
            </div>

            <div class="alert" id="alert1"><span id="alert1-msg"></span></div>

            <div class="field">
                <label for="email">Adresse e-mail</label>
                <div class="input-wrap">
                    <input type="email" id="email" placeholder="Entrez votre adresse e-mail" />
                </div>
            </div>

            <div class="field">
                <label for="date_naissance">Date de naissance</label>
                <div class="input-wrap">
                    <input type="date" id="date_naissance" />
                </div>
            </div>

            <div class="field">
                <label for="numero_cin">Numéro CIN</label>
                <div class="input-wrap">
                    <input type="text" id="numero_cin" placeholder="Ex. AB123456" />
                </div>
            </div>

            <button class="btn-submit" id="btn1" onclick="handleStep1()">
                <div class="spinner"></div>
                <span class="btn-text">Vérifier mon identité</span>
            </button>

            <div class="back-link">
                <a href="login.php">← Retour à la connexion</a>
            </div>

        </div>

        <!-- ── Step 2: new password ────────────────────────────────── -->
        <div id="view-step2" style="display:none">

            <h1>Nouveau mot de passe</h1>
            <p class="subtitle">Choisissez un mot de passe sécurisé</p>

            <div class="steps-track">
                <div class="step-node done">
                    <div class="step-circle">✓</div>
                    <span>Identité</span>
                </div>
                <div class="step-connector done"></div>
                <div class="step-node active">
                    <div class="step-circle">2</div>
                    <span>Nouveau mot de passe</span>
                </div>
            </div>

            <div class="alert" id="alert2"><span id="alert2-msg"></span></div>

            <div class="field">
                <label for="password">Nouveau mot de passe</label>
                <div class="input-wrap">
                    <input type="password" id="password" placeholder="Minimum 8 caractères" />
                    <button class="eye-btn" onclick="togglePwd('password','eye1')" type="button"
                        aria-label="Afficher ou masquer le mot de passe">
                        <svg id="eye1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94" />
                            <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19" />
                            <line x1="1" y1="1" x2="23" y2="23" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="field">
                <label for="confirm_password">Confirmer le mot de passe</label>
                <div class="input-wrap">
                    <input type="password" id="confirm_password" placeholder="Retapez le mot de passe" />
                    <button class="eye-btn" onclick="togglePwd('confirm_password','eye2')" type="button"
                        aria-label="Afficher ou masquer le mot de passe">
                        <svg id="eye2" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94" />
                            <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19" />
                            <line x1="1" y1="1" x2="23" y2="23" />
                        </svg>
                    </button>
                </div>
            </div>

            <button class="btn-submit" id="btn2" onclick="handleStep2()">
                <div class="spinner"></div>
                <span class="btn-text">Réinitialiser le mot de passe</span>
            </button>

            <div class="back-link">
                <a href="login.php">← Retour à la connexion</a>
            </div>

        </div>

    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        function togglePwd(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.innerHTML = show ?
                `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>` :
                `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                   <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                   <line x1="1" y1="1" x2="23" y2="23"/>`;
        }

        function showAlert(n, message, type = 'error') {
            const box = document.getElementById('alert' + n);
            box.className = 'alert alert-' + type + ' show';
            document.getElementById('alert' + n + '-msg').textContent = message;
        }

        function hideAlert(n) {
            document.getElementById('alert' + n).className = 'alert';
        }

        function setLoading(n, state, label) {
            const btn = document.getElementById('btn' + n);
            btn.disabled = state;
            btn.classList.toggle('loading', state);
            btn.querySelector('.btn-text').textContent = state ? 'Chargement…' : label;
        }

        async function handleStep1() {
            hideAlert(1);

            const email = document.getElementById('email').value.trim();
            const date_naissance = document.getElementById('date_naissance').value;
            const numero_cin = document.getElementById('numero_cin').value.trim();

            if (!email) {
                showAlert(1, 'Veuillez entrer votre adresse e-mail.');
                return;
            }
            if (!date_naissance) {
                showAlert(1, 'Veuillez entrer votre date de naissance.');
                return;
            }
            if (!numero_cin) {
                showAlert(1, 'Veuillez entrer votre numéro CIN.');
                return;
            }

            setLoading(1, true, 'Vérifier mon identité');

            try {
                const res = await fetch('../../actions/auth/reset-password.php', {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json',
                        "X-CSRF-Token": csrfToken
                    },
                    body: JSON.stringify({
                        step: 1,
                        email,
                        date_naissance,
                        numero_cin
                    }),
                });
                const data = await res.json();

                if (data.success && data.verified) {
                    document.getElementById('view-step1').style.display = 'none';
                    document.getElementById('view-step2').style.display = '';
                } else {
                    showAlert(1, data.message || 'Identité non reconnue.');
                }

            } catch (err) {
                showAlert(1, 'Erreur réseau. Veuillez réessayer.');
                console.error(err);
            } finally {
                setLoading(1, false, 'Vérifier mon identité');
            }
        }

        async function handleStep2() {
            hideAlert(2);

            const password = document.getElementById('password').value;
            const confirm_password = document.getElementById('confirm_password').value;

            if (!password) {
                showAlert(2, 'Veuillez entrer un nouveau mot de passe.');
                return;
            }
            if (password.length < 8) {
                showAlert(2, 'Le mot de passe doit contenir au moins 8 caractères.');
                return;
            }
            if (password !== confirm_password) {
                showAlert(2, 'Les mots de passe ne correspondent pas.');
                return;
            }

            setLoading(2, true, 'Réinitialiser le mot de passe');

            try {
                const res = await fetch('../../actions/auth/reset-password.php', {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json',
                        "X-CSRF-Token": csrfToken
                    },
                    body: JSON.stringify({
                        step: 2,
                        password,
                        confirm_password,
                    }),
                });
                const data = await res.json();

                if (data.success) {
                    showAlert(2, 'Mot de passe réinitialisé ! Redirection…', 'success');
                    setTimeout(() => window.location.href = 'login.php', 1500);
                } else {
                    showAlert(2, data.message || 'Une erreur est survenue.');
                }

            } catch (err) {
                showAlert(2, 'Erreur réseau. Veuillez réessayer.');
                console.error(err);
            } finally {
                setLoading(2, false, 'Réinitialiser le mot de passe');
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key !== 'Enter') return;
            document.getElementById('view-step2').style.display === 'none' ?
                handleStep1() :
                handleStep2();
        });
    </script>

</body>

</html>