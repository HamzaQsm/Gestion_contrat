<?php
session_start();
require_once('config.php');


$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email) {
        $errors[] = "L'email est requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide.";
    }

    if (!$password) {
        $errors[] = "Le mot de passe est requis.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM agents WHERE email = ?");
        $stmt->execute([$email]);
        $agent = $stmt->fetch();

        if ($agent && isset($agent['mot_de_passe'])) {
            if (password_verify($password, $agent['mot_de_passe'])) {
                // Auth OK
                $_SESSION['agent_id'] = $agent['id'];
                $_SESSION['agent_email'] = $agent['email'];
                header('Location: dashboard.php');
                exit;
            } else {
                $errors[] = "Mot de passe incorrect.";
            }
        } else {
            $errors[] = "Email non trouvé.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Connexion Agent - Assurance</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; padding: 40px; }
        .container { max-width: 400px; margin: auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 20px; }
        input[type=email], input[type=password] {
            width: 100%; padding: 10px; margin: 6px 0 16px; border: 1px solid #ccc; border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%; padding: 12px; background-color: #007BFF; color: white; border: none; border-radius: 4px;
            font-size: 16px; cursor: pointer;
        }
        button:hover { background-color: #0056b3; }
        .errors { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .link { text-align: center; margin-top: 15px; }
        a { color: #007BFF; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <h2>Connexion Agent</h2>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Se connecter</button>
    </form>

    <div class="link">
        <p>Pas encore de compte ? <a href="register.php">Inscrivez-vous ici</a>.</p>
    </div>
</div>

</body>
</html>
