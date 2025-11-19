#!/bin/bash

##############################################################################
# Travaux Pro - Installation Automatique
# Ce script installe et configure automatiquement la plateforme complète
##############################################################################

set -e  # Arrêt en cas d'erreur

# Couleurs pour l'affichage
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Logo
echo -e "${BLUE}"
cat << "EOF"
╔════════════════════════════════════════════════════════════╗
║                                                            ║
║     TRAVAUX PRO - Installation Automatique 🚀             ║
║     Plateforme Complète de Mise en Relation               ║
║                                                            ║
╚════════════════════════════════════════════════════════════╝
EOF
echo -e "${NC}"

# Fonction pour afficher les messages
print_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[✓]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[⚠]${NC} $1"
}

print_error() {
    echo -e "${RED}[✗]${NC} $1"
}

# Fonction pour vérifier une commande
check_command() {
    if command -v $1 &> /dev/null; then
        print_success "$1 est installé"
        return 0
    else
        print_error "$1 n'est pas installé"
        return 1
    fi
}

# Bannière étape
print_step() {
    echo ""
    echo -e "${GREEN}═══════════════════════════════════════════════════════${NC}"
    echo -e "${GREEN}  $1${NC}"
    echo -e "${GREEN}═══════════════════════════════════════════════════════${NC}"
    echo ""
}

##############################################################################
# ÉTAPE 1 : Vérification des prérequis
##############################################################################

print_step "ÉTAPE 1 : Vérification des prérequis"

print_info "Vérification des logiciels nécessaires..."

# Vérification PHP
if check_command php; then
    PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
    print_info "Version PHP : $PHP_VERSION"
    if (( $(echo "$PHP_VERSION < 8.1" | bc -l) )); then
        print_error "PHP 8.1 ou supérieur requis"
        exit 1
    fi
else
    print_error "PHP non trouvé. Installez PHP 8.1+ avec : sudo apt install php8.1"
    exit 1
fi

# Vérification MySQL
if check_command mysql; then
    MYSQL_VERSION=$(mysql -V | cut -d " " -f 5 | cut -d "," -f 1)
    print_info "Version MySQL : $MYSQL_VERSION"
else
    print_warning "MySQL non trouvé. Voulez-vous utiliser Docker pour MySQL ? (recommandé)"
    read -p "Utiliser Docker MySQL ? (y/n) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        USE_DOCKER_MYSQL=true
    fi
fi

# Vérification Composer
if ! check_command composer; then
    print_info "Installation de Composer..."
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    print_success "Composer installé"
fi

# Vérification Node.js (pour l'app mobile)
if check_command node; then
    NODE_VERSION=$(node -v)
    print_info "Version Node.js : $NODE_VERSION"
else
    print_warning "Node.js non trouvé (nécessaire pour l'app mobile)"
fi

# Vérification Docker (optionnel)
if check_command docker; then
    print_success "Docker disponible"
    DOCKER_AVAILABLE=true
else
    print_warning "Docker non disponible (optionnel)"
    DOCKER_AVAILABLE=false
fi

##############################################################################
# ÉTAPE 2 : Configuration de la base de données
##############################################################################

print_step "ÉTAPE 2 : Configuration de la base de données"

# Demander les informations de connexion
print_info "Configuration de la base de données..."

if [ "$USE_DOCKER_MYSQL" = true ]; then
    print_info "Démarrage de MySQL via Docker..."
    docker-compose up -d mysql
    DB_HOST="localhost"
    DB_PORT="3306"
    DB_NAME="travaux_pro"
    DB_USER="travaux_user"
    DB_PASSWORD="travaux_password"
    DB_ROOT_PASSWORD="root_password"
    sleep 10  # Attendre que MySQL démarre
else
    read -p "Hôte MySQL [localhost]: " DB_HOST
    DB_HOST=${DB_HOST:-localhost}

    read -p "Port MySQL [3306]: " DB_PORT
    DB_PORT=${DB_PORT:-3306}

    read -p "Nom de la base de données [travaux_pro]: " DB_NAME
    DB_NAME=${DB_NAME:-travaux_pro}

    read -p "Utilisateur MySQL [travaux_user]: " DB_USER
    DB_USER=${DB_USER:-travaux_user}

    read -sp "Mot de passe MySQL : " DB_PASSWORD
    echo

    read -sp "Mot de passe root MySQL : " DB_ROOT_PASSWORD
    echo
fi

# Créer la base de données
print_info "Création de la base de données..."

