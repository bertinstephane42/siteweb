<?php
// Démarrer la session
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MicroApp Solutions - Applications innovantes pour smartphones">
    <meta name="author" content="MicroApp Solutions">
    <meta name="keywords" content="applications, smartphone, productivité, innovation">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <title>MicroApp Solutions - Accueil</title>
</head>

<body>
    <!-- Inclusion de l'en-tête -->
    <?php include './includes/header.php'; ?>
    <!-- Inclusion du fichier de connexion à la base de données -->
    <?php include './includes/db_connection.php'; ?>
    <main>
        <section class="hero">
            <div class="container">
                <h1>Bienvenue chez MicroApp Solutions</h1>
                <p>Découvrez nos applications innovantes pour simplifier votre quotidien.</p>
                <?php
                if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
                ?>
                    <a href="/pages/register.php" class="button">Créer un compte</a>
                    <a href="/pages/login.html" class="button">Connexion</a>
                <?php
                }
                ?>
            </div>
        </section>
        <section class="features">
            <div class="container">
                <h2>Pourquoi choisir MicroApp Solutions ?</h2>
                <div class="feature-grid">
                    <div class="feature-item">
                        <h3>Applications innovantes</h3>
                        <p>Des solutions qui répondent à vos besoins quotidiens et professionnels.</p>
                    </div>
                    <div class="feature-item">
                        <h3>Facilité d'utilisation</h3>
                        <p>Une interface simple et intuitive pour une expérience optimale.</p>
                    </div>
                    <div class="feature-item">
                        <h3>Support client</h3>
                        <p>Un service client réactif pour vous accompagner en toutes circonstances.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Affichage des articles existants -->
        <section class="latest-articles">
            <h2>Articles récents</h2>
            <ul>
                <?php
                // Récupération des articles dans la base de données
                try {
                    $stmt = $pdo->query("SELECT id, title, created_at FROM articles ORDER BY created_at DESC LIMIT 5");
                    $articles = $stmt->fetchAll();
                } catch (PDOException $e) {
                    echo "<p>Erreur lors du chargement des articles : " . htmlspecialchars($e->getMessage()) . "</p>";
                }
                if (!empty($articles)):
                    foreach ($articles as $article):
                ?>
                        <li>
                            <a href="#" class="article-link" data-article-id="<?php echo $article['id']; ?>">
                                <?php echo htmlspecialchars($article['title']); ?> -
                                <small><?php echo date('d/m/Y', strtotime($article['created_at'])); ?></small>
                            </a>
                        </li>
                <?php
                    endforeach;
                else:
                ?>
                    <p>Aucun article disponible.</p>
                <?php endif; ?>
            </ul>
        </section>
    </main>

    <!-- Inclusion du pied de page -->
    <?php include './includes/footer.php'; ?>
</body>
</html>
