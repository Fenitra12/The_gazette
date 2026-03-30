# Documentation Front Office - TheGazette

## Table des matieres

1. [Architecture generale](#1-architecture-generale)
2. [URL Rewriting (.htaccess)](#2-url-rewriting-htaccess)
3. [Gestion du cache](#3-gestion-du-cache)
4. [Optimisation des images](#4-optimisation-des-images)
5. [Referencement SEO](#5-referencement-seo)
6. [robots.txt](#6-robotstxt)
7. [SitemapController (sitemap.xml)](#7-sitemapcontroller-sitemapxml)
8. [Schema.org (donnees structurees)](#8-schemaorg-donnees-structurees)
9. [Compression Gzip](#9-compression-gzip)
10. [Resume des optimisations](#10-resume-des-optimisations)

---

## 1. Architecture generale

Le front office fonctionne avec une architecture **MVC** (Model-View-Controller) custom deployee dans Docker :

```
Navigateur
   |
   v
Nginx (port 8080)          <-- Serveur web / reverse proxy
   |
   v
PHP-FPM (8.4)              <-- Interprete PHP
   |
   v
PostgreSQL 17              <-- Base de donnees
```

**Structure des fichiers :**

```
frontend/
  public/
    index.php               # Point d'entree unique (front controller)
    .htaccess               # Regles de reecriture d'URL (Apache)
    resize.php              # Endpoint de redimensionnement d'images
    robots.txt              # Fichier robots pour les moteurs de recherche
  Controllers/
    BaseController.php      # Controleur abstrait (SEO, cache, render)
    HomeController.php      # Page d'accueil
    ArticleController.php   # Page article (+ Schema.org NewsArticle)
    CategoryController.php  # Page categorie (+ Schema.org BreadcrumbList)
    PageController.php      # Pages statiques (about, contact)
    SitemapController.php   # Generation dynamique du sitemap.xml
  Core/
    Database.php            # Connexion PDO PostgreSQL (singleton)
    ImageHelper.php         # Helper de generation de balises <img> optimisees
  Models/
    BaseModel.php           # Modele abstrait
    ArticleModel.php        # Requetes articles
    CategoryModel.php       # Requetes categories
    AuthorModel.php         # Requetes auteurs
  Views/
    layout.php              # Template HTML principal (head, header, footer)
    home/index.php          # Vue page d'accueil
    article/show.php        # Vue article
    category/index.php      # Vue categorie
    page/about.php          # Vue page About
    page/contact.php        # Vue page Contact
    css/                    # Feuilles de style
    js/                     # Scripts JavaScript
    img/                    # Images sources
    img/cache/              # Images redimensionnees (generees automatiquement)
    fonts/                  # Polices
  config/
    database.php            # Configuration base de donnees
```

### Fonctionnement du front controller

Toutes les requetes HTTP passent par `frontend/public/index.php`. Ce fichier :

1. **Enregistre un autoloader PSR-4** qui mappe le namespace `App\` vers le dossier `frontend/`
2. **Declare deux fonctions helpers** globales (`resized()` et `img()`) pour les images
3. **Initialise la connexion a la base de donnees** via la configuration
4. **Cree un Router** et enregistre les routes avec leurs patterns d'URL
5. **Dispatche la requete** : le Router compare l'URI de la requete aux patterns et appelle le bon controleur

---

## 2. URL Rewriting (.htaccess)

### Principe

L'URL Rewriting permet de **masquer la technologie** utilisee et de presenter des URLs propres aux utilisateurs et aux moteurs de recherche. C'est realise par le fichier `.htaccess` place dans `frontend/public/`.

Les objectifs du rewriting (cf. cours) :
- **Masquer la technologie** : on ne voit pas que le site est en PHP, ni qu'il y a un `index.php`
- **Ameliorer le referencement** : les mots-cles dans l'URL sont pris en compte par Google
- **Simplifier la navigation** : les URLs sont courtes et memorisables

### Le fichier .htaccess (`frontend/public/.htaccess`)

```apache
# Activer le suivi des liens symboliques + désactiver le listage des dossiers
Options +FollowSymlinks -Indexes

# Activer le moteur de réécriture d'URL
RewriteEngine On
RewriteBase /

# -----------------------------------------------------------------------
# Sécurité : bloquer l'accès direct aux dossiers de l'application
# -----------------------------------------------------------------------
RewriteRule ^(config|Core|Controllers|Models|Views)(/|$) - [F,L]

# Règle 1 : Page d'accueil
RewriteRule ^$ index.php [L]

# Règle 2 : Article — /article/{slug}
RewriteRule ^article/([a-zA-Z0-9_-]+)/?$ index.php [QSA,L]

# Règle 3 : Catégorie avec pagination — /categorie/{slug}/{page}
RewriteRule ^categorie/([a-zA-Z0-9_-]+)/([0-9]+)/?$ index.php [QSA,L]

# Règle 4 : Catégorie — /categorie/{slug}
RewriteRule ^categorie/([a-zA-Z0-9_-]+)/?$ index.php [QSA,L]

# Règle 5 : Page À propos
RewriteRule ^about/?$ index.php [L]

# Règle 6 : Page Contact
RewriteRule ^contact/?$ index.php [L]

# Règle 7 : Sitemap XML
RewriteRule ^sitemap\.xml$ index.php [L]

# -----------------------------------------------------------------------
# Fallback : ne pas réécrire si le fichier ou dossier existe (assets statiques)
# -----------------------------------------------------------------------
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

### Explication ligne par ligne

#### Activation du moteur

- **`Options +FollowSymlinks`** : Apache doit suivre les liens symboliques. Prerequis obligatoire pour `mod_rewrite`.
- **`-Indexes`** : Desactive le listage du contenu des dossiers. Sans cette option, un visiteur qui accede a `/css/` verrait la liste de tous les fichiers CSS.
- **`RewriteEngine On`** : Active le moteur de reecriture. Sans cette ligne, aucune `RewriteRule` n'est evaluee.
- **`RewriteBase /`** : Definit le chemin de base pour les rewrites. Evite des problemes de chemin si le site est deploye dans un sous-dossier.

#### Regle de securite

```apache
RewriteRule ^(config|Core|Controllers|Models|Views)(/|$) - [F,L]
```

| Partie | Signification |
|--------|---------------|
| `^(config\|Core\|Controllers\|Models\|Views)` | Correspond aux dossiers sensibles de l'application |
| `-` | Ne pas réécrire l'URL (la garder telle quelle) |
| `[F]` | Retourner une erreur **403 Forbidden** |
| `[L]` | Arreter l'evaluation des regles suivantes |

Protege les fichiers PHP internes (logique metier, config DB, modeles) contre tout acces direct via le navigateur.

#### Regles de routage (Regles 1 a 7)

Chaque route est definie explicitement avec un pattern precis :

| Regle | Pattern | Exemple |
|-------|---------|---------|
| 1 | `^$` | `/` (accueil exact) |
| 2 | `^article/([a-zA-Z0-9_-]+)/?$` | `/article/iran-nuclear-deal` |
| 3 | `^categorie/([a-zA-Z0-9_-]+)/([0-9]+)/?$` | `/categorie/diplomacy/2` |
| 4 | `^categorie/([a-zA-Z0-9_-]+)/?$` | `/categorie/sport` |
| 5 | `^about/?$` | `/about` |
| 6 | `^contact/?$` | `/contact` |
| 7 | `^sitemap\.xml$` | `/sitemap.xml` |

Points importants :
- `([a-zA-Z0-9_-]+)` capture un slug (lettres, chiffres, tirets, underscores)
- `([0-9]+)` capture uniquement des chiffres (numero de page)
- `/?` rend le slash final optionnel
- `\.` echappe le point dans `sitemap.xml` (sans l'echappement, `.` matcherait n'importe quel caractere)
- La regle 3 (avec pagination) est placee **avant** la regle 4 pour eviter qu'un numero de page soit interprete comme un slug

#### Fallback (assets statiques)

```apache
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

Si aucune des regles 1-7 ne matche, ce fallback prend le relais mais seulement si la requete ne correspond pas a un fichier (`!-f`) ou un dossier (`!-d`) existant. Cela protege les assets statiques (CSS, JS, images, fonts) qui sont servis directement par Apache.

**Resultat** : Toutes les requetes arrivent a `index.php`, qui lit l'URI originale via `$_SERVER['REQUEST_URI']`. La technologie PHP reste totalement invisible pour l'utilisateur.

### Comment PHP dispatche ensuite (`index.php`)

Une fois en `index.php`, un **Router** analyse l'URI et appelle le bon controleur :

```php
$router = new \App\Core\Router();
$router->add('GET', '/',                        'HomeController@index');
$router->add('GET', '/article/{slug}',          'ArticleController@show');
$router->add('GET', '/categorie/{slug}',        'CategoryController@show');
$router->add('GET', '/categorie/{slug}/{page}', 'CategoryController@show');
$router->add('GET', '/about',                   'PageController@about');
$router->add('GET', '/contact',                 'PageController@contact');
$router->add('GET', '/sitemap.xml',             'SitemapController@index');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($_SERVER['REQUEST_METHOD'], $uri);
```

Le `Router` convertit les patterns `{slug}` en groupes de capture nommees regex :
- `/article/{slug}` devient `#^/article/(?P<slug>[a-zA-Z0-9_-]+)$#`

Il matche l'URI, extrait les parametres, et appelle le controleur correspondant.

#### Tableau des routes

| URL vue par l'utilisateur | Controleur appele |
|---|---|
| `/` | `HomeController::index()` |
| `/article/iran-nuclear-deal` | `ArticleController::show(['slug' => 'iran-nuclear-deal'])` |
| `/categorie/diplomacy` | `CategoryController::show(['slug' => 'diplomacy'])` |
| `/categorie/diplomacy/2` | `CategoryController::show(['slug' => 'diplomacy', 'page' => '2'])` |
| `/about` | `PageController::about()` |
| `/contact` | `PageController::contact()` |
| `/sitemap.xml` | `SitemapController::index()` |

### Equivalent Nginx (`default.conf`)

Le projet tourne dans Docker avec Nginx. Nginx ne supporte pas les `.htaccess` (mecanisme propre a Apache), mais il offre un equivalent avec `try_files` :

```nginx
# Front controller - toutes les requetes vers index.php
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

`try_files` fonctionne de la meme maniere que les `RewriteCond` et `RewriteRule` combines :
1. Cherche si l'URI correspond a un fichier existant (`$uri`)
2. Cherche si l'URI correspond a un dossier existant (`$uri/`)
3. Sinon, redirige vers `index.php` en conservant les parametres GET (`$query_string`)

### Cheminement complet d'une requete

```
Requete : GET /article/iran-nuclear-deal

1. Nginx recoit la requete sur le port 8080
2. try_files : le fichier n'existe pas sur le disque
3. Nginx transmet a index.php via PHP-FPM
4. $_SERVER['REQUEST_URI'] = '/article/iran-nuclear-deal'
5. Le Router matche le pattern /article/{slug}
6. Appel de ArticleController::show(['slug' => 'iran-nuclear-deal'])
7. Le controleur interroge la BDD, prepare les donnees, rend la vue
8. La reponse HTML est renvoyee au navigateur
```

---

## 3. Gestion du cache

Le cache est mis en place a **trois niveaux** differents, chacun ayant un role precis.

### Niveau 1 : Cache des pages dynamiques (PHP)

**Fichier :** `BaseController.php`

```php
header('Cache-Control: public, max-age=60, must-revalidate');
header('Vary: Accept-Encoding');
```

- **`Cache-Control: public, max-age=60`** : Le navigateur et les proxies intermediaires peuvent mettre en cache la page HTML pendant **60 secondes**. Pendant cette periode, le navigateur ne refait pas de requete au serveur.
- **`must-revalidate`** : Apres les 60 secondes, le navigateur doit redemander au serveur pour verifier si le contenu a change.
- **`Vary: Accept-Encoding`** : Indique que le contenu peut varier selon le type de compression (gzip ou non). Cela empeche un proxy de servir une version gzippee a un navigateur qui ne supporte pas gzip.

**Pourquoi 60 secondes ?** C'est un compromis : les visiteurs voient les nouvelles pages rapidement, mais on evite que chaque clic recharge toute la page depuis le serveur.

### Niveau 2 : Cache des assets statiques (Nginx)

**Fichier :** `default.conf`

```nginx
location ~ ^/(css|js|img|fonts)/ {
    root /var/www/html/frontend/Views;
    expires 7d;
    access_log off;
}
```

Les fichiers CSS, JavaScript, images et polices sont mis en cache par le navigateur pendant **7 jours**. Ces fichiers ne changent quasiment jamais, donc le navigateur les telecharge une seule fois et les reutilise sans refaire de requete au serveur.

`access_log off` desactive les logs pour ces fichiers afin d'economiser les ecritures disque.

### Niveau 3 : Cache des images redimensionnees (Nginx + PHP)

**Fichiers :** `default.conf` + `resize.php`

```nginx
location ~ ^/img/cache/ {
    root /var/www/html/frontend/Views;
    expires 30d;
    add_header Cache-Control "public, immutable";
    try_files $uri @resize;
}
```

Les images redimensionnees sont mises en cache **30 jours** avec le flag `immutable`. Cela signifie que le navigateur ne verifie meme pas si l'image a change : il fait confiance a sa copie locale tant que le delai n'est pas expire.

Le `try_files $uri @resize` fait la transition avec le systeme de redimensionnement (explique dans la section suivante).

### Tableau recapitulatif des durees de cache

| Ressource | Duree | Header |
|-----------|-------|--------|
| Pages HTML dynamiques | 60 secondes | `max-age=60, must-revalidate` |
| CSS, JS, fonts, images sources | 7 jours | `expires 7d` |
| Images redimensionnees (cache) | 30 jours | `max-age=2592000, immutable` |
| sitemap.xml | 1 heure | `max-age=3600` |

---

## 4. Optimisation des images

### Le probleme

Les images originales du site sont souvent tres lourdes (ex: 2 Mo pour une photo en 1920x1080). Afficher ces images directement ralentit considerable le chargement des pages, surtout sur mobile.

### La solution : redimensionnement a la demande

Le systeme fonctionne avec 3 composants qui cooperent :

#### 4.1. ImageHelper (`Core/ImageHelper.php`)

Ce helper genere les URLs et les balises `<img>` optimisees.

**`ImageHelper::url($src, $width, $height)`** : Genere l'URL de l'image redimensionnee.
```
Entree : url('blog-img/1.jpg', 350, 0)
Sortie : /img/cache/350x0/blog-img/1.jpg
```

**`ImageHelper::tag($src, $alt, $width, $height, $lazy)`** : Genere une balise `<img>` complete.
```html
<img src="/img/cache/350x0/blog-img/1.jpg" loading="lazy" alt="Article title" width="350">
```

La balise generee inclut :
- **`loading="lazy"`** : Le navigateur ne charge l'image que lorsqu'elle est proche de la zone visible (lazy loading natif). Cela evite de telecharger toutes les images d'un coup au chargement de la page.
- **`alt="..."`** : Le texte alternatif obligatoire pour le SEO et l'accessibilite.
- **`width="..."` et `height="..."`** : Ces attributs permettent au navigateur de reserver l'espace avant le chargement de l'image, ce qui evite le "saut de page" (CLS - Cumulative Layout Shift).

#### 4.2. Nginx - le systeme de cache disque

Quand le navigateur demande `/img/cache/350x0/blog-img/1.jpg` :

```
1. Nginx regarde si le fichier existe deja sur le disque
   (dans frontend/Views/img/cache/350x0/blog-img/1.jpg)

2. SI OUI -> Nginx sert directement le fichier (pas de PHP, ultra rapide)
   SI NON -> Nginx redirige vers @resize (resize.php)
```

C'est la directive `try_files $uri @resize` qui gere cette logique.

#### 4.3. resize.php - le redimensionnement

`resize.php` est appele uniquement la premiere fois qu'une taille d'image est demandee. Il :

1. **Parse l'URL** pour extraire la largeur, la hauteur, le sous-dossier et le nom de fichier
2. **Valide les parametres** : dimensions max de 2000x2000, pas d'upscaling, extensions autorisees uniquement (jpg, png, gif), sous-dossiers autorises uniquement (bg-img, blog-img, core-img)
3. **Charge l'image source** avec la librairie GD
4. **Redimensionne** l'image (proportionnellement si hauteur = 0)
5. **Sauvegarde sur le disque** dans le dossier cache (JPEG qualite 80, PNG compression 8)
6. **Sert le fichier** avec les headers de cache (30 jours, immutable)

A la prochaine requete pour la meme image/taille, Nginx servira directement le fichier depuis le disque sans appeler PHP.

#### Fonctions helpers globales

Deux fonctions globales sont definies dans `index.php` pour simplifier l'utilisation dans les vues :

```php
// Retourne juste l'URL (utile pour background-image en CSS)
resized('bg-img/4.jpg', 1200, 600)
// -> /img/cache/1200x600/bg-img/4.jpg

// Retourne une balise <img> complete
img('blog-img/1.jpg', 'Alt text', 350)
// -> <img src="/img/cache/350x0/blog-img/1.jpg" loading="lazy" alt="Alt text" width="350">
```

---

## 5. Referencement SEO

### 5.1. Balises meta dynamiques

**Fichier :** `layout.php` (head) + chaque controleur

Chaque page a ses propres balises `<title>` et `<meta description>` :

| Page | Title | Description |
|------|-------|-------------|
| Accueil | `TheGazette - Iran Conflict News \| Accueil` | `Latest news and analysis on the Iran conflict...` |
| Article | `{titre article} \| TheGazette` | `{meta_description ou excerpt de l'article}` |
| Categorie | `{nom categorie} \| TheGazette` | `Articles about {nom categorie} - Iran conflict...` |
| About | `About Us \| TheGazette` | `Learn about TheGazette team covering...` |
| Contact | `Contact \| TheGazette` | `Contact TheGazette for news tips...` |

Le `BaseController.php` definit des valeurs par defaut, et chaque controleur specifique les surcharge via le tableau `$data`.

### 5.2. Balise `<meta name="robots">`

```html
<meta name="robots" content="index, follow">
```

Cette balise indique aux moteurs de recherche :
- **`index`** : Vous pouvez indexer cette page (l'ajouter a vos resultats de recherche)
- **`follow`** : Vous pouvez suivre les liens presents sur cette page pour decouvrir d'autres pages

Elle est presente sur toutes les pages via `layout.php`.

### 5.3. Balise `<link rel="canonical">`

```html
<link rel="canonical" href="http://localhost:8080/article/iran-nuclear-deal">
```

L'URL canonique indique a Google **quelle est la "vraie" URL** de cette page. C'est utile quand une meme page est accessible par plusieurs URLs (avec ou sans parametres GET, avec ou sans trailing slash, pages paginees...).

Elle est generee dynamiquement dans `BaseController.php` :
```php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$data['canonicalUrl'] = $protocol . '://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?');
```

`strtok($_SERVER['REQUEST_URI'], '?')` retire les parametres GET de l'URL pour obtenir une URL propre.

### 5.4. Structure des titres (h1 -> h6)

Chaque page a un `<h1>` unique qui represente le sujet principal :

| Page | Contenu du `<h1>` |
|------|-------------------|
| Accueil | Titre de l'article vedette (le plus populaire) |
| Article | Titre de l'article |
| Categorie | Nom de la categorie |
| About | "About Us" |
| Contact | "Contact" |

Puis les sous-titres suivent la hierarchie `<h2>`, `<h3>`, etc. sans sauter de niveaux.

### 5.5. Attribut `alt` sur les images

Toutes les images generees par `ImageHelper::tag()` ont un attribut `alt` obligatoire. Le texte est generalement le titre de l'article, ce qui aide Google a comprendre le contenu de l'image.

### 5.6. URLs propres et lisibles

Grace au `.htaccess` (front controller) et au Router PHP, les URLs sont propres et semantiques :
- `/article/iran-nuclear-deal` plutot que `index.php?page=article&slug=iran-nuclear-deal`
- `/categorie/diplomacy` plutot que `index.php?page=categorie&slug=diplomacy`

Cela ameliore le SEO car les moteurs de recherche donnent du poids aux mots-cles presents dans l'URL, et on ne voit jamais qu'il y a du PHP derriere.

---

## 6. robots.txt

### Qu'est-ce que c'est ?

Le fichier `robots.txt` est un fichier texte place a la racine d'un site web. C'est la **premiere chose** qu'un moteur de recherche (Google, Bing, etc.) lit quand il visite un site. Il lui donne des instructions sur ce qu'il a le droit de visiter ou non.

### Notre fichier (`frontend/public/robots.txt`)

```
User-agent: *
Allow: /

Sitemap: /sitemap.xml
```

Explication ligne par ligne :

1. **`User-agent: *`** : Cette regle s'applique a **tous** les robots (Googlebot, Bingbot, etc.). Le `*` est un joker qui signifie "tout le monde".

2. **`Allow: /`** : Les robots ont le droit de visiter **toutes les pages** du site. Le `/` represente la racine, donc tout ce qui est en dessous.

3. **`Sitemap: /sitemap.xml`** : Indique aux robots ou trouver le sitemap (la carte du site). C'est comme dire "voici le plan du site, utilisez-le pour decouvrir toutes les pages".

### Comment Nginx le sert

```nginx
location = /robots.txt {
    root /var/www/html/frontend/public;
    access_log off;
}
```

Le `location =` signifie une correspondance **exacte** : seule l'URL `/robots.txt` est concernee. Nginx sert directement le fichier sans passer par PHP, ce qui est instantane. Les logs sont desactives car les robots le consultent tres frequemment.

---

## 7. SitemapController (sitemap.xml)

### Qu'est-ce qu'un sitemap ?

Un sitemap est un fichier XML qui **liste toutes les URLs du site** avec des informations supplementaires (date de modification, frequence de mise a jour, priorite). C'est comme donner un plan detaille de votre site aux moteurs de recherche pour qu'ils n'oublient aucune page.

### Pourquoi le generer dynamiquement ?

Les articles sont stockes en base de donnees et changent regulierement (nouveaux articles, modifications). Un fichier statique ne pourrait pas refleter ces changements. Le `SitemapController` genere le XML a chaque requete en interrogeant la base de donnees.

### Comment ca fonctionne (`Controllers/SitemapController.php`)

```
Requete : GET /sitemap.xml

1. Nginx transmet a index.php (via try_files)
2. Le Router matche la route '/sitemap.xml' -> SitemapController@index
3. Le controleur interroge la BDD
4. Il genere le XML et le renvoie
```

Le controleur fait ceci :

#### Etape 1 : Recuperer les donnees
```php
$articles = $articleModel->getLatest(1000);   // Tous les articles (max 1000)
$categories = $categoryModel->getAll();        // Toutes les categories
```

#### Etape 2 : Definir les bons headers HTTP
```php
header('Content-Type: application/xml; charset=UTF-8');  // C'est du XML, pas du HTML
header('Cache-Control: public, max-age=3600');           // Cache d'1 heure
```

Le cache d'1 heure evite de regenerer le sitemap a chaque visite du robot.

#### Etape 3 : Generer le XML

Le sitemap suit le protocole standard `sitemaps.org`. Chaque URL est declaree dans une balise `<url>` avec :

```xml
<url>
  <loc>http://example.com/article/iran-nuclear-deal</loc>   <!-- URL de la page -->
  <lastmod>2026-03-15</lastmod>                              <!-- Derniere modification -->
  <changefreq>weekly</changefreq>                            <!-- Frequence de changement -->
  <priority>0.7</priority>                                   <!-- Importance relative (0.0 a 1.0) -->
</url>
```

Les URLs du sitemap utilisent les URLs propres (sans parametres GET) car ce sont celles que Google doit indexer.

#### Priorites attribuees

| Type de page | Priorite | Frequence de changement |
|--------------|----------|------------------------|
| Page d'accueil | 1.0 (maximum) | `daily` |
| Categories | 0.8 | `daily` |
| Articles | 0.7 | `weekly` |
| Pages statiques (about, contact) | 0.5 | `monthly` |

La **priorite** est relative au sein du site : elle indique a Google quelles pages sont les plus importantes. La page d'accueil a la priorite maximum car c'est le point d'entree principal.

La **frequence de changement** (`changefreq`) est une indication pour les moteurs de recherche. `daily` signifie "cette page change souvent, revenez la verifier chaque jour".

### Pourquoi le SitemapController n'etend pas BaseController

Le `SitemapController` n'herite pas de `BaseController` car il ne genere pas de HTML. Il produit du XML pur, n'a pas besoin du systeme de templates (layout.php), et n'a pas besoin des meta SEO ni du cache de 60 secondes des pages HTML.

---

## 8. Schema.org (donnees structurees)

### Qu'est-ce que c'est ?

Schema.org est un vocabulaire standard (cree par Google, Bing, Yahoo et Yandex) pour decrire le contenu d'une page de maniere structuree. Cela permet aux moteurs de recherche d'afficher des **rich snippets** (resultats enrichis) dans leurs pages de resultats.

### Implementation

Les donnees Schema.org sont injectees dans le `<head>` via des balises `<script type="application/ld+json">` (format JSON-LD).

#### Schema global : WebSite (toutes les pages)

Present dans `layout.php` sur chaque page :

```json
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "TheGazette",
    "url": "http://localhost:8080/"
}
```

Cela declare aux moteurs de recherche : "Ce site s'appelle TheGazette et est accessible a cette URL".

#### Schema article : NewsArticle (pages article)

Genere dans `ArticleController.php` :

```json
{
    "@context": "https://schema.org",
    "@type": "NewsArticle",
    "headline": "Iran Nuclear Deal: Latest Developments",
    "description": "Excerpt de l'article...",
    "datePublished": "2026-03-15T10:30:00+00:00",
    "author": { "@type": "Person", "name": "John Smith" },
    "publisher": {
        "@type": "Organization",
        "name": "TheGazette",
        "logo": { "@type": "ImageObject", "url": "http://localhost:8080/img/core-img/logo.png" }
    },
    "mainEntityOfPage": "http://localhost:8080/article/iran-nuclear-deal",
    "image": "http://localhost:8080/img/blog-img/1.jpg"
}
```

Cela permet a Google d'afficher l'article avec le nom de l'auteur, la date, et une image dans ses resultats de recherche.

#### Schema categorie : BreadcrumbList (pages categorie)

Genere dans `CategoryController.php` :

```json
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        { "@type": "ListItem", "position": 1, "name": "Home", "item": "http://localhost:8080/" },
        { "@type": "ListItem", "position": 2, "name": "Diplomacy", "item": "http://localhost:8080/categorie/diplomacy" }
    ]
}
```

Google peut afficher le fil d'Ariane directement dans les resultats de recherche : `Home > Diplomacy`.

---

## 9. Compression Gzip

### Configuration Nginx (`default.conf`)

```nginx
gzip on;
gzip_types text/css application/javascript text/javascript application/json image/svg+xml;
gzip_min_length 256;
```

- **`gzip on`** : Active la compression gzip
- **`gzip_types`** : Compresse uniquement les fichiers CSS, JavaScript, JSON et SVG. Les images JPEG/PNG sont deja compressees par nature, les compresser en gzip n'apporterait rien.
- **`gzip_min_length 256`** : Ne compresse pas les fichiers de moins de 256 octets (la compression ajouterait du overhead pour un gain negligeable)

Le header `Vary: Accept-Encoding` (envoye par `BaseController.php`) signale aux proxies de stocker separement les versions compressees et non-compressees.

---

## 10. Resume des optimisations

| Optimisation | Ou | Effet |
|---|---|---|
| **URL Rewriting (.htaccess)** | `.htaccess` + `default.conf` + Router (`index.php`) | URLs propres, technologie PHP masquee, meilleur SEO |
| **Cache pages HTML** | `BaseController.php` (60s) | Moins de requetes serveur |
| **Cache assets statiques** | Nginx (7 jours) | CSS/JS/fonts telecharges une seule fois |
| **Cache images redimensionnees** | Nginx (30 jours, immutable) | Images servies instantanement depuis le disque |
| **Redimensionnement images** | `resize.php` + `ImageHelper.php` | Images adaptees a la taille d'affichage, poids reduit |
| **Lazy loading** | `ImageHelper::tag()` (`loading="lazy"`) | Images hors ecran chargees a la demande |
| **Attributs width/height** | `ImageHelper::tag()` | Evite le CLS (saut de page au chargement) |
| **Compression Gzip** | Nginx | CSS/JS transferes compresses (reduction ~70%) |
| **`<title>` dynamique** | `layout.php` + controleurs | Titre unique par page, mot-cle present |
| **`<meta description>`** | `layout.php` + controleurs | Description unique par page |
| **`<meta robots>`** | `layout.php` | Indique aux moteurs d'indexer et suivre les liens |
| **`<link rel="canonical">`** | `layout.php` + `BaseController.php` | Evite le contenu duplique |
| **Structure h1-h6** | Chaque vue | Hierarchie semantique, un seul h1 par page |
| **Attribut alt images** | `ImageHelper::tag()` | Accessibilite + SEO images |
| **Schema.org JSON-LD** | `layout.php` + controleurs | Donnees structurees pour les rich snippets |
| **robots.txt** | `frontend/public/robots.txt` | Guide les moteurs de recherche |
| **sitemap.xml** | `SitemapController.php` | Liste toutes les URLs pour l'indexation |
