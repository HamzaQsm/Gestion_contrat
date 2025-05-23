<style>
    .navbar {
        background-color: #2c3e50;
        padding: 15px 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
    }
    .navbar img {
        height: 40px;
        margin-right: 20px;
        vertical-align: middle;
    }
    .navbar h1 {
        color: white;
        margin: 0;
        display: inline;
        font-size: 24px;
    }
    .nav-links {
        float: right;
        margin-top: 5px;
    }
    .nav-links a {
        color: white;
        text-decoration: none;
        padding: 8px 16px;
        margin-left: 10px;
        border-radius: 4px;
        transition: background-color 0.3s;
        font-weight: 500;
    }
    .nav-links a:hover {
        background-color: #34495e;
    }
    .nav-links a.active {
        background-color: #3498db;
    }
    
    @media (max-width: 768px) {
        .nav-links {
            float: none;
            margin-top: 10px;
            text-align: center;
        }
        .nav-links a {
            display: inline-block;
            margin: 5px 10px;
        }
    }
</style>
<div class="navbar">
    <img src="logo.jpeg" alt="AXA Logo" />
    <h1>AXA Contrats</h1>
    <div class="nav-links">
        <a href="tableau_de_bord.php" <?php echo basename($_SERVER['PHP_SELF']) === 'tableau_de_bord.php' ? 'class="active"' : ''; ?>>Tableau de bord</a>
        <a href="paiements.php" <?php echo basename($_SERVER['PHP_SELF']) === 'paiements.php' ? 'class="active"' : ''; ?>>Paiements</a>
        <a href="renewals.php" <?php echo basename($_SERVER['PHP_SELF']) === 'renewals.php' ? 'class="active"' : ''; ?>>Renouvellements</a>
        <?php if (isset($_SESSION['agent_id'])): ?>
            <a href="logout.php">Déconnexion</a>
        <?php else: ?>
            <a href="login.php">Connexion</a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
