<?php
session_start();
require 'config.php';
require 'vendor/autoload.php'; // PHPMailer via Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

echo "<h3>Renouvellements automatiques en cours...</h3>";

try {
    // Date cible : contrats expirant dans 30 jours
    $dateCible = date('Y-m-d', strtotime('+30 days'));

    // Récupérer les contrats à renouveler
    $stmt = $pdo->prepare("SELECT c.*, cl.nom AS client_nom, cl.email
                           FROM contrats c
                           JOIN clients cl ON c.client_id = cl.id
                           WHERE c.date_fin = ? AND c.renouvellement_auto = 1");
    $stmt->execute([$dateCible]);
    $contrats = $stmt->fetchAll();

    if (count($contrats) === 0) {
        echo "<p>Aucun contrat à renouveler pour le moment.</p>";
    }

    foreach ($contrats as $contrat) {
        $nouvelle_date_debut = $contrat['date_fin'];
        $nouvelle_date_fin = date('Y-m-d', strtotime($contrat['date_fin'] . ' +1 year'));

        // Insérer le nouveau contrat
        $stmt2 = $pdo->prepare("INSERT INTO contrats (client_id, type, montant, date_debut, date_fin, renouvellement_auto)
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt2->execute([
            $contrat['client_id'],
            $contrat['type'],
            $contrat['montant'],
            $nouvelle_date_debut,
            $nouvelle_date_fin,
            1
        ]);

        // Envoi email au client
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'hamzaqouassem@gmail.com';  // Ton adresse Gmail
            $mail->Password   = 'motdepasseapplication';     // Utilise un "mot de passe d'application"
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('hamzaqouassem@gmail.com', 'Assurance');
            $mail->addAddress($contrat['email'], $contrat['client_nom']);

            $mail->Subject = 'Renouvellement automatique de votre contrat';
            $mail->Body    = "Bonjour {$contrat['client_nom']},\n\nVotre contrat \"{$contrat['type']}\" a été renouvelé automatiquement jusqu’au $nouvelle_date_fin.";

            $mail->send();
            echo "<p>Contrat de {$contrat['client_nom']} renouvelé et email envoyé.</p>";
        } catch (Exception $e) {
            echo "<p>Erreur lors de l'envoi de l'email à {$contrat['client_nom']}: {$mail->ErrorInfo}</p>";
        }
    }
} catch (PDOException $e) {
    echo "<p>Erreur base de données : " . $e->getMessage() . "</p>";
}
?>
