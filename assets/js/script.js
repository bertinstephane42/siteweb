/**
 * script.js
 * 
 * Ce fichier permet de gérer l'interactivité sur les articles :
 * 1. Afficher un message de détails pour chaque bouton "Afficher plus".
 * 2. Charger dynamiquement le contenu d'un article lorsque l'utilisateur clique sur un lien.
 * 
 * Objectif pédagogique : comprendre les notions de DOM, d'écouteurs d'événements,
 * d'attributs data-* et d'AJAX avec fetch.
 */

// Attendre que le DOM soit entièrement chargé avant d'exécuter le script
document.addEventListener('DOMContentLoaded', () => {

    // =====================================================
    // 1. Gestion des boutons "Afficher plus"
    // =====================================================
    // Sélectionner tous les boutons ayant la classe '.btn-details'
    document.querySelectorAll('.btn-details').forEach((button, index) => {
        // Ajouter un écouteur d'événement "click" pour chaque bouton
        button.addEventListener('click', () => {
            // Affiche une alerte avec l'index de l'article
            // (index + 1 pour que l'humain lise 1, 2, 3… au lieu de 0,1,2)
            alert(`Détails de l'article ${index + 1}`);
        });
    });

    // =====================================================
    // 2. Chargement dynamique des articles au clic sur un lien
    // =====================================================
    // Sélectionner tous les liens d'article avec la classe '.article-link'
    document.querySelectorAll('.article-link').forEach(link => {

        // Ajouter un écouteur d'événement "click" sur chaque lien
        link.addEventListener('click', event => {
            // Empêche le comportement par défaut du lien (<a href="#">),
            // qui serait de naviguer vers le haut de la page ou vers "#"
            event.preventDefault();

            /**
             * L'attribut data-* en HTML permet de stocker des informations personnalisées
             * directement dans les éléments HTML. Exemple :
             * <a href="#" class="article-link" data-id="42">Titre</a>
             * 
             * En JS, on peut accéder à ces attributs via "dataset".
             * Ici, data-id devient link.dataset.id
             * C'est ce qui permet de récupérer l'identifiant de l'article cliqué.
             */
            const articleId = link.dataset.id; // <-- Très important !

            // Vérification que l'ID est bien présent
            if (!articleId) {
                console.error("Aucun ID trouvé pour ce lien :", link);
                alert("Impossible de charger l'article : identifiant manquant.");
                return; // On sort du script si l'ID est manquant
            }

            // =====================
            // Requête AJAX vers l'API
            // =====================
            // Utilisation de fetch pour récupérer les détails de l'article sans recharger la page
            fetch(`/api/get_article.php?id=${encodeURIComponent(articleId)}`)
                .then(response => {
                    // Vérifier que la réponse HTTP est OK (code 200)
                    if (!response.ok) {
                        throw new Error("Erreur lors de la récupération de l'article.");
                    }
                    // Convertir la réponse JSON en objet JS
                    return response.json();
                })
                .then(data => {
                    // Vérifier que les données reçues contiennent bien un titre et un contenu
                    if (!data || !data.title || !data.content) {
                        throw new Error("Données invalides reçues de l'API.");
                    }

                    // Sélectionner la div où l'on souhaite afficher les détails
                    const detailDiv = document.querySelector('#article-detail');
                    if (!detailDiv) {
                        console.error("L'élément #article-detail est introuvable.");
                        return;
                    }

                    // Insérer dynamiquement le contenu de l'article dans la page
                    detailDiv.innerHTML = `
                        <h2>${data.title}</h2>
                        <p>${data.content}</p>
                    `;
                })
                .catch(error => {
                    // Gestion des erreurs éventuelles : réseau, API, JSON invalide
                    console.error("Erreur :", error);
                    alert("Une erreur s'est produite lors du chargement de l'article.");
                });
        });
    });

});
