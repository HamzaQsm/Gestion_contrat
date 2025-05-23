<?php
session_start();
$email = isset($_GET['email']) ? $_GET['email'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Agent - AXA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                        url('bg.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            color: #333;
        }

        nav {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #007bff;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        nav a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        nav a:hover {
            color: #007bff;
        }

        .login-button {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-button:hover {
            background: #0056b3;
        }

        .login-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1001;
        }

        .login-container.active {
            display: flex;
        }

        .login-box {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }

        .login-box h2 {
            text-align: center;
            color: #333;
            margin-bottom: 2rem;
            font-size: 1.5rem;
        }

        .error-message {
            color: #dc3545;
            text-align: center;
            margin-bottom: 1.5rem;
            padding: 1rem;
            border-radius: 5px;
            background-color: rgba(220, 53, 69, 0.1);
        }

        .login-box input {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .options {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            align-items: center;
        }

        .options label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #666;
        }

        .options a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }

        .options a:hover {
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 1rem;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-login:hover {
            background: #0056b3;
        }

        .register {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
        }

        .register a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }

        .register a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            nav {
                padding: 1rem;
            }

            nav ul {
                gap: 1rem;
            }

            .login-box {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <nav>
        <div class="logo">AXA Assurances</div>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="about.php">À propos</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
        <button class="login-button" onclick="toggleLogin()">Connexion</button>
    </nav>

    <div class="login-container">
        <div class="login-box">
            <span class="close-btn" onclick="toggleLogin()">&times;</span>
            <h2>Connexion Agent</h2>
            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <?= $error ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="auth.php">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="email" placeholder="Email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                <input type="password" placeholder="Mot de passe" id="password" name="password" required>
                <div class="options">
                    <label><input type="checkbox" name="remember"> Se souvenir de moi</label>
                    <a href="forgot_password.php">Mot de passe oublié ?</a>
                </div>
                <button type="submit" class="btn-login">Se connecter</button>
                <div class="register">
                    Pas encore de compte ? <a href="register.php">Inscrivez-vous</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleLogin() {
            const container = document.querySelector('.login-container');
            container.classList.toggle('active');
        }

        // Close login box when clicking outside
        document.addEventListener('click', function(e) {
            const container = document.querySelector('.login-container');
            if (container.classList.contains('active') && !e.target.closest('.login-container')) {
                container.classList.remove('active');
            }
        });

        // Close login box when pressing Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const container = document.querySelector('.login-container');
                if (container.classList.contains('active')) {
                    container.classList.remove('active');
                }
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (!email || !password) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs.');
                return;
            }

            // Basic email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Veuillez entrer une adresse email valide.');
                return;
            }
        });
    </script>
</body>
</html>