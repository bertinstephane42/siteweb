document.addEventListener('DOMContentLoaded', () => {
    // Gestion des boutons "Afficher plus"
    document.querySelectorAll('.btn-details').forEach((button, index) => {
        button.addEventListener('click', () => {
            alert(`Détails de l'article ${index + 1}`);
        });
    });

    // Chargement dynamique des articles au clic sur un lien
    document.querySelectorAll('.article-link').forEach(link => {
        link.addEventListener('click', event => {
            event.preventDefault();

            const articleId = link.dataset.id;

            if (!articleId) {
                console.error("Aucun ID trouvé pour ce lien :", link);
                alert("Impossible de charger l'article : identifiant manquant.");
                return;
            }

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

                    const detailDiv = document.querySelector('#article-detail');
                    if (!detailDiv) {
                        console.error("L'élément #article-detail est introuvable.");
                        return;
                    }

                    detailDiv.innerHTML = `
                        <h2>${data.title}</h2>
                        <p>${data.content}</p>
                    `;
                })
                .catch(error => {
                    console.error("Erreur :", error);
                    alert("Une erreur s'est produite lors du chargement de l'article.");
                });
        });
    });
});
