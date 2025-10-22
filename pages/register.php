<?php
// Inclure la connexion à la base de données
include '../includes/db_connection.php';

// Vérifier si la connexion PDO est disponible
if (!isset($pdo)) {
    die("Erreur : connexion à la base de données non disponible.");
}

// Initialiser une variable pour l'erreur
$error = "";

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer et nettoyer les données du formulaire
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $password_confirm = trim($_POST['password_confirm']);

    // Vérifier si les champs sont vides
    if (empty($username) || empty($password) || empty($password_confirm)) {
        $error = "Tous les champs sont obligatoires.";
    // Vérifier la longueur minimale du mot de passe
    elseif (strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } 
    // Vérifier la correspondance des mots de passe
    } elseif ($password !== $password_confirm) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // Hacher le mot de passe pour la sécurité
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Vérifier si l'utilisateur existe déjà
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            if ($stmt->rowCount() > 0) {
                $error = "Cet utilisateur existe déjà.";
            } else {
                // Insérer le nouvel utilisateur dans la base de données
                $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
                $stmt->execute(['username' => $username, 'password' => $hashed_password]);

                // Rediriger l'utilisateur vers la page de connexion
                header("Location: login.php");
                exit();
            }
        } catch (PDOException $e) {
            $error = "Erreur lors de l'inscription : " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<?php include '../includes/header.php'; ?>
<body>
    <div class="form-container">
        <h2>Créer un compte</h2>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>

            <label for="password_confirm">Confirmer le mot de passe :</label>
            <input type="password" id="password_confirm" name="password_confirm" required>

            <button type="submit">S'inscrire</button>
        </form>

        <p>Déjà un compte ? <a href="login.php" class="button">Connectez-vous ici</a></p>
        <p><a href="../index.php" class="button">Retour à la page d'accueil</a></p>
    </div>
</body>
</html>
<?php include '../includes/footer.php'; ?>
