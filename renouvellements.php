<?php
require 'config.php';
require 'vendor/autoload.php'; // PHPMailer via Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dateDebut = date('Y-m-d');
$dateFin = date('Y-m-d', strtotime('+30 days'));

try {
    // Récupérer les contrats expirant dans les 30 prochains jours avec renouvellement auto activé
    $stmt = $pdo->prepare("SELECT c.*, cl.email, cl.nom AS client_nom
                           FROM contrats c
                           JOIN clients cl ON c.client_id = cl.id
                           WHERE c.date_fin BETWEEN ? AND ? AND c.renouvellement_auto = 1");
    $stmt->execute([$dateDebut, $dateFin]);
    $contrats = $stmt->fetchAll();

    foreach ($contrats as $contrat) {
        $nouvelle_date_debut = $contrat['date_fin'];
        $nouvelle_date_fin = date('Y-m-d', strtotime($contrat['date_fin'] . ' +1 year'));

        // Vérifier si un contrat renouvelé existe déjà (pour éviter doublons)
        $check = $pdo->prepare("SELECT COUNT(*) FROM contrats WHERE client_id = ? AND date_debut = ? AND type = ?");
        $check->execute([$contrat['client_id'], $nouvelle_date_debut, $contrat['type']]);
        if ($check->fetchColumn() > 0) {
            // Contrat déjà renouvelé, passer au suivant
            continue;
        }

        // Insérer nouveau contrat renouvelé
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

        // Envoi email notification
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';   // SMTP de Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'hamzaqouassem@gmail.com';
            $mail->Password = 'Ton_Mot_De_Passe_Ici'; // Attention à ne pas laisser en clair dans un vrai projet
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('hamzaqouassem@gmail.com', 'Assurance');
            $mail->addAddress($contrat['email'], $contrat['client_nom']);

            $mail->Subject = 'Renouvellement automatique de votre contrat';
            $mail->Body    = "Bonjour {$contrat['client_nom']},\n\nVotre contrat \"{$contrat['type']}\" a été renouvelé automatiquement jusqu’au $nouvelle_date_fin.";

            $mail->send();
        } catch (Exception $e) {
            error_log("Erreur email pour client {$contrat['client_nom']} ({$contrat['email']}): " . $mail->ErrorInfo);
        }
    }
} catch (PDOException $e) {
    error_log("Erreur PDO dans renouvellement automatique : " . $e->getMessage());
}
?>
