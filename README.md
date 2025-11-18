# Travaux Pro - Plateforme de mise en relation

Plateforme moderne en PHP pour mettre en relation particuliers et artisans du bâtiment, inspirée de travaux.com.

## Fonctionnalités

### Pour les particuliers
- 📝 Publier des projets de travaux avec photos
- 💰 Recevoir et comparer plusieurs devis
- ⭐ Consulter les avis sur les artisans
- 💬 Messagerie intégrée avec les artisans
- 📊 Tableau de bord de suivi des projets

### Pour les artisans
- 🔍 Rechercher des projets par catégorie et localisation
- 📄 Soumettre des devis détaillés
- 👤 Créer un profil professionnel
- 📸 Portfolio de réalisations
- 📈 Statistiques et gestion des devis

### Fonctionnalités techniques
- 🎨 Design responsive et moderne
- 🔐 Système d'authentification sécurisé
- 🗄️ Architecture MVC propre
- 📱 Interface mobile-friendly
- 🔒 Protection contre les injections SQL (requêtes préparées)
- 🎯 Gestion des sessions
- 📤 Upload de photos
- 🔎 Système de recherche et filtres

## Installation

### Prérequis
- PHP 8.0 ou supérieur
- MySQL 5.7 ou supérieur
- Apache avec mod_rewrite activé

### Étapes d'installation

1. **Cloner le projet**
```bash
git clone <repository-url>
cd travaux
```

2. **Créer la base de données**
```bash
mysql -u root -p < database/schema.sql
```

3. **Configurer la connexion**
Éditer `src/Config/config.php` avec vos paramètres de base de données:
```php
'database' => [
    'host' => 'localhost',
    'dbname' => 'travaux_db',
    'username' => 'root',
    'password' => 'votre_mot_de_passe',
    'charset' => 'utf8mb4'
]
```

4. **Configurer Apache**
Assurez-vous que le DocumentRoot pointe vers le dossier `public/` ou utilisez le .htaccess fourni.

5. **Définir les permissions**
```bash
chmod -R 755 public/uploads
```

6. **Accéder à l'application**
Ouvrez votre navigateur et accédez à `http://localhost/`

## Structure du projet

```
travaux/
├── public/              # Point d'entrée web
│   ├── css/            # Fichiers CSS
│   ├── js/             # Fichiers JavaScript
│   ├── uploads/        # Fichiers uploadés
│   └── index.php       # Point d'entrée principal
├── src/
│   ├── Config/         # Configuration et router
│   ├── Controllers/    # Contrôleurs
│   ├── Models/         # Modèles de données
│   ├── Views/          # Templates
│   └── Middleware/     # Middlewares
├── database/           # Scripts SQL
└── README.md
```

## Comptes par défaut

**Administrateur:**
- Email: admin@travaux.com
- Mot de passe: admin123

Pour créer des comptes client et artisan, utilisez le formulaire d'inscription.

## Catégories de travaux

Le système inclut 12 catégories par défaut:
- Électricité
- Plomberie
- Maçonnerie
- Peinture
- Menuiserie
- Toiture
- Chauffage
- Climatisation
- Carrelage
- Jardinage
- Nettoyage
- Rénovation complète

## Technologies utilisées

- **Backend:** PHP 8+ avec architecture MVC
- **Base de données:** MySQL avec requêtes préparées (PDO)
- **Frontend:** HTML5, CSS3, JavaScript vanilla
- **Design:** CSS moderne avec variables CSS et Flexbox/Grid
- **Icônes:** Font Awesome 6
- **Sécurité:** Password hashing, sessions sécurisées, protection CSRF

## Développement

### Architecture MVC

- **Models:** Gestion des données et interactions avec la base de données
- **Views:** Templates PHP pour l'affichage
- **Controllers:** Logique métier et coordination

### Routing

Le système de routing utilise un router personnalisé avec support des:
- Routes GET/POST
- Paramètres dynamiques
- Middlewares d'authentification

### Sécurité

- Mots de passe hashés avec `password_hash()`
- Requêtes préparées pour prévenir les injections SQL
- Validation des données côté serveur
- Protection XSS avec `htmlspecialchars()`
- Sessions sécurisées

## Licence

MIT License

## Support

Pour toute question ou problème, veuillez ouvrir une issue sur GitHub.
