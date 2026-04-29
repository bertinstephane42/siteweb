<?php
// cours-reseaux_homepage_v1.php
// Page d'accueil professionnelle et animée pour cours-reseaux.fr
// Usage: déposer à la racine du site (ou dans un dossier public) et appeler depuis le serveur web.
// Cette page est conçue pour pointer vers : /bts_sio/doku.php/start

// Sécurité légère côté PHP (en-têtes recommandées) — ajuster selon votre configuration serveur/Cloudflare
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
// Content-Security-Policy minimal — adapter selon besoins (CDN autorisés pour Bootstrap et polices)
$csp = "default-src 'self' https:; "
     . "script-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com 'unsafe-inline'; "
     . "style-src 'self' https://cdn.jsdelivr.net https://fonts.googleapis.com 'unsafe-inline'; "
     . "font-src https://fonts.gstatic.com https://cdn.jsdelivr.net; "
     . "img-src 'self' data: https://www.gravatar.com https://upload.wikimedia.org;";

header("Content-Security-Policy: $csp");

// Petite logique: détecter environnement (dev/prod) via variable d'environnement
$is_prod = getenv('APP_ENV') === 'production' || !empty($_SERVER['HTTP_HOST']);
require_once __DIR__ . '/rate_limit.php';
?><!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cours-Réseaux — Formation BTS SIO • CPI • Expert Cyber</title>
  <meta name="description" content="Cours-Réseaux.fr — Ressources pédagogiques informatiques (BTS SIO, Bac+3 CPI, Bac+5 Expert Cyber). DokuWiki sécurisé, outils pédagogiques et proxys applicatifs.">
  <link rel="canonical" href="https://cours-reseaux.fr/">

  <!-- Bootswatch Spacelab via CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/spacelab/bootstrap.min.css">

  <!-- Google font fallback -->
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="index/css/index.css">
</head>
<body>
  <div class="float-bg" aria-hidden="true">
    <!-- decorative floating SVG shapes animated by JS -->
    <svg class="float-item" id="float1" width="260" height="260" viewBox="0 0 260 260" style="left:-60px; top:-40px;">
      <defs><linearGradient id="g1" x1="0" x2="1"><stop offset="0" stop-color="#4a8fe7"/><stop offset="1" stop-color="#2f6fd6"/></linearGradient></defs>
      <circle cx="130" cy="130" r="110" fill="url(#g1)"/>
    </svg>
    <svg class="float-item" id="float2" width="220" height="220" viewBox="0 0 220 220" style="right:-50px; bottom:-70px;">
      <rect x="10" y="10" width="200" height="200" rx="36" fill="#e9f2ff"/>
    </svg>
  </div>

  <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
  <div class="container d-flex align-items-center justify-content-between">
    <!-- Logo + texte regroupés dans le lien -->
    <a class="navbar-brand d-flex align-items-center gap-3">
      <span class="logo-mark position-relative">
        CR
        <div id="tux-popup"></div>
      </span>
      <div class="d-none d-md-block">
        <div style="font-weight:700">Cours-Réseaux</div>
        <small class="text-muted">BTS SIO • CPI • Expert Cyber</small>
      </div>
    </a>

    <!-- Boutons -->
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary" id="btnDoku">Accéder au DokuWiki</button>
		<button class="btn btn-outline-secondary" id="btnContact">Contact</button>
	  <button id="helpBtn" class="btn btn-outline-primary px-3 py-2 shadow-sm"
        style="border-width:2px; font-weight:600; letter-spacing:0.5px;">
	  <span style="font-family:monospace;">Aide</span>
	</button>
	  <button class="btn btn-outline-light btn-sm subtle-btn hacker-fade" id="hackerBtn">
		0xH4X0
	  </button>
    </div>
  </div>
