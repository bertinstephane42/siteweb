<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Inclure la connexion à la base de données
include '../includes/db_connection.php';

// Créer un jeton CSRF si ce n'est pas déjà fait
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = '';
$error = '';

// Gérer l'ajout d'un nouvel article
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_article'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // Vérification du jeton CSRF
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Requête invalide.");
    }

    if (!empty($title) && !empty($content)) {
        if (strlen($title) > 255) {
            $error = "Le titre est trop long (maximum 255 caractères).";
        } else {
            try {
                // Insérer l'article dans la base de données
                $stmt = $pdo->prepare("INSERT INTO articles (title, content, created_at) VALUES (:title, :content, NOW())");
                $stmt->execute(['title' => $title, 'content' => $content]);
                $message = "Article ajouté avec succès.";
            } catch (PDOException $e) {
                error_log($e->getMessage());
                $error = "Une erreur est survenue. Veuillez réessayer plus tard.";
            }
        }
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}

// Gérer la suppression d'un article
if (isset($_GET['delete'])) {
    $article_id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
    if ($article_id) {
        try {
            // Supprimer l'article de la base de données
            $stmt = $pdo->prepare("DELETE FROM articles WHERE id = :id");
            $stmt->execute(['id' => $article_id]);
            $message = "Article supprimé avec succès.";
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $error = "Erreur lors de la suppression de l'article : Veuillez réessayer plus tard.";
        }
    } else {
        $error = "Identifiant d'article invalide.";
    }
}

// Récupérer tous les articles
try {
    $stmt = $pdo->prepare("SELECT * FROM articles ORDER BY created_at DESC");
    $stmt->execute();
    $articles = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $error = "Impossible de récupérer les articles. Veuillez réessayer plus tard.";
}
?>

<?php include '../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Articles</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="../assets/js/script.js"></script>
</head>
<body>
    <div class="dashboard-container">
        <h2>Gérer vos articles</h2>

        <!-- Affichage des messages de succès ou d'erreur -->
        <?php if (!empty($message)): ?>
            <p class="success"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <!-- Formulaire d'ajout d'un article -->
        <form action="manage_articles.php" method="POST">
            <label for="title">Titre de l'article :</label>
            <input type="text" id="title" name="title" maxlength="255" required>

            <label for="content">Contenu de l'article :</label>
            <textarea id="content" name="content" rows="4" required></textarea>

            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <button type="submit" name="add_article">Ajouter l'article</button>
        </form>

        <h3>Liste de vos articles :</h3>
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($articles)): ?>
                    <?php foreach ($articles as $article): ?>
                        <tr>
                            <td>
                                <!-- Lien interactif utilisant api/get_article.php -->
                                <a href="#" class="article-link" data-id="<?= $article['id'] ?>"><?= htmlspecialchars($article['title']) ?></a>
                            </td>
                            <td><?= htmlspecialchars($article['created_at']) ?></td>
                            <td>
                                <!-- Lien pour supprimer l'article -->
                                <a href="manage_articles.php?delete=<?= $article['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">Aucun article trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
	<!-- Zone d'affichage du contenu d'article -->
	<div id="article-detail" class="article-detail">
    	<p>Sélectionnez un article pour afficher son contenu ici.</p>
	</div>
        <p><a href="dashboard.php" class="button">Retour au tableau de bord</a></p>
    </div>
</body>
</html>

<?php include '../includes/footer.php'; ?>
