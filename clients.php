<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            margin-left:30%;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 30px;
            background-color: #007bff;
        }
        nav {
            background: #2980b9;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        nav a {
            color: white;
            margin-right: 15px;
            text-decoration: none;
            font-weight: bold;
        }
        h2 {
            color: #ffff;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 600px;
        }
        input, textarea {
            padding: 10px;
            margin-top: 10px;
            width: 90%;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            /* width: auto;
            background: #0056b3;
            color: white;
            font-weight: bold;
            cursor: pointer; */
            margin-top: 10px;
            width: 90%;
            padding: 10px 20px; 
            background:   #007bff; 
            color: white; 
            border: none; 
            border-radius: 4px;
            cursor: pointer; 
            font-size: 1em; 
            margin-right: 10px;
            margin-left: 10px;
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
            text-align: left;
        }
        th {
            background: #2980b9;
            color: white;
        }
        tr:hover {
            background: #f0f0f0;
        }
        input[type="button"] {
            width: auto;
            padding: 10px 20px; 
            background:  black; 
            color: white; 
            border: none; 
            border-radius: 4px;
            cursor: pointer; 
            font-size: 1em; 
            margin-right: 10px;
            margin-left: 10px;
        }
        ul{
            color: #ffffff;
            font-size: large;
        }
    </style>
</head>
<body>
    

<?php
session_start();
require 'config.php';

// Ajouter un client
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO clients (nom, email, telephone, adresse) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $_POST['nom'],
        $_POST['email'],
        $_POST['telephone'],
        $_POST['adresse']
    ]);
    header("Location: clients.php");
    exit;
}

// Lister les clients
$clients = $pdo->query("SELECT * FROM clients")->fetchAll();
?>

<h2>Ajouter un client</h2>
<form method="POST">
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="email" name="email" placeholder="Email">
    <input type="text" name="telephone" placeholder="Téléphone">
    <textarea name="adresse" placeholder="Adresse"></textarea>
    <button type="submit">Ajouter</button>
</form>

<h2>Liste des clients</h2>
<ul>
<?php foreach ($clients as $client): ?>
    <li><?= htmlspecialchars($client['nom']) ?> - <?= htmlspecialchars($client['email']) ?> - <?= htmlspecialchars($client['telephone']) ?></li>
<?php endforeach; ?>
</ul>
<input type="button" value="Contrats" onclick="window.location.href='contrats.php'" />
</body>
</html>