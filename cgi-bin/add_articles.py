#!/usr/bin/env python3

import cgi
import cgitb
import requests

# Activer le rapport des erreurs CGI pour le débogage
cgitb.enable()

# Récupération des données du formulaire
form = cgi.FieldStorage()
title = form.getvalue('title', '').strip()
content = form.getvalue('content', '').strip()

# URL de l'API PHP pour insérer l'article
api_url = 'https://www.microappsolutions.com/api/insert_article.php'

# Afficher l'en-tête HTTP
print("Content-Type: text/html\n")
print("<html><head><title>Ajouter un article</title></head><body>")

# Validation des champs du formulaire
if not title or not content:
    print("<p>Erreur : Tous les champs sont obligatoires.</p>")
else:
    # Envoi de la requête POST à l'API PHP
    # L'option 'verify=false'désactive la vérification du certificat SSL pour cette requête particulière. Ne doit pas être utilisé en production
    response = requests.post(api_url, data={'title': title, 'content': content}, verify=False)
    response_data = response.json()

    if response_data.get('success'):
        print("<p>L'article a été ajouté avec succès.</p>")
    else:
        print(f"<p>Erreur : {response_data.get('message')}</p>")

print("<a href='/index.php'>Retour à l'accueil</a>")

print("</body></html>")
