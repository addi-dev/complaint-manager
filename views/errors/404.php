<?php http_response_code(404); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>404 - Page Not Found</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background: #f4f6fb;
            color: #1a1d2e;
        }

        h1 {
            font-size: 5rem;
            margin: 0;
            color: #6c4ef8;
        }

        p {
            color: #8c93a8;
        }

        a {
            color: #6c4ef8;
            font-weight: 600;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h1>404</h1>
    <p>La page que vous recherchez n'existe pas.</p>
    <a href="/complaint-manager/views/auth/connexion.php">← Retour à la connexion</a>
</body>

</html>