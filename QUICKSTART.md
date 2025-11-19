# Guide de Démarrage Rapide 🚀

Lancez Travaux Pro en 5 minutes chrono !

## Installation Express

### Option 1 : Installation Automatique (Recommandé)

```bash
# 1. Cloner le projet
git clone https://github.com/votre-repo/travaux-pro.git
cd travaux-pro

# 2. Lancer le script d'installation
chmod +x install.sh
./install.sh
```

Le script va :
- ✅ Vérifier les prérequis
- ✅ Configurer la base de données
- ✅ Créer le fichier .env
- ✅ Importer les données de démonstration
- ✅ Configurer le serveur web

### Option 2 : Installation Manuelle

```bash
# 1. Base de données
mysql -u root -p
CREATE DATABASE travaux_pro;
exit

# 2. Import du schéma
mysql -u root -p travaux_pro < database/schema.sql
mysql -u root -p travaux_pro < database/advanced_features.sql
mysql -u root -p travaux_pro < database/internationalization.sql
mysql -u root -p travaux_pro < database/custom_form_examples.sql
mysql -u root -p travaux_pro < database/complete_trades_forms.sql
mysql -u root -p travaux_pro < database/additional_trades_forms.sql
mysql -u root -p travaux_pro < database/payments.sql
mysql -u root -p travaux_pro < database/seed.sql

# 3. Configuration
cp .env.example .env
# Éditer .env avec vos paramètres

# 4. Dossiers
mkdir -p uploads/{projects,profiles,reviews,messages}
mkdir -p storage/{logs,cache,sessions}
chmod -R 775 uploads storage

# 5. Dépendances PHP (optionnel)
composer install
```

## Lancement

### Serveur de développement PHP

```bash
php -S localhost:8000 -t public
```

Accédez à : http://localhost:8000

### Avec Docker

```bash
docker-compose up -d
```

Accédez à : http://localhost

## Comptes de Démonstration

Après avoir importé `database/seed.sql`, vous pouvez vous connecter avec :

### Client
- **Email :** client@demo.com
- **Mot de passe :** password123

### Artisan
- **Email :** artisan@demo.com
- **Mot de passe :** password123

### Admin
- **Email :** admin@demo.com
- **Mot de passe :** password123

## Données de Démonstration

Le fichier `seed.sql` créé automatiquement :

📋 **Projets**
- 6 projets actifs dans différentes catégories
- Électricité, Plomberie, Peinture, Carrelage, Menuiserie, Toiture

💬 **Messages**
- 8 messages entre clients et artisans
- Conversations réalistes

📝 **Devis**
- 6 devis avec différents statuts
- Montants et descriptions détaillées

⭐ **Avis**
- 2 avis clients 5 étoiles
- Commentaires positifs

🔔 **Notifications**
- 13 notifications de démonstration
- Messages de bienvenue

## Vérification Rapide

### 1. Page d'accueil
```
http://localhost:8000
```
✅ Doit afficher la page d'accueil avec les fonctionnalités

### 2. Connexion
```
http://localhost:8000/login
```
✅ Se connecter avec client@demo.com / password123

### 3. Dashboard
```
http://localhost:8000/client/dashboard
```
✅ Voir vos projets et statistiques

### 4. Créer un projet
```
http://localhost:8000/client/projects/create
```
✅ Formulaire de création de projet fonctionnel

### 5. Catalogue des métiers
```
http://localhost:8000/trades
```
✅ 60+ métiers avec formulaires personnalisés

### 6. API
```
http://localhost:8000/api/categories
```
✅ Retourne les catégories en JSON

## Application Mobile

### Installation

```bash
cd mobile
npm install

# iOS (Mac uniquement)
cd ios && pod install && cd ..
npm run ios

# Android
npm run android
```

### Configuration API

Éditer `mobile/src/services/api.js` :

```javascript
// Pour émulateur Android
const API_BASE_URL = 'http://10.0.2.2:8000/api';

// Pour simulateur iOS
const API_BASE_URL = 'http://localhost:8000/api';

// Pour appareil réel
const API_BASE_URL = 'http://VOTRE_IP:8000/api';
```

## Tester l'API

### Avec cURL

