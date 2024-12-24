<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-nous</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<?php include '../includes/header.php'; ?>
<body>
    <div class="contact-container">
        <h2>Contactez-nous</h2>

        <p>Si vous avez des questions, des suggestions ou des demandes, n'hésitez pas à nous contacter en remplissant le formulaire ci-dessous.</p>

        <!-- Formulaire de contact -->
        <form action="send_contact.php" method="POST">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
            <label for="message">Message :</label>
            <textarea id="message" name="message" rows="5" required></textarea>
            <button type="submit">Envoyer le message</button>
        </form>

        <h3>Autres moyens de contact :</h3>
        <p><strong>Email :</strong> contact@microappsolutions.com</p>
        <p><strong>Téléphone :</strong> +33 1 23 45 67 89</p>
         <p><a href="../index.php" class="button">Retour à la page d'accueil</a></p>
    </div>
</body>
</html>
<?php include '../includes/footer.php'; ?>
