# Fashion Family

> Marketplace de mode et articles d'occasion — achetez, vendez, échangez.

Fashion Family est une plateforme e-commerce complète développée en PHP, permettant aux utilisateurs de mettre en vente et d'acheter des articles de mode et d'autres produits d'occasion. Le projet repose sur une architecture MVC maison, sans dépendance à un framework externe.

---

## Fonctionnalités

| Domaine               | Détails                                                 |
| --------------------- | ------------------------------------------------------- |
| **Authentification**  | Inscription, connexion, déconnexion, gestion de profil  |
| **Catalogue**         | Parcourir par catégorie, recherche, tri par prix / date |
| **Vente**             | Mise en ligne d'articles avec photo, condition, prix    |
| **Panier**            | Ajout, modification, suppression, passage de commande   |
| **Commandes**         | Historique, suivi de statut (pending → delivered)       |
| **Messagerie**        | Conversations privées entre acheteur et vendeur         |
| **Dashboard vendeur** | Gestion des annonces personnelles                       |
| **Panel admin**       | CRUD utilisateurs, articles et commandes + statistiques |

---

## Stack technique

- **Backend :** PHP 8.3 — architecture MVC custom
- **Base de données :** MySQL 8.4 avec PDO
- **Frontend :** HTML5, CSS3, JavaScript vanilla
- **Serveur local :** Apache (mod_rewrite) via Laragon
- **Sécurité :** bcrypt, CSRF tokens, sessions sécurisées, requêtes préparées PDO

---

## Structure du projet

```
FashionFamily/
├── app/
│   ├── controllers/          # Logique métier (MVC)
│   │   ├── Admin/            # Contrôleurs admin
│   │   ├── AuthController.php
│   │   ├── ProductController.php
│   │   ├── CartController.php
│   │   ├── OrderController.php
│   │   ├── MessageController.php
│   │   └── ...
│   ├── models/               # Accès données
│   │   ├── User.php
│   │   ├── Article.php
│   │   ├── Order.php
│   │   ├── Message.php
│   │   └── Cart.php          # Panier en session
│   └── views/                # Templates HTML/PHP
│       ├── layout.php
│       ├── admin/
│       ├── auth/
│       ├── products/
│       └── ...
├── core/
│   ├── Router.php            # Dispatcher de routes
│   ├── Database.php          # Singleton PDO
│   ├── BaseController.php
│   └── Session.php
├── utils/
│   ├── Auth.php              # Helpers authentification
│   ├── Validator.php
│   ├── Sanitizer.php
│   └── Flash.php             # Messages flash
├── config/
│   └── config.php            # Configuration globale
├── public/                   # Racine web
│   ├── index.php             # Point d'entrée
│   ├── .htaccess
│   ├── css/
│   ├── js/
│   └── uploads/              # Images produits
├── logs/
│   └── app.log
└── fashion_family.sql        # Dump de la base de données
```

---

## Installation

### Prérequis

