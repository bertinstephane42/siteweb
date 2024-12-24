<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Vérification des données
    if (empty($name) || empty($email) || empty($message)) {
        echo "Tous les champs doivent être remplis.";
        exit;
    }

    // Destinataire du message (votre email)
    $to = "contact@microappsolutions.com";

    // Sujet du mail
    $subject = "Message de contact de $name";

    // Corps du message
    $body = "Nom: $name\nEmail: $email\n\nMessage:\n$message";

    // En-têtes pour l'email
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Envoi de l'email
    if (mail($to, $subject, $body, $headers)) {
        echo "Votre message a été envoyé avec succès !";
    } else {
        echo "Erreur lors de l'envoi du message. Veuillez réessayer.";
    }
} else {
    echo "Veuillez soumettre le formulaire.";
}
?>
