<?php
/**
 * pages/login.php
 */

// 1. Încărcăm funcțiile (calea este relativă la index.php pentru că de acolo este inclus)
require_once 'includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    if (login($pdo, $user, $pass)) {
        // Redirecționarea funcționează acum nativ pentru că index.php 
        // nu mai include header.php înainte de acest fișier
        header("Location: index.php?page=dashboard");
        exit;
    } else {
        $error = "Utilizator sau parolă incorectă!";
    }
}
?>
<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Calitate Aer</title>
    <style>
        body {
            background: #0d0d0d;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background: #1a1a1a;
            padding: 40px;
            border-radius: 12px;
            border: 1px solid #333;
            width: 100%;
            max-width: 350px;
            color: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        h2 {
            color: #28a745;
            text-align: center;
            margin-bottom: 25px;
            font-weight: 300;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            background: #000;
            border: 1px solid #444;
            color: white;
            border-radius: 5px;
            box-sizing: border-box;
            transition: border 0.3s;
        }

        input:focus {
            border-color: #28a745;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #28a745;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
            transition: background 0.3s;
        }

        button:hover {
            background: #218838;
        }

        .error-msg {
            background: rgba(255, 77, 77, 0.1);
            color: #ff4d4d;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-size: 0.85rem;
            margin-bottom: 15px;
            border: 1px solid #ff4d4d;
        }

        .info {
            margin-top: 25px;
            font-size: 0.75rem;
            color: #666;
            text-align: center;
            border-top: 1px solid #222;
            padding-top: 15px;
            line-height: 1.6;
        }

        .info strong {
            color: #888;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <h2>Autentificare</h2>

        <?php if ($error): ?>
            <div class="error-msg"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="username" placeholder="Utilizator" required autocomplete="off">
            <input type="password" name="password" placeholder="Parolă" required>
            <button type="submit">Logare Sistem</button>
        </form>

        <div class="info">
            Admin: <strong>admin / admin123</strong><br>
            User: <strong>user_test / test123</strong>
        </div>
    </div>
</body>

</html>