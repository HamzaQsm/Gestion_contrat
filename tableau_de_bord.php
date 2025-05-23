<?php
session_start();
require 'config.php';

if (!isset($_SESSION['agent_id'])) {
    header('Location: login.php');
    exit;
}

$agent_id = $_SESSION['agent_id'];

// Handle adding new paiement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("INSERT INTO paiements (contrat_id, date_paiement, montant, statut) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $_POST['contrat_id'],
            $_POST['date_paiement'],
            $_POST['montant'],
            $_POST['statut']
        ]);
        header("Location: tableau_de_bord.php");
        exit;
    } catch (Exception $e) {
        echo "Erreur lors de l'ajout du paiement: " . $e->getMessage();
    }
}

// Contrats pour le formulaire

// Contrats pour le formulaire
$contrats = $pdo->prepare("SELECT contrats.id, contrats.type, clients.nom AS client_nom
                           FROM contrats
                           JOIN clients ON contrats.client_id = clients.id");
$contrats->execute();
$contrats = $contrats->fetchAll();

// Total paiements effectués
$sql_total_effectues = "SELECT SUM(CASE WHEN statut = 'en attente' THEN 0 ELSE montant END) as total FROM paiements";
$total_effectues_stmt = $pdo->query($sql_total_effectues);
$total_effectues = $total_effectues_stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Contrats à renouveler (dans 30 jours à partir d’aujourd’hui)
$sql_renouvellements = "SELECT COUNT(*) as total 
                        FROM contrats 
                        WHERE DATE(date_fin) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)";
$renouvellements_stmt = $pdo->query($sql_renouvellements);
$contrats_a_renouveler = $renouvellements_stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Liste des paiements
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
    <title>Tableau de bord</title>
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
        ul {
            list-style: none;
            padding-left: 0;
        }
        ul li {
            margin-bottom: 10px;
        }
        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            display: inline-block;
            font-size: 0.8em;
        }
        .badge.bg-success {
            width: auto;        
            background-color: #4CAF50;
            color: white;
        }
        .badge.bg-danger {
            background-color: #FF0000;
            color: white;
        }
    </style>
</head>
<body>

<h2>Statistiques</h2>
<ul>
    <li><strong>Total des paiements effectués :</strong> <?= number_format($total_effectues, 2) ?> €</li>
    <li><strong>Contrats à renouveler dans 30 jours :</strong> <?= $contrats_a_renouveler ?></li>
</ul>

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
    </tr>
    <?php endforeach; ?>
</table><br>

<input type="button" value="Retour" onclick="window.location.href='paiements.php'" />
<input type="button" value="Exporter au Word" onclick="window.location.href='export_contrats_docx.php'" />
<input type="button" value="Renouvellements" onclick="window.location.href='renewals.php'" />

</div>
</div>
</body>
</html>