```bash
# Inscription
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123",
    "role": "client",
    "first_name": "Test",
    "last_name": "User"
  }'

# Connexion
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "client@demo.com",
    "password": "password123"
  }'

# Liste des projets (avec token)
curl http://localhost:8000/api/projects \
  -H "Authorization: Bearer VOTRE_TOKEN"
```

### Avec Postman

1. Importer `postman/travaux-pro-api-tests.json`
2. Créer un environnement avec `base_url = http://localhost:8000`
3. Exécuter les requêtes

## Fonctionnalités Disponibles

### Plateforme Web

✅ **Authentification**
- Inscription client/artisan
- Connexion JWT
- Gestion de session

✅ **Projets**
- Création avec formulaires dynamiques
- Upload de photos
- Géolocalisation

✅ **Devis**
- Envoi par artisans
- Acceptation/Refus clients
- Comparaison

✅ **Messagerie**
- Chat temps réel
- Historique conversations

✅ **Recherche Artisans**
- Filtres multiples
- Géolocalisation
- Avis et notes

✅ **Catalogue Métiers**
- 60+ métiers
- Formulaires personnalisés
- Multilingue (5 langues)

✅ **Analytics**
- Prix du marché
- Statistiques par région
- Comparaison devis

### Application Mobile

✅ **25+ écrans**
- iOS & Android
- Navigation fluide
- Design Material

✅ **Notifications Push**
- Firebase Cloud Messaging
- Temps réel

✅ **Fonctionnalités complètes**
- Parité avec la version web
- Mode offline (partiel)
- Upload photos

## Configuration Avancée

### Email SMTP

Éditez `.env` :

```env
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre.email@gmail.com
MAIL_PASSWORD=votre_mot_de_passe_app
MAIL_FROM_ADDRESS=noreply@travauxpro.com
MAIL_FROM_NAME="Travaux Pro"
```

### Stripe Paiements

```env
STRIPE_SECRET_KEY=sk_test_...
STRIPE_PUBLISHABLE_KEY=pk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

### Firebase Notifications

1. Créer projet sur https://console.firebase.google.com
2. Télécharger `google-services.json` (Android)
3. Télécharger `GoogleService-Info.plist` (iOS)
4. Ajouter à `.env` :

```env
FIREBASE_SERVER_KEY=votre_cle_serveur
FIREBASE_SENDER_ID=votre_sender_id
```

## Résolution de Problèmes

### Base de données

```bash
# Vérifier la connexion
mysql -u root -p -e "USE travaux_pro; SELECT COUNT(*) FROM users;"

# Réimporter si nécessaire
mysql -u root -p travaux_pro < database/schema.sql
```

### Permissions

```bash
# Fixer les permissions
chmod -R 775 uploads storage
chown -R www-data:www-data uploads storage  # Pour Apache/Nginx
```

### Cache

```bash
# Vider le cache
rm -rf storage/cache/*
rm -rf storage/sessions/*
```

### PHP Extensions

Vérifier que toutes les extensions sont installées :

```bash
php -m | grep -E "pdo|mysql|gd|curl|mbstring|json|openssl"
```

Si manquantes :

```bash
sudo apt install php8.1-mysql php8.1-gd php8.1-curl php8.1-mbstring php8.1-xml
```

## Prochaines Étapes

1. **Personnalisation**
   - Logo dans `public/assets/images/`
   - Couleurs dans les CSS
   - Textes dans `src/Helpers/i18n.php`

2. **Configuration Stripe**
   - Créer compte sur stripe.com
   - Obtenir clés API
   - Configurer webhooks

3. **Firebase Setup**
   - Configurer les notifications push
   - Tester sur mobile

4. **Déploiement Production**
   - Voir `DEPLOYMENT.md`
   - Configurer SSL
   - Optimiser performances

## Support

📚 **Documentation complète**
- `README.md` - Vue d'ensemble
- `API_DOCUMENTATION.md` - API REST
- `DEPLOYMENT.md` - Déploiement production
- `CONTRIBUTING.md` - Contribution

💬 **Aide**
- Issues GitHub
- Email : support@travauxpro.com

---

**🎉 Vous êtes prêt ! Bon développement !**
