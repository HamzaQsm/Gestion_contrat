<?php
session_start();
require 'config.php';

if (!isset($_SESSION['agent_id'])) {
    header('Location: login.php');
    exit;
}

$agent_id = $_SESSION['agent_id'];

// Récupérer les contrats disponibles
$contrats = $pdo->prepare("SELECT contrats.id, contrats.type, clients.nom AS client_nom
                           FROM contrats
                           JOIN clients ON contrats.client_id = clients.id");
$contrats->execute();
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

// Afficher les paiements
$sql = "SELECT p.*, c.type, cl.nom AS client_nom,
            CASE 
                WHEN p.statut = 'en attente' THEN 0
                ELSE 1
            END as est_effectue
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
        html{
            background-color: #007bff;
        }
        body{
            background-color: #ffffff;
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
            margin-right: 10px;
            margin-left: 10px;
        }
        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            display: inline-block;
            font-size: 0.8em;
        }
        .badge.bg-success {
            background-color: #4CAF50;
            color: white;
        }
        .badge.bg-danger {
            background-color: #FF0000;
            color: white;
        }
        .container{
        background-color: #ffffff;
        }
    </style>
</head>
<body>
<div class="container">
<h2>Ajouter un paiement</h2>
<form method="POST" action=''>
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
    <input type="number" name="montant" step="1" required><br>

    <label>Statut :</label>
    <select name="statut" required>
        <option value="">-- Sélectionner un statut --</option>
        <option value="effectue">Effectué</option>
        <option value="en attente">En attente</option>
    </select><br>

    <button type="submit">Enregistrer</button>
</form>

<hr>

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
        <td>
            <?php if ($p['statut'] === 'en attente'): ?>
                <span class="badge bg-danger"><?= htmlspecialchars($p['statut']) ?></span>
            <?php else: ?>
                <span class="badge bg-success">effectue</span>
            <?php endif; ?>
        </td>
        <!-- "effectue" (paid) - green badge
             "en attente" (pending) - red badge
        -->
    </tr>
    <?php endforeach; ?>
</table>
<br>
<input type="button" value="Tableau de bord" onclick="window.location.href='tableau_de_bord.php'" />
<input type="button" value="Se déconnecter" onclick="window.location.href='login.php'" />
</div>
</body>
</html>
