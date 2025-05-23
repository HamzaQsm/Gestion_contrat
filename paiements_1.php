<?php
session_start();
require 'config.php';

/*if (!isset($_SESSION['agent_id'])) {
    header('Location: login.php');
    exit;
}*/

$agent_id = $_SESSION['agent_id'];

// Récupérer tous les contrats liés à cet agent pour le formulaire
$contrats = $pdo->prepare("SELECT contrats.id, contrats.type, clients.nom AS client_nom
                           FROM contrats
                           JOIN clients ON contrats.client_id = clients.id");
$contrats->execute(); // ✅ No parameters needed

$contrats = $contrats->fetchAll();

// Ajouter un paiement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO paiements (contrat_id, date_paiement, montant, statut) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $_POST['contrat_id'],
        $_POST['date_paiement'],
        $_POST['montant'],
        $_POST['statut']
    ]);
    header("Location: paiements.php");
    exit;
}

// Afficher les paiements liés aux contrats de cet agent
$sql = "SELECT p.*, c.type, cl.nom AS client_nom
        FROM paiements p
        JOIN contrats c ON p.contrat_id = c.id
        JOIN clients cl ON c.client_id = cl.id
        ORDER BY p.date_paiement DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$paiements = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Suivi des paiements</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        input[type="button"] {
            width: 15%;
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
</head>
<body>

<h2>Ajouter un paiement</h2>
<form method="POST">
    <label>Contrat :</label>
    <select name="contrat_id" required>
        <option value="">-- Sélectionner un contrat --</option>
        <?php foreach ($contrats as $contrat): ?>
            <option value="<?= $contrat['id'] ?>">
                <?= htmlspecialchars($contrat['client_nom'] . ' - ' . $contrat['type']) ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <label>Date de paiement :</label>
    <input type="date" name="date_paiement" required><br>

    <label>Montant :</label>
    <input type="number" name="montant" step="0.01" required><br>

    <label>Statut :</label>
    <select name="statut">
        <option value="effectué">Effectué</option>
        <option value="en attente">En attente</option>
    </select><br>

    <button type="submit">Enregistrer le paiement</button>
</form>
<hr>
<form method="POST"></form>
<h2>Liste des paiements</h2>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Client</th>
        <th>Contrat</th>
        <th>Date</th>
        <th>Montant</th>
        <th>Statut</th>
    </tr>
    <?php foreach ($paiements as $p): ?>
    <tr>
        <td><?= htmlspecialchars($p['client_nom']) ?></td>
        <td><?= htmlspecialchars($p['type']) ?></td>
        <td><?= htmlspecialchars($p['date_paiement']) ?></td>
        <td><?= number_format($p['montant'], 2) ?> €</td>
        <td><?= htmlspecialchars($p['statut']) ?></td>
    </tr>
    <?php endforeach; ?>
</table><br>
<input type="button" value="Tableau" onclick="window.location.href='tableau_de_bord.php'" />
    </form>
</body>
</html>
