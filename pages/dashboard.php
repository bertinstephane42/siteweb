<?php
// Démarrer la session
session_start();
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
    header("Location: login.php");
    exit();
}

// Inclure la connexion à la base de données
include '../includes/db_connection.php';

// Récupérer les informations de l'utilisateur connecté
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();
?>

<?php // Inclusion du fichier header
include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Bienvenue, <?= htmlspecialchars($user['username']) ?> !</h2>

        <p>Vous êtes connecté à votre tableau de bord.</p>

        <p><a href="manage_articles.php" class="button">Gérer vos articles</a></p>
        <p><a href="manage_account.php" class="button">Gérer votre compte</a></p>
        <p><a href="logout.php" class="button logout-button">Se déconnecter</a></p>
    </div>
</body>
</html>
<?php
// Inclusion du fichier footer
include '../includes/footer.php';
?>
