#!/bin/bash

##############################################################################
# Travaux Pro - Vérification Système
# Vérifie que tout est correctement installé et configuré
##############################################################################

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

ERRORS=0
WARNINGS=0

# Logo
echo -e "${BLUE}"
cat << "EOF"
╔════════════════════════════════════════════════════════════╗
║                                                            ║
║     TRAVAUX PRO - Vérification Système ✅                 ║
║                                                            ║
╚════════════════════════════════════════════════════════════╝
EOF
echo -e "${NC}"

print_ok() {
    echo -e "${GREEN}[✓]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[⚠]${NC} $1"
    ((WARNINGS++))
}

print_error() {
    echo -e "${RED}[✗]${NC} $1"
    ((ERRORS++))
}

print_info() {
    echo -e "${BLUE}[i]${NC} $1"
}

print_section() {
    echo ""
    echo -e "${BLUE}═══════════════════════════════════════════════════════${NC}"
    echo -e "${BLUE}  $1${NC}"
    echo -e "${BLUE}═══════════════════════════════════════════════════════${NC}"
}

##############################################################################
# Vérification PHP
##############################################################################

print_section "1. PHP"

if command -v php &> /dev/null; then
    PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2)
    print_ok "PHP installé : $PHP_VERSION"

    # Vérifier version minimale
    PHP_VERSION_NUM=$(echo $PHP_VERSION | cut -d "." -f 1,2)
    if (( $(echo "$PHP_VERSION_NUM >= 8.1" | bc -l) )); then
        print_ok "Version PHP compatible (>= 8.1)"
    else
        print_error "Version PHP trop ancienne. Requis : >= 8.1, Actuel : $PHP_VERSION_NUM"
    fi

    # Vérifier extensions
    print_info "Vérification des extensions PHP..."

    extensions=("pdo" "pdo_mysql" "mbstring" "json" "openssl" "curl" "gd" "xml")

    for ext in "${extensions[@]}"; do
        if php -m | grep -qi "^$ext$"; then
            print_ok "Extension $ext"
        else
            print_error "Extension $ext manquante"
        fi
    done
else
    print_error "PHP non installé"
fi

##############################################################################
# Vérification MySQL
##############################################################################

print_section "2. MySQL"