mysql -h "$DB_HOST" -P "$DB_PORT" -u root -p"$DB_ROOT_PASSWORD" << EOF
CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$DB_USER'@'%' IDENTIFIED BY '$DB_PASSWORD';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'%';
FLUSH PRIVILEGES;
EOF

print_success "Base de données créée"

# Importer le schéma
print_info "Import du schéma de base de données..."

mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" < database/schema.sql
print_success "Schéma principal importé"

mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" < database/advanced_features.sql
print_success "Fonctionnalités avancées importées"

mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" < database/internationalization.sql
print_success "Internationalisation importée"

mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" < database/custom_form_examples.sql
print_success "Formulaires personnalisés importés"

mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" < database/complete_trades_forms.sql
print_success "Formulaires métiers complets importés"

mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" < database/additional_trades_forms.sql
print_success "Formulaires métiers additionnels importés"

mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" < database/payments.sql
print_success "Système de paiement importé"

##############################################################################
# ÉTAPE 3 : Configuration de l'environnement
##############################################################################

print_step "ÉTAPE 3 : Configuration de l'environnement"

# Générer une clé JWT aléatoire
JWT_SECRET=$(openssl rand -base64 32)

# Demander les informations Stripe
print_info "Configuration des clés API..."

read -p "Clé secrète Stripe (optionnel, Entrée pour passer) : " STRIPE_SECRET
STRIPE_SECRET=${STRIPE_SECRET:-sk_test_your_stripe_secret_key}

read -p "Clé publique Stripe (optionnel, Entrée pour passer) : " STRIPE_PUBLIC
STRIPE_PUBLIC=${STRIPE_PUBLIC:-pk_test_your_stripe_public_key}

# Créer le fichier .env
print_info "Création du fichier .env..."

cat > .env << EOF
# Travaux Pro - Configuration Environnement
# Généré automatiquement le $(date)

# Application
APP_NAME="Travaux Pro"
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost
APP_TIMEZONE=Europe/Paris

# Base de données
DB_HOST=$DB_HOST
DB_PORT=$DB_PORT
DB_NAME=$DB_NAME
DB_USER=$DB_USER
DB_PASSWORD=$DB_PASSWORD
DB_ROOT_PASSWORD=$DB_ROOT_PASSWORD

# JWT Authentication
JWT_SECRET=$JWT_SECRET
JWT_EXPIRATION=86400

# Stripe Payment
STRIPE_SECRET_KEY=$STRIPE_SECRET
STRIPE_PUBLISHABLE_KEY=$STRIPE_PUBLIC
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret

# Firebase (Push Notifications)
FIREBASE_SERVER_KEY=your_firebase_server_key
FIREBASE_SENDER_ID=your_firebase_sender_id

# Email SMTP
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_FROM_ADDRESS=noreply@travauxpro.com
MAIL_FROM_NAME="Travaux Pro"

# Storage
UPLOAD_MAX_SIZE=5242880
UPLOAD_PATH=uploads/

# Session
SESSION_LIFETIME=86400

# Rate Limiting
RATE_LIMIT_PER_HOUR=100

# Localization
DEFAULT_LANGUAGE=fr
DEFAULT_COUNTRY=FR
DEFAULT_CURRENCY=EUR

# Redis Cache (Optionnel)
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=null
EOF

print_success "Fichier .env créé"

# Créer les dossiers nécessaires
print_info "Création des dossiers..."

mkdir -p uploads/{projects,profiles,reviews,messages}
mkdir -p storage/{logs,cache,sessions}
mkdir -p public/assets/{css,js,images}

chmod -R 775 uploads storage
chmod -R 755 public

print_success "Dossiers créés avec les bonnes permissions"

##############################################################################
# ÉTAPE 4 : Installation des dépendances
##############################################################################

print_step "ÉTAPE 4 : Installation des dépendances"

# PHP Dependencies
if [ -f "composer.json" ]; then
    print_info "Installation des dépendances PHP..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
    print_success "Dépendances PHP installées"
fi

##############################################################################
# ÉTAPE 5 : Import des données de démonstration
##############################################################################

print_step "ÉTAPE 5 : Import des données de démonstration"

print_info "Voulez-vous importer des données de démonstration ?"
read -p "Importer les données de test ? (y/n) " -n 1 -r
echo

if [[ $REPLY =~ ^[Yy]$ ]]; then
    if [ -f "database/seed.sql" ]; then
        print_info "Import des données de démonstration..."
        mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" < database/seed.sql
        print_success "Données de démonstration importées"

        echo ""
        print_info "Comptes de démonstration créés :"
        echo -e "  ${GREEN}Client${NC} : client@demo.com / password123"
        echo -e "  ${GREEN}Artisan${NC} : artisan@demo.com / password123"
        echo -e "  ${GREEN}Admin${NC} : admin@demo.com / password123"
        echo ""
    else
        print_warning "Fichier seed.sql non trouvé"
    fi
