// Gestion des boutons "Afficher plus"
document.querySelectorAll('.btn-details').forEach((button, index) => {
    // Ajout d'un gestionnaire d'événement pour chaque bouton
    button.addEventListener('click', () => {
        alert(`Détails de l'article ${index + 1}`);
    });
});

// Chargement dynamique des articles au clic sur un lien
document.querySelectorAll('.article-link').forEach(link => {
    link.addEventListener('click', event => {
        // Empêcher la navigation par défaut du lien
        event.preventDefault();

        // Récupérer l'ID de l'article depuis l'attribut data-article-id
        const articleId = link.dataset.articleId;

        // Effectuer une requête AJAX pour récupérer les détails de l'article
        fetch(`/api/get_article.php?id=${encodeURIComponent(articleId)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Erreur lors de la récupération de l'article.");
                }
                return response.json();
            })
            .then(data => {
                if (!data || !data.title || !data.content) {
                    throw new Error("Données invalides reçues de l'API.");
                }

                // Sélectionner la section où afficher les détails
                const detailDiv = document.querySelector('#article-detail');

                // Assainir les données avant de les afficher
                const title = document.createTextNode(data.title);
                const content = document.createTextNode(data.content);

                // Vider le contenu existant
                detailDiv.innerHTML = '';

                // Ajouter les nouveaux détails
                const titleElement = document.createElement('h2');
                titleElement.appendChild(title);
                const contentElement = document.createElement('p');
                contentElement.appendChild(content);

                detailDiv.appendChild(titleElement);
                detailDiv.appendChild(contentElement);
            })
            .catch(error => {
                console.error("Erreur :", error);
                alert("Une erreur s'est produite lors du chargement de l'article.");
            });
    });
});
