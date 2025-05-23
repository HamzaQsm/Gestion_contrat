<?php
// Configuration
session_start();
require 'config.php';

// Security check
if (!isset($_SESSION['agent_id'])) {
    header('Location: login.php');
    exit;
}

// Functions
function getRecentlyRenewedContracts($pdo) {
    $sql = "SELECT eh.*, c.type as contrat_type, cl.nom as client_nom
            FROM email_history eh
            JOIN contrats c ON eh.contrat_id = c.id
            JOIN clients cl ON c.client_id = cl.id
            WHERE eh.status = 'sent'
            ORDER BY eh.created_at DESC
            LIMIT 10";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function handleManualRenewal($pdo, $contract_id) {
    try {
        // Validate contract ID
        if (!is_numeric($contract_id)) {
            throw new Exception("ID de contrat invalide. Veuillez entrer un numéro valide.");
        }
        
        // Get contract details
        $check_sql = "SELECT id, date_fin, renouvellement_auto FROM contrats WHERE id = ?";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute([$contract_id]);
        $contract = $check_stmt->fetch();
        
        if (!$contract) {
            throw new Exception("Ce contrat n'existe pas dans le système.");
        }
        
        // Update contract end date
        $new_end_date = date('Y-m-d', strtotime('+1 year', strtotime($contract['date_fin'])));
        
        $update_sql = "UPDATE contrats 
                     SET date_fin = ?, 
                         renouvellement_auto = 0
                     WHERE id = ?";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->execute([$new_end_date, $contract_id]);
        
        // Log the renewal in history
        $history_sql = "INSERT INTO email_history (contrat_id, status, created_at)
                      VALUES (?, 'sent', NOW())";
        $history_stmt = $pdo->prepare($history_sql);
        $history_stmt->execute([$contract_id]);
        
        // Set success message
        $message = "Contrat renouvelé avec succès.";
        
        $_SESSION['manual_renew_result'] = $message;
        header("Location: renewals.php");
        exit;
        
    } catch (Exception $e) {
        error_log("Failed to renew contract ID " . $contract_id . ": " . $e->getMessage());
        
        // Log the failure in history
        $history_sql = "INSERT INTO email_history (contrat_id, status, error_message, created_at)
                      VALUES (?, 'failed', ?, NOW())";
        $history_stmt = $pdo->prepare($history_sql);
        $history_stmt->execute([$contract_id, $e->getMessage()]);
        
        // Set error message
        $message = "Erreur lors du renouvellement du contrat: " . $e->getMessage();
        
        $_SESSION['manual_renew_error'] = $message;
        header("Location: renewals.php?error=1");
        exit;
    }
}

function getContractsDueForRenewal($pdo) {
    $sql = "SELECT c.*, cl.nom AS client_nom, cl.email AS client_email,
            CASE 
                WHEN DATE(date_fin) < CURDATE() THEN 'expiré'
                WHEN DATE(date_fin) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'à renouveler'
                ELSE 'à renouveler'
            END as statut,
            CASE 
                WHEN c.renouvellement_auto = 1 THEN 'Activé'
                ELSE 'Désactivé'
            END as auto_renewal
            FROM contrats c
            JOIN clients cl ON c.client_id = cl.id
            WHERE DATE(date_fin) <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
            ORDER BY date_fin ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Manual renewal handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'renew') {
    error_log("[RENEWAL] Starting renewal process for contract ID: " . $_POST['contrat_id']);
    
    try {
        // Get contract details
        $check_sql = "SELECT id, date_fin, renouvellement_auto FROM contrats WHERE id = ?";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute([$_POST['contrat_id']]);
        $contract = $check_stmt->fetch();
        
        if (!$contract) {
            throw new Exception("Contract not found");
        }
        
        error_log("[RENEWAL] Found contract: " . print_r($contract, true));
        
        // Update contract end date
        $new_end_date = date('Y-m-d', strtotime('+1 year', strtotime($contract['date_fin'])));
        error_log("[RENEWAL] New end date: " . $new_end_date);
        
        $update_sql = "UPDATE contrats 
                     SET date_fin = ?, 
                         renouvellement_auto = 0
                     WHERE id = ?";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->execute([$new_end_date, $_POST['contrat_id']]);
        
        error_log("[RENEWAL] Update result: " . $update_stmt->rowCount() . " rows affected");
        
        // Log the renewal in history
        $history_sql = "INSERT INTO email_history (contrat_id, status, created_at)
                      VALUES (?, 'sent', NOW())";
        $history_stmt = $pdo->prepare($history_sql);
        $history_stmt->execute([$_POST['contrat_id']]);
        
        error_log("[RENEWAL] History log successful");
        
        // Set success message
        $message = "Contrat renouvelé avec succès.";
        
        $_SESSION['manual_renew_result'] = $message;
        header("Location: renewals.php");
        exit;
        
    } catch (Exception $e) {
        error_log("[RENEWAL] Error: " . $e->getMessage());
        error_log("[RENEWAL] Stack trace: " . $e->getTraceAsString());
        
        // Log the failure in history
        $history_sql = "INSERT INTO email_history (contrat_id, status, error_message, created_at)
                      VALUES (?, 'failed', ?, NOW())";
        $history_stmt = $pdo->prepare($history_sql);
        $history_stmt->execute([$_POST['contrat_id'], $e->getMessage()]);
        
        // Set error message
        $message = "Erreur lors du renouvellement du contrat: " . $e->getMessage();
        
        $_SESSION['manual_renew_error'] = $message;
        header("Location: renewals.php?error=1");
        exit;
    }
}

