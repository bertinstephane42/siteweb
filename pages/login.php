<?php
// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Connexion à la base de données
    require_once '../includes/db_connection.php';
    // Récupérer les données du formulaire de manière sécurisée
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    // Vérifier si les champs sont vides
    if (empty($username) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        try {
            // Préparer la requête pour vérifier l'existence de l'utilisateur
            $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch();
            if ($user && password_verify($password, $user['password'])) {
                // Authentification réussie
                // Démarrer la session pour permettre la gestion des connexions utilisateur
                session_start();
                // Sécurisation des sessions en régénérant l'ID de session pour éviter les attaques de fixation
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                // Rediriger l'utilisateur vers le tableau de bord
                header("Location: dashboard.php");
                exit();
            } else {
                // Mauvais identifiants
                header("Location: login.html?error=true");
                exit();
            }
        } catch (PDOException $e) {
            $error = "Erreur lors de la connexion : " . $e->getMessage();
        }
    }
}
?>
