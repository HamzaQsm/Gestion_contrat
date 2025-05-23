<?php
require 'config.php';
require 'vendor/autoload.php'; // Chargement Composer

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

// Récupérer les contrats
$sql = "SELECT c.id, c.type, c.montant, c.date_debut, c.date_fin, cl.nom AS client_nom
        FROM contrats c
        JOIN clients cl ON c.client_id = cl.id
        ORDER BY c.date_debut DESC";
$contrats = $pdo->query($sql)->fetchAll();

$phpWord = new PhpWord();
$section = $phpWord->addSection();

// Titre
$section->addTitle("Liste des contrats d'assurance", 1);

// Ajouter un tableau
$table = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);

// Entête du tableau
$table->addRow();
$table->addCell(2000)->addText('Client');
$table->addCell(2000)->addText('Type de contrat');
$table->addCell(1500)->addText('Montant (€)');
$table->addCell(2000)->addText('Date début');
$table->addCell(2000)->addText('Date fin');

// Remplir le tableau avec les données
foreach ($contrats as $contrat) {
    $table->addRow();
    $table->addCell(2000)->addText(htmlspecialchars($contrat['client_nom']));
    $table->addCell(2000)->addText(htmlspecialchars($contrat['type']));
    $table->addCell(1500)->addText(number_format($contrat['montant'] ?? 0.00, 2));
    $table->addCell(2000)->addText($contrat['date_debut']);
    $table->addCell(2000)->addText($contrat['date_fin']);
}

// Envoi du fichier au navigateur
header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="contrats_list.docx"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Cache-Control: max-age=0');

$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('php://output');
exit;
