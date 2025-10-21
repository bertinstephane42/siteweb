<?php
// Cette ligne permet de démarrer une session PHP, nécessaire pour gérer les sessions d'utilisateurs (connexion, déconnexion, etc.)
session_start();

// Sécurisation des sessions en régénérant l'ID de session pour éviter les attaques de fixation
session_regenerate_id(true);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Déclaration de l'encodage des caractères, important pour afficher correctement les caractères spéciaux -->
    <meta charset="UTF-8">

    <!-- Vue pour mobile (responsive design) afin de garantir que la page est bien affichée sur les appareils mobiles -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Le titre de la page qui sera affiché dans l'onglet du navigateur -->
    <title>MicroApp Solutions</title>

    <!-- Lien vers la feuille de style CSS pour le design de la page -->
    <link rel="stylesheet" href="../assets/css/styles.css">

    <!-- Lien vers le fichier JavaScript (si nécessaire) -->
    <script src="../assets/js/script.js" defer></script>
</head>

<body>
    <!-- Le header contient généralement les éléments visibles en haut de la page (logo, menu, etc.) -->
    <header>
        <nav>
            <!-- Menu de navigation -->
            <ul>
                <li><a href="../index.php">Accueil</a></li>
                <li><a href="../pages/manage_articles.php">Gérer les articles</a></li>
                <li><a href="../pages/contact.php">Contact</a></li>
                <li><a href="../pages/mentions_legales.php">Mentions légales</a></li>
                <li><a href="../pages/cookies_policy.php">Politique de cookies</a></li>
                <li><a href="../pages/privacy_policy.php">Politique de confidentialité</a></li>
                <?php
                    // Vérifie si l'utilisateur est connecté (présence d'une session utilisateur active)
                    if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
                        echo '<li><a href="../pages/dashboard.php">Tableau de bord</a></li>';
                        echo '<li><a href="../pages/logout.php">Se déconnecter</a></li>';
                    } else {
                        echo '<li><a href="../pages/login.php">Se connecter</a></li>';
                        echo '<li><a href="../pages/register.php">S\'inscrire</a></li>';
                    }
                ?>
            </ul>
        </nav>
    </header>