fi

##############################################################################
# ÉTAPE 6 : Configuration du serveur web
##############################################################################

print_step "ÉTAPE 6 : Configuration du serveur web"

print_info "Quel serveur web utilisez-vous ?"
echo "1) PHP Built-in Server (développement)"
echo "2) Apache"
echo "3) Nginx"
echo "4) Docker"
read -p "Choix [1-4]: " WEB_SERVER

case $WEB_SERVER in
    1)
        print_info "Serveur PHP Built-in sélectionné"
        SERVER_COMMAND="php -S localhost:8000 -t public"
        ;;
    2)
        print_info "Configuration Apache..."
        print_warning "Copiez le fichier .htaccess dans votre DocumentRoot"
        print_warning "Activez mod_rewrite : sudo a2enmod rewrite"
        ;;
    3)
        print_info "Configuration Nginx..."
        print_warning "Utilisez la configuration dans nginx.conf"
        ;;
    4)
        print_info "Configuration Docker..."
        if [ "$DOCKER_AVAILABLE" = true ]; then
            docker-compose up -d
            print_success "Containers Docker démarrés"
        fi
        ;;
esac

##############################################################################
# ÉTAPE 7 : Application Mobile (optionnel)
##############################################################################

print_step "ÉTAPE 7 : Application Mobile (optionnel)"

print_info "Voulez-vous installer l'application mobile ?"
read -p "Installer l'app mobile ? (y/n) " -n 1 -r
echo

if [[ $REPLY =~ ^[Yy]$ ]]; then
    if check_command node && check_command npm; then
        print_info "Installation de l'application mobile..."
        cd mobile
        npm install

        # Configuration de l'API
        print_info "Configuration de l'URL de l'API..."
        read -p "URL de votre serveur [http://localhost:8000]: " API_URL
        API_URL=${API_URL:-http://localhost:8000}

        # Mettre à jour api.js
        sed -i "s|const API_BASE_URL = .*|const API_BASE_URL = '$API_URL/api';|" src/services/api.js

        cd ..
        print_success "Application mobile installée"

        print_info "Pour lancer l'app mobile :"
        echo "  cd mobile"
        echo "  npm run android  # ou npm run ios"
    else
        print_error "Node.js ou npm non disponible"
    fi
fi

##############################################################################
# ÉTAPE 8 : Vérification finale
##############################################################################

print_step "ÉTAPE 8 : Vérification finale"

print_info "Vérification de l'installation..."

# Vérifier la connexion DB
if mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -e "SELECT 1" &> /dev/null; then
    print_success "Connexion base de données OK"
else
    print_error "Problème de connexion à la base de données"
fi

# Vérifier les fichiers
if [ -f ".env" ]; then
    print_success "Fichier .env présent"
fi

if [ -d "uploads" ]; then
    print_success "Dossier uploads présent"
fi

##############################################################################
# FIN - Instructions finales
##############################################################################

echo ""
echo -e "${GREEN}═══════════════════════════════════════════════════════${NC}"
echo -e "${GREEN}  Installation terminée avec succès ! 🎉${NC}"
echo -e "${GREEN}═══════════════════════════════════════════════════════${NC}"
echo ""

print_info "Pour démarrer l'application :"
echo ""

if [ ! -z "$SERVER_COMMAND" ]; then
    echo -e "  ${BLUE}$SERVER_COMMAND${NC}"
    echo ""
fi

echo -e "Puis accédez à : ${GREEN}http://localhost:8000${NC}"
echo ""

print_info "Prochaines étapes :"
echo "  1. Configurez vos clés Stripe dans .env"
echo "  2. Configurez Firebase pour les notifications push"
echo "  3. Configurez votre serveur SMTP pour les emails"
echo "  4. Consultez QUICKSTART.md pour plus d'informations"
echo ""

print_info "Documentation :"
echo "  • README.md - Vue d'ensemble"
echo "  • API_DOCUMENTATION.md - Documentation API"
echo "  • DEPLOYMENT.md - Guide de déploiement"
echo "  • CONTRIBUTING.md - Guide de contribution"
echo ""

print_warning "N'oubliez pas de sécuriser votre installation pour la production :"
echo "  • Changez APP_DEBUG=false dans .env"
echo "  • Utilisez HTTPS"
echo "  • Configurez un firewall"
echo "  • Activez les sauvegardes automatiques"
echo ""

print_success "Bon développement ! 🚀"
echo ""
