<?php
// Connexion sécurisée à la base de données
// Utilisation de PDO pour sécuriser les transactions SQL

$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'microapp_solutions';
$username = getenv('DB_USER') ?: 'microapp_user';
$password = getenv('DB_PASSWORD') ?: 'password123';

// Options pour la connexion PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Activer les exceptions en cas d'erreur
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Mode de récupération par défaut : tableau associatif
    PDO::ATTR_EMULATE_PREPARES   => false, // Désactiver l'émulation des requêtes préparées (plus sûr)
];

try {
    // Création de l'objet PDO pour établir la connexion
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, $options);
} catch (PDOException $e) {
    // Gestion des erreurs de connexion
    die("Erreur de connexion à la base de données : " . htmlspecialchars($e->getMessage()));
}
?>