// Get contracts due for renewal
$contracts = getContractsDueForRenewal($pdo);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renouvellements de Contrats - AXA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #007bff;
            --success-color: #28a745;
            --error-color: #dc3545;
            --background-color: #f8f9fa;
            --text-color: #212529;
        }

        body {
            margin-top: 50px;
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        h1, h2, h3 {
            color: var(--text-color);
            margin-bottom: 20px;
        }

        .success-message {
            background-color: #d4edda;
            color: var(--success-color);
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .error-message {
            background-color: #f8d7da;
            color: var(--error-color);
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .contracts-list {
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: var(--primary-color);
            color: white;
            padding: 12px;
            text-align: left;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }

        .renew-button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .renew-button:hover {
            background-color: #0056b3;
        }

        .back-button {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .back-button:hover {
            background-color: #5a6268;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            table {
                font-size: 14px;
            }
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .renewal-actions {
            margin-top: 10px;
        }
        .renew-button {
            background: #007bff;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .renew-button:hover {
            background: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .manual-renewal-form {
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 4px;
        }
        .badge {
            padding: 0.5em 0.75em;
            border-radius: 0.25rem;
        }
        .badge.bg-warning {
            background-color: #ffc107;
            color: #212529;
        }
        .badge.bg-danger {
            background-color: #dc3545;
            color: white;
        }
        .badge.bg-success {
            background-color: #28a745;
            color: white;
        }
        .badge.bg-secondary {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($_SESSION['manual_renew_result'])): ?>
            <div class="success-message">
                <?= htmlspecialchars($_SESSION['manual_renew_result']) ?>
            </div>
            <?php unset($_SESSION['manual_renew_result']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['manual_renew_error'])): ?>
            <div class="error-message">
                <?= htmlspecialchars($_SESSION['manual_renew_error']) ?>
            </div>
            <?php unset($_SESSION['manual_renew_error']); ?>
        <?php endif; ?>
        
        <h1>Renouvellements de Contrats</h1>
        <!-- Contracts List -->
        <div class="contracts-list">
        <?php if (empty($contracts)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Aucun contrat à renouveler dans les 30 prochains jours.
            </div>
        <?php else: ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Numéro de Contrat</th>
                        <th>Client</th>
                        <th>Type de Contrat</th>
                        <th>Date de Fin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contracts as $contract): ?>
                        <tr>
                            <td><?= htmlspecialchars($contract['id']) ?></td>
                            <td><?= htmlspecialchars($contract['client_nom']) ?></td>
                            <td><?= htmlspecialchars($contract['type']) ?></td>
                            <td><?= date('d/m/Y', strtotime($contract['date_fin'])) ?></td>
                            <td>
                                <form method="POST" action="renewals.php" class="d-inline">
                                    <input type="hidden" name="action" value="renew">
                                    <input type="hidden" name="contrat_id" value="<?= htmlspecialchars($contract['id']) ?>">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-sync-alt"></i> Renouveler
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        </div>

        <!-- Recently Renewed Contracts -->
        <div class="recently-renewed-list mt-4">
            <h2>Contrats Récemment Renouvelés</h2>
            <?php
            $recently_renewed = getRecentlyRenewedContracts($pdo);
            if (empty($recently_renewed)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Aucun contrat renouvelé récemment.
                </div>
            <?php else: ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Type de Contrat</th>
                            <th>Numéro de Contrat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recently_renewed as $renewal): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($renewal['created_at'])) ?></td>
                                <td><?= htmlspecialchars($renewal['client_nom']) ?></td>
                                <td><?= htmlspecialchars($renewal['contrat_type']) ?></td>
                                <td><?= htmlspecialchars($renewal['contrat_id']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    <div class="text-center mt-4">
        <a href="tableau_de_bord.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour au Tableau de bord
        </a>
    </div>
    </div>
</div>

<?php require 'footer.php'; ?>
</body>
</html>

<?php if (isset($_SESSION['manual_renew_result'])): ?>
    <div class="success-message">
        <p><?= htmlspecialchars($_SESSION['manual_renew_result']) ?></p>
    </div>
    <?php unset($_SESSION['manual_renew_result']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['manual_renew_error'])): ?>
    <div class="error-message">
        <p><?= htmlspecialchars($_SESSION['manual_renew_error']) ?></p>
    </div>
    <?php unset($_SESSION['manual_renew_error']); ?>
<?php endif; ?>

</body>
</html>