if command -v mysql &> /dev/null; then
    MYSQL_VERSION=$(mysql -V | cut -d " " -f 5 | cut -d "," -f 1)
    print_ok "MySQL installé : $MYSQL_VERSION"

    # Tester connexion si .env existe
    if [ -f ".env" ]; then
        DB_HOST=$(grep "^DB_HOST=" .env | cut -d '=' -f 2)
        DB_USER=$(grep "^DB_USER=" .env | cut -d '=' -f 2)
        DB_PASSWORD=$(grep "^DB_PASSWORD=" .env | cut -d '=' -f 2)
        DB_NAME=$(grep "^DB_NAME=" .env | cut -d '=' -f 2)

        if mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASSWORD" -e "USE $DB_NAME" &> /dev/null; then
            print_ok "Connexion base de données OK"

            # Compter les tables
            TABLE_COUNT=$(mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASSWORD" -D "$DB_NAME" -e "SHOW TABLES" | wc -l)
            ((TABLE_COUNT--))  # Enlever l'en-tête

            if [ $TABLE_COUNT -gt 0 ]; then
                print_ok "Base de données contient $TABLE_COUNT tables"
            else
                print_warning "Base de données vide. Exécutez les scripts SQL"
            fi
        else
            print_error "Impossible de se connecter à la base de données"
        fi
    else
        print_warning "Fichier .env non trouvé. Impossible de tester la connexion"
    fi
else
    print_warning "MySQL non installé (peut utiliser Docker)"
fi

##############################################################################
# Vérification Fichiers
##############################################################################

print_section "3. Fichiers et Dossiers"

# Fichiers essentiels
files=(".env" "public/index.php" "src/Config/config.php" "src/Controllers/HomeController.php")

for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        print_ok "$file"
    else
        print_error "$file manquant"
    fi
done

# Dossiers
dirs=("uploads" "storage" "public" "src" "database")

for dir in "${dirs[@]}"; do
    if [ -d "$dir" ]; then
        print_ok "Dossier $dir"

        # Vérifier permissions écriture
        if [ -w "$dir" ]; then
            print_ok "  └─ Permissions écriture OK"
        else
            print_warning "  └─ Pas de permission écriture"
        fi
    else
        print_error "Dossier $dir manquant"
    fi
done

##############################################################################
# Vérification Configuration
##############################################################################

print_section "4. Configuration"

if [ -f ".env" ]; then
    print_ok "Fichier .env présent"

    # Vérifier variables importantes
    vars=("DB_HOST" "DB_NAME" "DB_USER" "DB_PASSWORD" "JWT_SECRET" "APP_URL")

    for var in "${vars[@]}"; do
        if grep -q "^$var=" .env && ! grep -q "^$var=$" .env && ! grep -q "^$var=your_" .env; then
            print_ok "$var configuré"
        else
            print_warning "$var non configuré ou utilise valeur par défaut"
        fi
    done
else
    print_error "Fichier .env manquant. Copiez .env.example vers .env"
fi

##############################################################################
# Vérification Serveur Web
##############################################################################

print_section "5. Serveur Web"

# Vérifier si un serveur tourne
if lsof -Pi :8000 -sTCP:LISTEN -t >/dev/null 2>&1; then
    print_ok "Serveur web actif sur le port 8000"
elif lsof -Pi :80 -sTCP:LISTEN -t >/dev/null 2>&1; then
    print_ok "Serveur web actif sur le port 80"
else
    print_info "Aucun serveur web détecté"
    print_info "Démarrez avec : php -S localhost:8000 -t public"
fi

# Vérifier Apache/Nginx
if command -v apache2 &> /dev/null || command -v httpd &> /dev/null; then
    print_ok "Apache installé"
elif command -v nginx &> /dev/null; then
    print_ok "Nginx installé"
else
    print_info "Ni Apache ni Nginx détecté (PHP built-in OK)"
fi

##############################################################################
# Vérification Docker (Optionnel)
##############################################################################

print_section "6. Docker (Optionnel)"

if command -v docker &> /dev/null; then
    print_ok "Docker installé"

    if command -v docker-compose &> /dev/null || docker compose version &> /dev/null; then
        print_ok "Docker Compose installé"

        if [ -f "docker-compose.yml" ]; then
            print_ok "Fichier docker-compose.yml présent"

            # Vérifier containers actifs
            if docker ps | grep -q travaux; then
                print_ok "Containers Docker actifs"
            else
                print_info "Containers Docker non démarrés"
                print_info "Démarrez avec : docker-compose up -d"
            fi
        fi
    else
        print_warning "Docker Compose non installé"
    fi
else
    print_info "Docker non installé (optionnel)"
fi

##############################################################################
# Vérification Application Mobile
##############################################################################

print_section "7. Application Mobile (Optionnel)"

if [ -d "mobile" ]; then
    print_ok "Dossier mobile présent"

    if command -v node &> /dev/null; then
        NODE_VERSION=$(node -v)
        print_ok "Node.js installé : $NODE_VERSION"

        if command -v npm &> /dev/null; then
            NPM_VERSION=$(npm -v)
            print_ok "npm installé : $NPM_VERSION"

            if [ -d "mobile/node_modules" ]; then
                print_ok "Dépendances mobile installées"
            else
                print_warning "Dépendances mobile non installées"
                print_info "Installez avec : cd mobile && npm install"
            fi
        else
            print_warning "npm non installé"
        fi
    else
        print_info "Node.js non installé (nécessaire pour mobile)"
    fi
else
    print_info "Dossier mobile non trouvé (optionnel)"
fi

##############################################################################
# Vérification Composer (Optionnel)
##############################################################################

print_section "8. Composer (Optionnel)"

if command -v composer &> /dev/null; then
    COMPOSER_VERSION=$(composer -V | cut -d " " -f 3)
    print_ok "Composer installé : $COMPOSER_VERSION"

    if [ -d "vendor" ]; then
        print_ok "Dépendances Composer installées"
    else
        print_info "Dépendances Composer non installées"
        print_info "Installez avec : composer install"
    fi
else
    print_info "Composer non installé (optionnel)"
fi

##############################################################################
# Test API
##############################################################################

print_section "9. Test API"

# Essayer d'accéder à l'API
if command -v curl &> /dev/null; then
    # Vérifier localhost:8000
    if curl -s -o /dev/null -w "%{http_code}" http://localhost:8000 | grep -q "200\|301\|302"; then
        print_ok "API accessible sur http://localhost:8000"

        # Tester endpoint categories
        if curl -s http://localhost:8000/api/categories | grep -q "success"; then
            print_ok "Endpoint API /api/categories fonctionne"
        else
            print_warning "Endpoint API non accessible (vérifiez le routing)"
        fi
    else
        print_info "Serveur non accessible sur localhost:8000"
        print_info "Démarrez avec : php -S localhost:8000 -t public"
    fi
else
    print_info "curl non disponible, test API ignoré"
fi

##############################################################################
# Résumé
##############################################################################

echo ""
echo -e "${BLUE}═══════════════════════════════════════════════════════${NC}"
echo -e "${BLUE}  RÉSUMÉ${NC}"
echo -e "${BLUE}═══════════════════════════════════════════════════════${NC}"
echo ""

if [ $ERRORS -eq 0 ] && [ $WARNINGS -eq 0 ]; then
    echo -e "${GREEN}✓ Système parfaitement configuré ! 🎉${NC}"
    echo ""
    echo "Vous pouvez démarrer l'application avec :"
    echo -e "${BLUE}  php -S localhost:8000 -t public${NC}"
    echo ""
    echo "Puis accédez à : ${GREEN}http://localhost:8000${NC}"
    EXIT_CODE=0
elif [ $ERRORS -eq 0 ]; then
    echo -e "${YELLOW}⚠ Configuration OK avec quelques avertissements${NC}"
    echo -e "  • $WARNINGS avertissement(s)"
    echo ""
    echo "L'application devrait fonctionner. Consultez les avertissements ci-dessus."
    EXIT_CODE=0
else
    echo -e "${RED}✗ Problèmes détectés${NC}"
    echo -e "  • $ERRORS erreur(s)"
    echo -e "  • $WARNINGS avertissement(s)"
    echo ""
    echo "Corrigez les erreurs avant de lancer l'application."
    echo "Consultez QUICKSTART.md pour plus d'aide."
    EXIT_CODE=1
fi

echo ""
exit $EXIT_CODE
