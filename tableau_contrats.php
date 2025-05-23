<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 30px;
        }
        h2 {
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #2980b9;
            color: white;
        }
    </style>
</head>
<body>
    
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
    <?php 
    require 'config.php';
    $sql = "SELECT c.*, cl.nom AS client_nom
    FROM contrats c
    JOIN clients cl ON c.client_id = cl.id
    ORDER BY c.date_fin DESC";
$contrats = $pdo->query($sql)->fetchAll();
    foreach ($contrats as $contrat): 
    ?>
    <tr>
        <td><?= htmlspecialchars($contrat['client_nom']) ?></td>
        <td><?= htmlspecialchars($contrat['type']) ?></td>
        <td><?= number_format($contrat['montant'], 2) ?> €</td>
        <td><?= $contrat['date_debut'] ?></td>
        <td><?= $contrat['date_fin'] ?></td>
        <td><?= $contrat['renouvellement_auto'] ? 'Oui' : 'Non' ?></td>
    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>