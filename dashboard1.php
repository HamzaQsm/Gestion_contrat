<?php
session_start();
require_once('config.php');

// Vérifier si agent est connecté
if (!isset($_SESSION['agent_id'])) {
    header('Location: login.php');
    exit;
}

// Récupérer les infos agent (nom/email)
$stmt = $pdo->prepare("SELECT nom, email FROM agents WHERE id = ?");
$stmt->execute([$_SESSION['agent_id']]);
$agent = $stmt->fetch();

// Exemple de stats (tu devras les remplacer par des requêtes SQL réelles)
$stats = [
    'contrats' => 24,
    'paiements' => 18,
    'renouvellements' => 5,
];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard - Agent Assurance</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; padding: 30px; }
        .container { max-width: 700px; margin: auto; background: #fff; padding: 25px 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 10px; }
        .welcome { margin-bottom: 20px; font-size: 1.1em; color: #333; }
        .stats { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .stat-box {
            background: #007BFF; color: white; flex: 1; margin: 0 10px; padding: 20px; border-radius: 6px;
            text-align: center; font-size: 1.2em; box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        }
        .stat-box:first-child { margin-left: 0; }
        .stat-box:last-child { margin-right: 0; }
        .logout-btn {
            display: inline-block; padding: 10px 18px; background: #dc3545; color: white; border: none;
            border-radius: 4px; cursor: pointer; text-decoration: none; font-weight: bold;
        }
        .logout-btn:hover {
            background: #b02a37;
        }
        input[type="button"] {
            padding: 10px 20px; 
            background:  #b02a37; 
            color: white; 
            border: none; 
            border-radius: 4px;
            cursor: pointer; 
            font-size: 1em; 
            margin-right: 10px;
            margin-left: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Bienvenue sur votre Dashboard</h1><br>

    <div class="stats">
        <div class="stat-box">
            Contrats gérés<br><strong><?= $stats['contrats'] ?></strong>
        </div>
        <div class="stat-box">
            Paiements effectués<br><strong><?= $stats['paiements'] ?></strong>
        </div>
        <div class="stat-box">
            Renouvellements<br><strong><?= $stats['renouvellements'] ?></strong>
        </div>
    </div>

    <a href="logout.php" class="logout-btn">Se déconnecter</a>
    <input type="button" value="Contrats" onclick="window.location.href='contrats.php'" />
    <input type="button" value="Clients" onclick="window.location.href='clients.php'" />
</div>

</body>
</html>
