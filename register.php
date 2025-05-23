<?php
session_start();
require_once('config.php');

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et nettoyer les champs
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validation
    if (empty($nom)) {
        $errors[] = "Le nom complet est requis.";
    }
    if (empty($email)) {
        $errors[] = "L'email est requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide.";
    }
    if (empty($password)) {
        $errors[] = "Le mot de passe est requis.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
    }
    if ($password !== $password_confirm) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    // Vérifier si email existe déjà
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM agents WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "Un agent avec cet email existe déjà.";
        }
    }

    // Insertion en base si pas d’erreurs
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO agents (nom, email, mot_de_passe) VALUES (?, ?, ?)");
        if ($stmt->execute([$nom, $email, $password_hash])) {
            $success = "Inscription réussie ! Vous pouvez maintenant <a href='login.php'>vous connecter</a>.";
            // Optionnel : vider les champs
            $nom = $email = '';
        } else {
            $errors[] = "Une erreur est survenue, veuillez réessayer.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Inscription Agent - Assurance</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; padding: 40px; margin-top: 50px; background-color: #007bff;}
        .container { max-width: 400px; margin: auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 20px; }
        input[type=text], input[type=email], input[type=password] {
            width: 100%; padding: 10px; margin: 6px 0 16px; border: 1px solid #ccc; border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%; padding: 12px; background-color: #007BFF; color: white; border: none; border-radius: 4px;
            font-size: 16px; cursor: pointer;
        }
        button:hover { background-color: #0056b3; }
        .errors { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .link { text-align: center; margin-top: 15px; }
        a { color: #007BFF; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <h2>Créer un compte agent</h2>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="nom">Nom complet</label>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($nom ?? '') ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>

        <label for="password_confirm">Confirmer mot de passe</label>
        <input type="password" id="password_confirm" name="password_confirm" required>

        <button type="submit">S’inscrire</button>
    </form>

    <div class="link">
        <p>Déjà un compte ? <a href="login.php">Connectez-vous ici</a>.</p>
    </div>
</div>

</body>
</html>
