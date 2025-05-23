<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        input[type="button"] {
            width: auto;
            padding: 10px 20px; 
            background:rgb(0, 0, 0); 
            color: white; 
            border: none; 
            border-radius: 4px;
            cursor: pointer; 
            font-size: 1em; 
            margin-right: 10px;
            margin-left: 10px;
        }
    </style>
<body>
<?php
session_start();
require 'config.php';

// Récupérer les clients pour les afficher dans le formulaire
$clients = $pdo->query("SELECT id, nom FROM clients")->fetchAll();

// Ajouter un contrat
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $agent_id = $_SESSION['agent_id'];
    $stmt = $pdo->prepare("INSERT INTO contrats (client_id, agent_id,  type, montant, date_debut, date_fin, renouvellement_auto) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['client_id'],
        $agent_id,
        $_POST['type'],
        $_POST['montant'],
        $_POST['date_debut'],
        $_POST['date_fin'],
        isset($_POST['renouvellement_auto']) ? 1 : 0
    ]);
header("Location: contrats.php");
    exit;
}

// Liste des contrats avec nom du client
$sql = "SELECT c.*, cl.nom AS client_nom
        FROM contrats c
        JOIN clients cl ON c.client_id = cl.id
        ORDER BY c.date_fin DESC";
$contrats = $pdo->query($sql)->fetchAll();
?>

<h2>Ajouter un contrat</h2>
<form method="POST" action="">
    <label>Client :</label>
    <select name="client_id" required>
        <option value="">-- Sélectionner un client --</option>
        <?php foreach ($clients as $client): ?>
            <option value="<?= $client['id'] ?>"><?= htmlspecialchars($client['nom']) ?></option>
        <?php endforeach; ?>
    </select><br>

    <label>Type :</label>
    <!--<input type="text" name="type" required><br>-->
    <select name="type" id="type">
        <option value="">Tous</option>
        <option value="auto" >Auto</option>
        <option value="santé" >Santé</option>
        <option value="autre" >Autre</option>
    </select>

    <label>Montant :</label>
    <input type="number" name="montant" step="0.01" required><br>

    <label>Date début :</label>
    <input type="date" name="date_debut" required><br>

    <label>Date fin :</label>
    <input type="date" name="date_fin" required><br>

    <label>Renouvellement automatique :</label>
    <input type="checkbox" name="renouvellement_auto"><br>

    <button type="submit">Créer le contrat</button>
</form>

<h2>Contrats existants</h2>
<table border="1">
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
<input type="button" value="clients" onclick="window.location.href='clients.php'" />
<input type="button" value="paiements" onclick="window.location.href='paiements.php'" />
<input type="button" value="logout" onclick="window.location.href='logout.php'" />
</body>
</html>