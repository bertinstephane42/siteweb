<?php
// Démarrer la session
session_start();

// Sécurisation des sessions en régénérant l'ID de session pour éviter les attaques de fixation
session_regenerate_id(true);

// Détruire toutes les variables de session
session_unset();

// Détruire la session
session_destroy();

// Rediriger l'utilisateur vers la page de connexion
header("Location: login.html");
exit();
?>
