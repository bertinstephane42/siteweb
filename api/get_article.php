<?php
// Inclure la connexion à la base de données
require_once __DIR__ . '/../includes/db_connection.php';

// Définir les en-têtes pour les requêtes AJAX
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *'); // Restreindre à votre domaine en production (indiquer le domaine)
header('Access-Control-Allow-Methods: GET'); // Restreindre aux requêtes GET

// Vérifier si un ID est fourni et s'il est valide
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400); // Code de réponse 400 : Mauvaise requête
    echo json_encode(['error' => 'ID d\'article invalide ou non spécifié.']);
    exit;
}

// Assainir l'entrée utilisateur
$articleId = (int) $_GET['id'];

try {
    // Préparer une requête SQL pour récupérer l'article
    $query = $pdo->prepare("SELECT title, content FROM articles WHERE id = :id");
    $query->bindParam(':id', $articleId, PDO::PARAM_INT);
    $query->execute();

    // Vérifier si un article est trouvé
    if ($query->rowCount() === 0) {
        http_response_code(404); // Code de réponse 404 : Non trouvé
        echo json_encode(['error' => 'Article non trouvé.']);
        exit;
    }

    // Récupérer les données de l'article
    $article = $query->fetch(PDO::FETCH_ASSOC);

    // Nettoyer les données pour éviter d'éventuelles injections ou affichages non sécurisés
    $article['title'] = htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8');
    $article['content'] = htmlspecialchars($article['content'], ENT_QUOTES, 'UTF-8');

    // Retourner les données en format JSON
    echo json_encode($article);

} catch (PDOException $e) {
    // Gestion des erreurs SQL
    http_response_code(500); // Code de réponse 500 : Erreur interne du serveur
    echo json_encode([
        'error' => 'Erreur lors de la récupération de l\'article.',
        'details' => 'Une erreur interne s\'est produite. Veuillez réessayer plus tard.'
    ]);

    // Vous pouvez activer le mode débogage en développement pour voir les détails de l'erreur :
    // 'details' => $e->getMessage()
}
?>
