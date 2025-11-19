# Travaux Pro - Application Mobile 📱

Application mobile **iOS & Android** pour la plateforme Travaux Pro, construite avec **React Native**.

## ✨ Nouveautés v1.1.0

- **Catalogue des Métiers** 🔧 - Parcourir les 60+ métiers disponibles avec leurs formulaires personnalisés
- **Favoris** ⭐ - Sauvegarder vos artisans préférés pour y accéder rapidement
- **Prix du Marché** 📊 - Consulter les prix moyens par catégorie et région

## 🚀 Fonctionnalités

### ✅ Authentification
- Inscription (Client / Artisan)
- Connexion avec JWT
- Gestion de session persistante
- Refresh token automatique

### 📋 Projets (Client)
- Liste des projets avec filtres
- Création de projet avec photos
- Modification/Suppression
- Détails projet avec devis reçus
- Géolocalisation GPS

### 🔨 Projets (Artisan)
- Recherche projets disponibles
- Filtres par catégorie, localisation
- Envoi de devis
- Suivi des devis envoyés

### 💬 Messagerie
- Chat temps réel
- Liste des conversations
- Notifications de nouveaux messages
- Envoi de photos

### 👷 Artisans
- Recherche d'artisans
- Profils détaillés avec portfolio
- Avis et notes
- Contact direct

### 📊 Dashboard
- Statistiques personnalisées
- Projets récents
- Devis actifs
- Taux de conversion (Artisan)

### 🔔 Notifications
- Notifications push (Firebase)
- Centre de notifications
- Marquage lu/non lu

### 📸 Upload Photos
- Prise de photo
- Sélection depuis galerie
- Upload multi-photos
- Compression automatique

## 📱 Technologies

- **React Native** 0.72.0
- **React Navigation** 6.x
- **React Native Paper** (Material Design)
- **Axios** (API calls)
- **AsyncStorage** (Stockage local)
- **React Native Maps** (Géolocalisation)
- **React Native Image Picker** (Photos)
- **Firebase** (Push notifications)

## 🛠️ Installation

### Prérequis

**Node.js & npm:**
```bash
node --version  # v16+
npm --version   # v8+
```

**React Native CLI:**
```bash
npm install -g react-native-cli
```

**iOS (Mac uniquement):**
```bash
# Xcode 14+ depuis App Store
sudo gem install cocoapods
```

**Android:**
- Android Studio
- Android SDK (API 31+)
- Java JDK 11+

### Installation du projet

```bash
# 1. Cloner le repo
cd mobile

# 2. Installer dépendances
npm install

# 3. iOS - Installer pods
cd ios
pod install
cd ..

# 4. Configurer l'API
# Éditer src/services/api.js
# Changer API_BASE_URL:
# - Android emulator: http://10.0.2.2/api
# - iOS simulator: http://localhost/api
# - Device réel: http://YOUR_COMPUTER_IP/api
```

### Configuration Firebase (Notifications Push)

**1. Créer projet Firebase:**
- Aller sur https://console.firebase.google.com
- Créer nouveau projet "Travaux Pro"

**2. Android:**
```bash
# Télécharger google-services.json
# Placer dans: android/app/google-services.json
```

**3. iOS:**
```bash
# Télécharger GoogleService-Info.plist
# Placer dans: ios/GoogleService-Info.plist
```

**4. Installer config:**
```bash
# Déjà fait dans package.json
@react-native-firebase/app
@react-native-firebase/messaging
```

## 🚀 Lancement

### iOS

```bash
# Simulateur
npm run ios

# Device spécifique
npm run ios -- --simulator="iPhone 14 Pro"

# Device physique
# Ouvrir ios/TravauxPro.xcworkspace dans Xcode
# Sélectionner device et Run
```

### Android

```bash
# Émulateur (lancer AVD d'abord)
npm run android

# Device physique
# Activer débogage USB
# Connecter appareil
npm run android
```

## 📁 Structure du projet

```
mobile/
├── src/
│   ├── screens/           # Écrans
│   │   ├── auth/          # Login, Register
│   │   ├── home/          # Dashboard
│   │   ├── projects/      # Projets (liste, détails, création)
│   │   ├── quotes/        # Devis
│   │   ├── messages/      # Chat
│   │   ├── artisans/      # Recherche artisans
│   │   ├── profile/       # Profil utilisateur
│   │   ├── notifications/ # Notifications
│   │   └── settings/      # Paramètres
│   ├── components/        # Composants réutilisables
│   ├── context/           # Context API (Auth, etc.)
│   ├── services/          # API, Storage, etc.
│   ├── utils/             # Helpers
│   ├── assets/            # Images, fonts
│   └── styles/            # Thèmes, constantes
├── android/               # Code Android natif
├── ios/                   # Code iOS natif
├── App.js                 # Point d'entrée
└── package.json
```

## 🔑 Configuration API Backend

L'application mobile utilise l'API REST du backend PHP.

### Endpoints utilisés:

```
POST   /api/auth/register      - Inscription
POST   /api/auth/login         - Connexion
POST   /api/auth/refresh       - Refresh token
GET    /api/auth/me            - Utilisateur actuel

GET    /api/projects           - Liste projets
GET    /api/projects/:id       - Détails projet
POST   /api/projects           - Créer projet
PUT    /api/projects/:id       - Modifier projet
DELETE /api/projects/:id       - Supprimer projet

GET    /api/quotes             - Liste devis
POST   /api/quotes             - Créer devis
PUT    /api/quotes/:id/accept  - Accepter devis
PUT    /api/quotes/:id/reject  - Refuser devis

GET    /api/messages           - Conversations
GET    /api/messages/:userId   - Messages avec user
POST   /api/messages           - Envoyer message

GET    /api/artisans           - Rechercher artisans
GET    /api/artisans/:id       - Profil artisan

POST   /api/reviews            - Créer avis

GET    /api/notifications      - Notifications
PUT    /api/notifications/:id/read - Marquer lu

POST   /api/upload             - Upload image

GET    /api/stats/dashboard    - Statistiques
```