</nav>

  <header class="hero">
    <div class="container position-relative">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <h1 class="display-6" style="font-weight:700">Apprenez l'informatique moderne — pratique, sécurisée, accessible</h1>
          <p class="lead text-muted">Ressources pédagogiques pour BTS SIO, formations CPI (Bac+3) et parcours Expert (Bac+5). DokuWiki protégé par CloudFlare & proxy applicatif, proposant des TP réalistes et sécurisés.</p>

          <div class="my-3 d-flex gap-2 flex-wrap">
            <div class="tech-chip" title="Golang">
              <div class="tech-icon">Go</div>
              <small>Golang</small>
            </div>
            <div class="tech-chip" title="PowerShell">
              <div class="tech-icon">PS</div>
              <small>PowerShell</small>
            </div>
            <div class="tech-chip" title="Web">
              <div class="tech-icon">Web</div>
              <small>HTML/JS/PHP</small>
            </div>
            <div class="tech-chip" title="GNU/Linux">
              <div class="tech-icon">Tux</div>
              <small>GNU/Linux</small>
            </div>
            <div class="tech-chip" title="Windows">
              <div class="tech-icon">Win</div>
              <small>Windows</small>
            </div>
          </div>

          <div class="mt-4 d-flex gap-3">
            <a class="btn btn-cta btn-lg" id="visitBtn">Accéder au DokuWiki</a>
			<a class="btn btn-outline-secondary btn-lg" id="btnPlan">Plan du site</a>
			<a class="btn btn-outline-secondary btn-lg" id="openSchemaBtn">Infrastructure</a>
          </div>

          <p class="mt-3 small text-muted">Site protégé par CloudFlare. Les applications pédagogiques sont servies via un proxy PHP pour isoler les environnements de TP.</p>
        </div>

        <div class="col-lg-6 position-relative">
          <div class="card feature-card p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <h5 style="font-weight:700">Parcours & compétences</h5>
                <p class="mb-2 text-muted">Des TP progressifs et contextualisés : réseaux, scripting, cybersécurité, Cloud, DevOps, gestion de projet.</p>
              </div>
              <div class="text-end">
                <span class="badge bg-primary">BTS → Bac+5</span>
              </div>
            </div>

            <ul class="list-unstyled mt-3">
              <li class="d-flex align-items-start mb-2">
                <div class="me-3">🛠️</div>
                <div>
                  <strong>Ateliers pratiques</strong><br><small class="text-muted">TP encadrés reproduisant des environnements réels.</small>
                </div>
              </li>
              <li class="d-flex align-items-start mb-2">
                <div class="me-3">🔐</div>
                <div>
                  <strong>Sécurité et isolation</strong><br><small class="text-muted">CloudFlare + proxy applicatif pour protéger les services pédagogiques.</small>
                </div>
              </li>
              <li class="d-flex align-items-start mb-2">
                <div class="me-3">📈</div>
                <div>
                  <strong>Méthodes projet</strong><br><small class="text-muted">Agile & Cycle en V pour préparer la gestion de projets et les certifications.</small>
                </div>
              </li>
            </ul>
          </div>

          <!-- small preview area for animated console / editor -->
          <div class="mt-4 p-3">
            <div id="miniIDE" class="border rounded" style="background:#0b1220;color:#dbeafe;padding:12px;font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,monospace;min-height:160px">
              <div style="opacity:.85;font-size:.85rem"></div>
              <pre id="console" style="margin:0;white-space:pre-wrap"></pre>
            </div>
          </div>

        </div>
      </div>
	  <div class="mt-3 small text-muted mysterious" id="search">
			  Survolez. Cliquez. Certains éléments réagissent comme si le site avait laissé des portes dérobées...
			  <span class="tooltip">Le développement web est un art… pour celui qui sait regarder derrière le code.</span>
			</div>

    </div>
  </header>

  <main class="container my-5">
  <!-- features -->
	<div class="row mt-5 mb-5">
	  <div class="col-md-4">
		<div class="card feature-card p-3" style="background:#f8f9fa; color:#212529; padding-bottom:20px;">
		  <h6><strong>DevOps & CI</strong></h6>
		  <p class="small text-muted" style="color:#495057;">Pipelines, conteneurs, intégration continue et pratiques d'automatisation.</p>
		</div>
	  </div>
	  <div class="col-md-4">
		<div class="card feature-card p-3" style="background:#f8f9fa; color:#212529;">
		  <h6><strong>Administration Systèmes</strong></h6>
		  <p class="small text-muted">
			  Linux, Windows Server, scripting, supervision, dépannage et gestion des utilisateurs.
			</p>

		</div>
	  </div>
	  <div class="col-md-4">
		<div class="card feature-card p-3" style="background:#f8f9fa; color:#212529;">
		  <h6><strong>Cybersécurité</strong></h6>
		  <p class="small text-muted" style="color:#495057;">Hardening, tests d'intrusion, chiffrement et gestion des incidents.</p>
		</div>
	  </div>
	</div>
    <div class="row">
      <div class="col-md-8">
        <article>
          <h3>Comment accéder au site cours-reseaux ?</h3>
          <p class="text-muted">
		  cours-reseaux.fr est un site privé dont l'accès est réservé en priorité aux étudiants du CFAI LDA.  
		  Il propose une approche pédagogique progressive : démonstrations, TP guidés, exercices liés à des scénarios métiers réalistes.  
		  Les contenus sont maintenus, sécurisés et adaptés aux cursus BTS SIO, Bac+3 CPI (CPLR) et Bac+5 Expert Cyber.
		</p>

          <section class="mt-4">
            <h5>Thématiques clés</h5>
            <div class="d-flex flex-wrap gap-2 mt-2">
              <span class="badge bg-light border" style="cursor:default;">Systèmes</span>
              <span class="badge bg-light border" style="cursor:default;">Scripting</span>
              <span id="cipherStrike" class="badge bg-light border" style="cursor:default;">
					Hacking
				</span>
              <span class="badge bg-light border" style="cursor:default;">ITIL</span>
              <span class="badge bg-light border" style="cursor:default;">DevOps</span>
              <span class="badge bg-light border" style="cursor:default;">Gestion de projet</span>
            </div>
          </section>
		  <!-- Word Cloud défilant -->
		<div id="wordCloud" class="mt-4" style="height:180px;"> <!-- hauteur augmentée -->
		  <div id="wordCloudInner"></div>
		</div>
        </article>
      </div>

      <aside class="col-md-4">
        
        <div class="card mt-3 p-3">
          <h6>Statut</h6>
          <p class="small text-muted">Proxy applicatif en place — sessions isolées pour travaux pratiques. CloudFlare filtre les attaques et sert le contenu statique.</p>
          <div class="progress" style="height:10px; cursor:pointer;">
			<div class="progress-bar" role="progressbar" style="width:72%"></div>
		  </div>
        </div>
      </aside>
    </div>
  </main>

	<!-- Modale Mitnick -->
	<div id="mitnickModal" class="mitnick-modal">
		<div class="mitnick-modal-content">
			<span class="mitnick-close" id="closeMitnick">&times;</span>
			<h2 class="mitnick-title"></h2>
			<p id="mitnick-story"></p>
		</div>
	</div>

	<!-- Modale Aide -->
	<div id="helpModal" class="hacker-modal">
		<div class="hacker-content">
			<span class="hacker-close" id="closeHelp">&times;</span>
			<h3 class="hacker-title"></h3>
			<div class="hacker-text"></div>
		</div>
	</div>
	
	<!-- Fenêtre modale -->
	<div id="hackerModal" class="hacker-modal hidden">
		<div class="hacker-demo-content">
			<div id="hackerContainer">
				<pre id="screen"></pre>
				<div id="musicText" style="position:absolute; left:50%; width:auto; height:20px; color:#0f0; font-family:monospace; overflow:hidden; transform:translateX(-50%);"></div>
				<div id="scroller"><div id="scrollDiv"></div></div>
				<pre id="skull" style="position:absolute; top:50%; right:20%; font-size:12px; line-height:12px;"></pre>
			</div>
			<button id="backButton">Retour</button>
			<button id="pauseBtn">Pause / Play</button>
			<audio id="hackerAudio"></audio>
		</div>
	</div>
	
	<!-- === Modale Schéma Infra === -->
	<div id="schemaModal" class="hacker-modal">
		<div class="hacker-content">
			<span id="closeSchemaModal" class="schema-close">&times;</span>
			<div style="display:flex; justify-content:center; margin-top:0px;">
				<img src="index/img/schema.svg" 
					 alt="Schéma de l’infrastructure pédagogique"
					 style="max-width:100%; height:auto; border-radius:6px;">
			</div>
		</div>
	</div>

  <footer class="bg-light py-4 mt-5 border-top">
    <div class="container d-flex justify-content-between align-items-center">
      <div>
        <a href="https://www.linkedin.com/in/stephane-bertin42/" target="_blank" rel="noopener noreferrer">
			<strong>Stéphane BERTIN</strong>
		</a><br>
        <small class="text-muted">© <?= date('Y') ?> — Ressources pédagogiques informatiques</small>
      </div>
      <div class="text-end">
        <small class="text-muted">Hébergement sécurisé • CloudFlare • Proxy PHP</small>
      </div>
    </div>
	<canvas id="fireworks-canvas" style="position:fixed;bottom:0;left:0;width:100%;height:100%;pointer-events:none;z-index:9999;"></canvas>
	<div id="fireworks-hint"
     style="position:fixed; bottom:40px; left:50%; transform:translateX(-50%);
            color:white; font-size:18px; font-weight:600; 
            text-shadow:0 0 8px black; opacity:0; transition:opacity .4s;
            pointer-events:none; z-index:10000;">
    Cliquez pour activer le son des explosions
</div>
  </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/index/js/index.js"></script>
</body>
</html>
