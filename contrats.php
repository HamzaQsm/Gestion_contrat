<?php
session_start();
require 'config.php';

// Vérifier si l'agent est connecté
if (!isset($_SESSION['agent_id'])) {
    header('Location: login.php');
    exit;
}

// Récupérer les clients
$clients = $pdo->query("SELECT id, nom FROM clients")->fetchAll();

// Ajouter un contrat
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO contrats (client_id, type, montant, date_debut, date_fin, renouvellement_auto, agent_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['client_id'],
        $_POST['type'],
        $_POST['montant'],
        $_POST['date_debut'],
        $_POST['date_fin'],
        isset($_POST['renouvellement_auto']) ? 1 : 0,
        $_SESSION['agent_id']
    ]);
    header("Location: contrats.php");
    exit;
}

// Liste des contrats de l'agent connecté
$sql = "SELECT c.*, cl.nom AS client_nom
        FROM contrats c
        JOIN clients cl ON c.client_id = cl.id
        WHERE c.agent_id = ?
        ORDER BY c.date_fin DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['agent_id']]);
$contrats = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contrats</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        body{
            background-color: #ffffff;
        }
        html{
            background-color: #007bff;
        }
        input[type="button"] {
            width: auto;
            padding: 10px 20px; 
            background: rgb(0, 0, 0); 
            color: white; 
            border: none; 
            border-radius: 4px;
            cursor: pointer; 
            font-size: 1em; 
            margin: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #aaa;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        form {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<h2>Ajouter un contrat</h2>
<form method="POST" action="">
    <label>Client :</label>
    <select name="client_id" required>
        <option value="">-- Sélectionner un client --</option>
        <?php foreach ($clients as $client): ?>
            <option value="<?= $client['id'] ?>"><?= htmlspecialchars($client['nom']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Type :</label>
    <select name="type" required>
        <option value="">-- Sélectionner le type --</option>
        <option value="auto">Auto</option>
        <option value="santé">Santé</option>
        <option value="vie">Vie</option>
        <option value="voyage">Voyage</option>
        <option value="autre">Autre</option>
    </select><br><br>

    <label>Montant :</label>
    <input type="number" name="montant" step="0.01" required><br><br>

    <label>Date début :</label>
    <input type="date" name="date_debut" required><br><br>

    <label>Date fin :</label>
    <input type="date" name="date_fin" required><br><br>

    <label>Renouvellement automatique :</label>
    <input type="checkbox" name="renouvellement_auto"><br><br>

    <button type="submit">Créer le contrat</button>
</form>

<h2>Contrats existants</h2>
<table>
    <tr>
        <th>Client</th>
        <th>Type</th>
        <th>Montant</th>
        <th>Début</th>
        <th>Fin</th>
        <th>Renouvellement</th>
    </tr>
    <?php foreach ($contrats as $contrat): ?>
    <tr>
        <td><?= htmlspecialchars($contrat['client_nom']) ?></td>
        <td><?= htmlspecialchars($contrat['type']) ?></td>
        <td><?= number_format($contrat['montant'], 2) ?> €</td>
        <td><?= $contrat['date_debut'] ?></td>
        <td><?= $contrat['date_fin'] ?></td>
        <td><?= $contrat['renouvellement_auto'] ? 'Oui' : 'Non' ?></td>
    </tr>
    <?php endforeach; ?>
</table><br>

<input type="button" value="Paiements" onclick="window.location.href='paiements.php'" />
<input type="button" value="Déconnexion" onclick="window.location.href='logout.php'" />

</body>
</html>
