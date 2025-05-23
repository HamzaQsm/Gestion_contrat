<?php
session_start();

// Si tu veux protéger cette page, ajoute ici une vérification de session, ex:
// if (!isset($_SESSION['agent_logged_in'])) { header('Location: login.php'); exit; }

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Exécuter le script de renouvellement en incluant le fichier PHP
    try {
        include 'renouvellements_auto.php';  // Le script que tu as déjà
        $message = "Renouvellements lancés avec succès.";
    } catch (Exception $e) {
        $message = "Erreur lors du lancement des renouvellements : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Lancer les renouvellements automatiques</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2em; }
        button { padding: 10px 20px; font-size: 16px; }
        .message { margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Lancer les renouvellements automatiques</h1>
    <form method="POST">
        <button type="submit">Lancer le script</button>
    </form>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
</body>
</html>
