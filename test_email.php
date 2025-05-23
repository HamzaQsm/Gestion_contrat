<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$appPassword = 'TON_MOT_DE_PASSE_APP'; // Remplace ici par ton mot de passe d'application Gmail
$toEmail = 'client@exemple.com';       // Adresse email destinataire
$toName = 'Nom Client';                 // Nom du destinataire

$mail = new PHPMailer(true);

try {
    // Configuration SMTP Gmail
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'hamzaqouassem@gmail.com';
    $mail->Password = $appPassword;
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Expéditeur & destinataire
    $mail->setFrom('hamzaqouassem@gmail.com', 'Assurance Hamza');
    $mail->addAddress($toEmail, $toName);

    // Contenu
    $mail->Subject = 'Test envoi email PHPMailer';
    $mail->Body    = "Bonjour $toName,\n\nCeci est un email de test envoyé depuis PHP avec PHPMailer et Gmail SMTP.\n\nCordialement,\nHamza";

    $mail->send();
    echo 'Email envoyé avec succès !';
} catch (Exception $e) {
    echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
}
