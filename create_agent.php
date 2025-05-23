<?php
require 'config.php';

$email = 'amine@gmail.com';
$password = '123';

$hash = password_hash($password, PASSWORD_DEFAULT);

// Préparation de la requête
$stmt = $pdo->prepare("INSERT INTO agents (email, mot_de_passe) VALUES (?, ?)");

// Exécution avec 3 paramètres
try {
    $stmt->execute([$name, $email, $hash]);
    echo "Agent créé avec succès !";
    header("Location: dashboard.php");
    exit;
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
