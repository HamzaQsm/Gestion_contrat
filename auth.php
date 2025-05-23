<?php
/*
session_start();
require_once('config.php'); // ta connexion PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Récupérer l'agent par email
    $stmt = $pdo->prepare("SELECT * FROM agents WHERE email = ?");
    $stmt->execute([$email]);
    $agent = $stmt->fetch();

    if ($agent && password_verify($password, $agent['password'])) {
        // Authentification réussie
        $_SESSION['agent_id'] = $agent['id'];
        $_SESSION['agent_email'] = $agent['email'];
        header('Location: dashboard.php'); // page d’accueil après login
        exit;
    } else {
        echo "Email ou mot de passe incorrect.";
    }
} else {
    header('Location: login.php');
    exit;
}*/
?> 
<?php
session_start();
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM agents WHERE email = ?");
    $stmt->execute([$email]);
    $agent = $stmt->fetch();

    // Attention : la colonne s'appelle 'mot_de_passe' dans ta BDD
    if ($agent && password_verify($password, $agent['mot_de_passe'])) {
        $_SESSION['agent_id'] = $agent['id'];
        $_SESSION['agent_email'] = $agent['email'];
        header('Location: clients.php'); // redirige vers la page protégée
        exit;
    } else {
        // Redirection vers login avec erreur
        header('Location: login.php?error=1');
        exit;
    }
} else {
    // Redirection si tentative d'accès direct
    header('Location: login.php');
    exit;
}
