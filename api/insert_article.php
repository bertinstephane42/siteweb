<?php
// Définir le Content-Type pour la réponse JSON
header('Content-Type: application/json');

// Inclure la connexion PDO
require_once '../includes/db_connection.php'; // Utilisation de `require_once` pour éviter plusieurs inclusions

// Récupérer les données envoyées via POST
$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');

// Vérification des entrées utilisateur
if (empty($title) || empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Les champs titre et contenu sont obligatoires.']);
    exit;
}

// Limiter la longueur du titre pour éviter les débordements
if (strlen($title) > 255) {
    echo json_encode(['success' => false, 'message' => 'Le titre ne peut pas dépasser 255 caractères.']);
    exit;
}

try {
    // Préparer et exécuter la requête d'insertion dans la base de données
    $stmt = $pdo->prepare("
        INSERT INTO articles (title, content, created_at)
        VALUES (:title, :content, NOW())
    ");
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':content', $content, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Article ajouté avec succès.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Une erreur est survenue lors de l\'ajout de l\'article.']);
    }
} catch (PDOException $e) {
    // Enregistrer l'erreur dans les logs et éviter de l'exposer à l'utilisateur
    error_log('Erreur PDO lors de l\'insertion de l\'article : ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Une erreur interne est survenue.']);
}