- [Laragon](https://laragon.org/) (ou tout serveur Apache + PHP 8.3+ + MySQL 8.4+)
- PHP avec l'extension `pdo_mysql`
- Apache avec `mod_rewrite` activé

### Étapes

**1. Cloner le dépôt**

```bash
git clone https://github.com/<votre-org>/FashionFamily.git
# Placer le dossier dans : C:/laragon/www/FashionFamily
```

**2. Importer la base de données**

```bash
mysql -u root -p < fashion_family.sql
```

Ou via phpMyAdmin : créer une base `fashion_family` et importer le fichier `.sql`.

**3. Configurer l'application**

Éditer `config/config.php` :

```php
define('DB_HOST',    'localhost');
define('DB_NAME',    'fashion_family');
define('DB_USER',    'root');
define('DB_PASS',    '');          // adapter selon votre config
```

**4. (Optionnel) Configurer un virtual host**

Dans la configuration Apache de Laragon :

```apache
<VirtualHost *:80>
    ServerName fashionfamily.test
    DocumentRoot "C:/laragon/www/FashionFamily/public"
    <Directory "C:/laragon/www/FashionFamily/public">
        AllowOverride All
    </Directory>
</VirtualHost>
```

Puis dans `C:\Windows\System32\drivers\etc\hosts` :

```
127.0.0.1 fashionfamily.test
```

**5. Démarrer Laragon et accéder à l'application**

- Avec virtual host : `http://fashionfamily.test/`
- Sans virtual host : `http://localhost/FashionFamily/public/`

---

## Configuration

Toutes les options se trouvent dans `config/config.php`.

| Variable           | Valeur par défaut                   | Description                             |
| ------------------ | ----------------------------------- | --------------------------------------- |
| `ENVIRONMENT`      | `development`                       | Mode `production` pour la mise en ligne |
| `DB_*`             | `localhost / fashion_family / root` | Connexion MySQL                         |
| `SECRET_KEY`       | _(à changer)_                       | Clé de signature des sessions           |
| `SESSION_LIFETIME` | `7200`                              | Durée de session en secondes            |
| `ITEMS_PER_PAGE`   | `10`                                | Pagination du catalogue                 |
| `MAX_UPLOAD_SIZE`  | `5MB`                               | Taille max des images produits          |
| `CONTACT_EMAIL`    | `contact@fashionfamily.com`         | Email de contact                        |

---

## Base de données

### Schéma simplifié

```
users ──< articles ──< order_items >── orders
  │                                      │
  └──< messages >────────────────────────┘
```

### Tables principales

| Table         | Rôle                                                |
| ------------- | --------------------------------------------------- |
| `users`       | Comptes utilisateurs (`role`: user / admin)         |
| `articles`    | Annonces de vente avec condition, catégorie, statut |
| `orders`      | Commandes avec statut de livraison                  |
| `order_items` | Lignes de commande (article + quantité + prix)      |
| `messages`    | Messages privés entre utilisateurs                  |

---

## Routes principales

### Publiques

| Méthode | Route                     | Description                            |
| ------- | ------------------------- | -------------------------------------- |
| GET     | `/`                       | Page d'accueil                         |
| GET     | `/products`               | Catalogue (`?sort=price_asc\|newest…`) |
| GET     | `/products/show?id=`      | Fiche produit                          |
| GET     | `/products/category?cat=` | Filtrer par catégorie                  |
| GET     | `/search?q=`              | Recherche                              |

### Authentification

| Méthode  | Route       | Description |
| -------- | ----------- | ----------- |
| GET/POST | `/login`    | Connexion   |
| GET/POST | `/register` | Inscription |
| GET      | `/logout`   | Déconnexion |

### Espace connecté

| Méthode  | Route            | Description                |
| -------- | ---------------- | -------------------------- |
| GET/POST | `/sell`          | Mettre un article en vente |
| GET      | `/cart`          | Panier                     |
| POST     | `/cart/checkout` | Passer commande            |
| GET      | `/dashboard`     | Tableau de bord vendeur    |
| GET      | `/messages`      | Messagerie                 |

### Admin

| Méthode | Route             | Description              |
| ------- | ----------------- | ------------------------ |
| GET     | `/admin`          | Panel d'administration   |
| GET     | `/admin/users`    | Gestion des utilisateurs |
| GET     | `/admin/products` | Gestion des articles     |
| GET     | `/admin/orders`   | Gestion des commandes    |

---

## Sécurité

- Mots de passe hashés avec **bcrypt** (`PASSWORD_DEFAULT`)
- Protection **CSRF** sur tous les formulaires POST
- Requêtes SQL **préparées** (PDO) — pas d'injection possible
- **Sanitisation** des entrées utilisateurs
- Cookies de session
- En production : sessions limitées au protocole **HTTPS**
- `.htaccess` bloque l'accès direct aux fichiers sensibles

---

## Catégories disponibles

`vetements` · `chaussures` · `accessoires` · `electronique` · `maison` · `sport` · `beaute` · `jouets` · `livres` · `musique` · `jardinage` · `vehicules` · `autres`

---

## Équipe

Projet réalisé dans le cadre d'un projet de formation professionnelle.

| Contributeur | Rôle                                       |
| ------------ | ------------------------------------------ |
| Kevin        | Backend, architecture MVC, base de données |
| Guenael      | Frontend                                   |
| Jamila       | Base de données                            |
| Bilal        | Base de données                            |
| Harun        | Base de données                            |

---

## Licence

Ce projet est distribué à des fins éducatives.
# fashionfamily
