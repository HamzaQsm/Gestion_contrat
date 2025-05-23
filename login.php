<?php
session_start();
$email = $_GET['email'] ?? '';
$error = $_GET['error'] ?? null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Connexion Agent - AXA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            margin-top: 120px;
            background-color:#007bff ;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .errors {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="email"],
        input[type="password"] {
            width: 95%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .link {
            text-align: center;
            margin-top: 15px;
        }

        .link a {
            color: #007bff;
            text-decoration: none;
        }

        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Connexion à l'agent</h2>
    <?php if ($error): ?>
        <div class="errors">
            Email ou mot de passe incorrect.
        </div>
    <?php endif; ?>

    <form method="POST" action="auth.php">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Se connecter</button>
    </form>

    <div class="link">
        <p>Pas encore de compte ? <a href="register.php">Inscrivez-vous ici</a>.</p>
    </div>
</div>
</body>
</html>
