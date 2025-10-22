<?php
// manage_account.php
// Gestion du compte utilisateur (PDO version, cohérente avec manage_articles.php)

session_start();

// Vérifier session
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Inclure la connexion PDO (doit définir $pdo)
include '../includes/db_connection.php';

// Générer token CSRF si nécessaire
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$userId = (int) $_SESSION['user_id'];
$message = '';
$error = '';

try {
    // Récupérer les infos utilisateur
    $stmt = $pdo->prepare("SELECT username, created_at, password FROM users WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // utilisateur introuvable -> déconnecter
        session_destroy();
        header("Location: login.php");
        exit();
    }
} catch (PDOException $e) {
    error_log("manage_account: erreur lecture user: " . $e->getMessage());
    $error = "Erreur serveur lors du chargement des informations.";
    $user = ['username' => '', 'created_at' => ''];
}

// ------- Mise à jour du nom d'utilisateur -------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_username'])) {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = "Requête invalide (CSRF).";
    } else {
        $newUsername = trim((string)($_POST['username'] ?? ''));
        if ($newUsername === '') {
            $error = "Le nom d'utilisateur ne peut pas être vide.";
        } elseif (strlen($newUsername) > 50) {
            $error = "Le nom d'utilisateur est trop long (max 50 caractères).";
        } else {
            try {
                // Vérifier unicité (autre utilisateur)
                $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username AND id != :id");
                $stmt->execute(['username' => $newUsername, 'id' => $userId]);
                if ($stmt->fetch()) {
                    $error = "Ce nom d'utilisateur est déjà utilisé.";
                } else {
                    $stmt = $pdo->prepare("UPDATE users SET username = :username WHERE id = :id");
                    $stmt->execute(['username' => $newUsername, 'id' => $userId]);
                    $message = "Nom d'utilisateur mis à jour avec succès.";
                    // mettre à jour la variable $user et la session
                    $user['username'] = $newUsername;
                    $_SESSION['username'] = $newUsername;
                }
            } catch (PDOException $e) {
                error_log("manage_account: update username: " . $e->getMessage());
                $error = "Erreur lors de la mise à jour du nom d'utilisateur.";
            }
        }
    }
}

// ------- Mise à jour du mot de passe (nécessite l'ancien) -------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = "Requête invalide (CSRF).";
    } else {
        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($oldPassword === '' || $newPassword === '' || $confirmPassword === '') {
            $error = "Tous les champs du formulaire de mot de passe sont obligatoires.";
        } elseif ($newPassword !== $confirmPassword) {
            $error = "Les nouveaux mots de passe ne correspondent pas.";
        } elseif (strlen($newPassword) < 6) {
            $error = "Le nouveau mot de passe doit contenir au moins 6 caractères.";
        } else {
            try {
                // Vérifier ancien mot de passe (hash stocké dans $user['password'])
                if (!isset($user['password']) || !password_verify($oldPassword, $user['password'])) {
                    $error = "L'ancien mot de passe est incorrect.";
                } else {
                    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
                    $stmt->execute(['password' => $newHash, 'id' => $userId]);
                    $message = "Mot de passe mis à jour avec succès.";
                    // pour sécurité, regénérer token CSRF
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    // mettre à jour le hash en mémoire
                    $user['password'] = $newHash;
                }
            } catch (PDOException $e) {
                error_log("manage_account: update password: " . $e->getMessage());
                $error = "Erreur lors de la mise à jour du mot de passe.";
            }
        }
    }
}

// ------- Suppression du compte (confirmée côté client par JS) -------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_account'])) {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = "Requête invalide (CSRF).";
    } else {
        try {
            // Supprimer l'utilisateur
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute(['id' => $userId]);

            // Détruire la session et rediriger via JS (afin d'afficher un message)
            session_unset();
            session_destroy();

            // Afficher un petit script alert + redirection vers index.php
            echo '<!doctype html><html><head><meta charset="utf-8"><title>Compte supprimé</title></head><body>';
            echo '<script>';
            echo 'alert("Votre compte a bien été supprimé.");';
            echo 'window.location.href = "../index.php";';
            echo '</script>';
            echo '</body></html>';
            exit();
        } catch (PDOException $e) {
            error_log("manage_account: delete account: " . $e->getMessage());
            $error = "Erreur lors de la suppression du compte.";
        }
    }
}

include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Gérer mon compte</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
<div class="dashboard-container">
    <h2>Gérer votre compte</h2>

    <?php if ($message): ?>
        <p class="success-message"><?= htmlspecialchars($message, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8') ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="error-message"><?= htmlspecialchars($error, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8') ?></p>
    <?php endif; ?>

    <!-- Formulaire mise à jour du nom -->
    <form action="manage_account.php" method="POST" style="max-width:600px;margin-bottom:30px;">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" maxlength="50" required value="<?= htmlspecialchars($user['username'] ?? '', ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8') ?>">

        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8') ?>">
        <button type="submit" name="update_username">Mettre à jour le nom d'utilisateur</button>
    </form>

    <hr>

    <!-- Formulaire changement de mot de passe -->
    <form action="manage_account.php" method="POST" style="max-width:600px;margin-bottom:30px;">
        <label for="old_password">Ancien mot de passe :</label>
        <input type="password" id="old_password" name="old_password" required>

        <label for="new_password">Nouveau mot de passe :</label>
        <input type="password" id="new_password" name="new_password" required>

        <label for="confirm_password">Confirmer le nouveau mot de passe :</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8') ?>">
        <button type="submit" name="update_password">Modifier le mot de passe</button>
    </form>

    <hr>

    <!-- Suppression du compte -->
    <form action="manage_account.php" method="POST" onsubmit="return confirmDelete();" style="max-width:600px;">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8') ?>">
        <button type="submit" name="delete_account" style="background-color:#d32f2f;border:none;color:#fff;padding:10px 16px;border-radius:6px;cursor:pointer;">Supprimer mon compte</button>
    </form>

    <p style="margin-top:20px;"><a href="dashboard.php" class="button">Retour au tableau de bord</a></p>
</div>

<script>
function confirmDelete() {
    return confirm("⚠️ Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible !");
}
</script>

</body>
</html>

<?php include '../includes/footer.php'; ?>