### Authentification JWT

L'application utilise JWT (JSON Web Tokens) pour l'authentification:

1. **Login/Register** retourne un token
2. Token stocké dans AsyncStorage
3. Chaque requête inclut: `Authorization: Bearer {token}`
4. Token expire après 24h
5. Refresh automatique si expiré

## 📸 Screenshots

### Client Flow
- **Dashboard**: Statistiques, projets récents, actions rapides
- **Projets**: Liste filtrée, création avec photos
- **Artisans**: Recherche, profils, contact
- **Messages**: Chat avec artisans

### Artisan Flow
- **Dashboard**: KPIs, taux de conversion, projets récents
- **Projets disponibles**: Recherche, filtres
- **Mes devis**: Envoyés, acceptés, refusés
- **Messages**: Chat avec clients

## 🔧 Développement

### Debug

```bash
# Activer debug menu
# iOS: Cmd+D
# Android: Cmd+M / Ctrl+M

# Chrome DevTools
# Debug menu > Debug
```

### Hot Reload

```bash
# Activé par défaut
# iOS: Cmd+R pour reload
# Android: RR pour reload
```

### Build Production

**Android (APK):**
```bash
cd android
./gradlew assembleRelease

# APK dans: android/app/build/outputs/apk/release/
```

**Android (AAB pour Play Store):**
```bash
cd android
./gradlew bundleRelease

# AAB dans: android/app/build/outputs/bundle/release/
```

**iOS (Archive):**
```bash
# Ouvrir Xcode
# Product > Archive
# Distribute App > App Store Connect
```

## 🎨 Personnalisation

### Thème / Couleurs

Éditer `src/styles/theme.js`:

```javascript
export const theme = {
  colors: {
    primary: '#3b82f6',    // Bleu principal
    success: '#10b981',    // Vert
    warning: '#f59e0b',    // Orange
    danger: '#ef4444',     // Rouge
    ...
  }
};
```

### Logo

Remplacer `src/assets/logo.png` avec votre logo.

### Nom de l'app

**Android:** `android/app/src/main/res/values/strings.xml`
```xml
<string name="app_name">Travaux Pro</string>
```

**iOS:** Ouvrir Xcode > General > Display Name

## 🐛 Troubleshooting

### Erreur: "Unable to resolve module"

```bash
npm start -- --reset-cache
```

### iOS: Pods error

```bash
cd ios
rm -rf Pods Podfile.lock
pod install
cd ..
```

### Android: Build failed

```bash
cd android
./gradlew clean
cd ..
```

### Metro bundler issues

```bash
# Tuer tous les processus Metro
killall node

# Nettoyer
npm start -- --reset-cache
```

## 📄 Permissions

### iOS (ios/TravauxPro/Info.plist)

```xml
<key>NSCameraUsageDescription</key>
<string>Prendre des photos de vos projets</string>
<key>NSPhotoLibraryUsageDescription</key>
<string>Sélectionner des photos</string>
<key>NSLocationWhenInUseUsageDescription</key>
<string>Localiser votre projet</string>
```

### Android (android/app/src/main/AndroidManifest.xml)

```xml
<uses-permission android:name="android.permission.CAMERA" />
<uses-permission android:name="android.permission.READ_EXTERNAL_STORAGE" />
<uses-permission android:name="android.permission.WRITE_EXTERNAL_STORAGE" />
<uses-permission android:name="android.permission.ACCESS_FINE_LOCATION" />
```

## 🚀 Déploiement

### Google Play Store

1. Créer compte développeur ($25)
2. Générer AAB: `./gradlew bundleRelease`
3. Signer avec keystore
4. Upload sur Play Console
5. Remplir fiche store
6. Soumettre pour review

### Apple App Store

1. Compte développeur Apple ($99/an)
2. Créer App ID dans Apple Developer
3. Archive depuis Xcode
4. Upload vers App Store Connect
5. Remplir fiche store
6. Soumettre pour review

## 📊 Analytics (optionnel)

Intégrer Firebase Analytics:

```bash
npm install @react-native-firebase/analytics

# Suivre événements
import analytics from '@react-native-firebase/analytics';

analytics().logEvent('project_created', {
  category: 'engagement',
  label: 'New Project'
});
```

## 🔒 Sécurité

- ✅ JWT tokens avec expiration
- ✅ HTTPS uniquement en production
- ✅ Validation inputs côté client
- ✅ Storage sécurisé (AsyncStorage)
- ✅ Pas de données sensibles en clair

## 📝 TODO / Améliorations

- [ ] Notifications push temps réel
- [ ] Chat temps réel avec WebSocket
- [ ] Paiement in-app (Stripe)
- [ ] Mode offline
- [ ] Deep linking
- [ ] Share projet
- [ ] Dark mode
- [ ] Multi-langue UI
- [ ] Tests E2E (Detox)
- [ ] CI/CD (GitHub Actions)

## 📄 Licence

MIT License - Voir fichier LICENSE

## 👥 Support

Email: support@travauxpro.com

---

**Version:** 1.0.0
**Dernière mise à jour:** 2024
**Compatibilité:** iOS 13+, Android 8+
