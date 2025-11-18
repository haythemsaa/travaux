# Travaux Pro - Deployment Guide 🚀

Complete guide for deploying Travaux Pro to production.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Quick Deploy with Docker](#quick-deploy-with-docker)
- [Manual Deployment](#manual-deployment)
- [Mobile App Deployment](#mobile-app-deployment)
- [Environment Configuration](#environment-configuration)
- [Post-Deployment](#post-deployment)
- [Troubleshooting](#troubleshooting)

## Prerequisites

### Server Requirements

**Minimum:**
- 2 CPU cores
- 4 GB RAM
- 20 GB SSD storage
- Ubuntu 20.04 LTS or later

**Recommended (Production):**
- 4 CPU cores
- 8 GB RAM
- 50 GB SSD storage
- Ubuntu 22.04 LTS

### Software Requirements

- Docker 20.10+
- Docker Compose 2.0+
- Git
- SSL Certificate (Let's Encrypt recommended)

## Quick Deploy with Docker

### 1. Clone Repository

```bash
git clone https://github.com/yourusername/travaux.git
cd travaux
```

### 2. Configure Environment

```bash
cp .env.example .env
nano .env  # Edit with your settings
```

**Required Configuration:**

```env
# Database
DB_NAME=travaux_pro
DB_USER=travaux_user
DB_PASSWORD=STRONG_PASSWORD_HERE
DB_ROOT_PASSWORD=STRONG_ROOT_PASSWORD_HERE

# JWT
JWT_SECRET=YOUR_SECRET_KEY_MINIMUM_32_CHARACTERS

# Stripe
STRIPE_SECRET_KEY=sk_live_xxxxxxxxxxxxxxxxxxxxx
STRIPE_PUBLISHABLE_KEY=pk_live_xxxxxxxxxxxxxxxxxxxxx

# Firebase
FIREBASE_SERVER_KEY=your_firebase_server_key
```

### 3. Deploy

```bash
chmod +x deploy.sh
./deploy.sh
```

The script will:
- ✅ Build Docker containers
- ✅ Initialize database
- ✅ Run migrations
- ✅ Set permissions
- ✅ Start all services

### 4. Verify Deployment

```bash
# Check containers
docker-compose ps

# View logs
docker-compose logs -f web
```

Access your application:
- **Web:** http://your-server-ip
- **API:** http://your-server-ip/api
- **PHPMyAdmin (dev only):** http://your-server-ip:8080

## Manual Deployment

### 1. Install Dependencies

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.1
sudo apt install -y php8.1 php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-gd php8.1-bcmath

# Install MySQL
sudo apt install -y mysql-server

# Install Apache
sudo apt install -y apache2

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 2. Configure MySQL

```bash
sudo mysql_secure_installation

# Create database
sudo mysql -u root -p
```

```sql
CREATE DATABASE travaux_pro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'travaux_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON travaux_pro.* TO 'travaux_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Configure Apache

```bash
sudo nano /etc/apache2/sites-available/travaux-pro.conf
```

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    ServerAlias www.your-domain.com
    DocumentRoot /var/www/travaux/public

    <Directory /var/www/travaux/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/travaux-error.log
    CustomLog ${APACHE_LOG_DIR}/travaux-access.log combined
</VirtualHost>
```

```bash
# Enable site and modules
sudo a2ensite travaux-pro.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### 4. Deploy Application

```bash
# Clone repository
cd /var/www
sudo git clone https://github.com/yourusername/travaux.git
cd travaux

# Install dependencies
composer install --no-dev --optimize-autoloader

# Set permissions
sudo chown -R www-data:www-data /var/www/travaux
sudo chmod -R 755 /var/www/travaux
sudo chmod -R 775 /var/www/travaux/uploads
sudo chmod -R 775 /var/www/travaux/storage

# Configure environment
cp .env.example .env
sudo nano .env
```

### 5. Run Migrations

```bash
mysql -u travaux_user -p travaux_pro < database/schema.sql
mysql -u travaux_user -p travaux_pro < database/advanced_features.sql
mysql -u travaux_user -p travaux_pro < database/internationalization.sql
mysql -u travaux_user -p travaux_pro < database/custom_form_examples.sql
mysql -u travaux_user -p travaux_pro < database/payments.sql
```

### 6. Setup SSL (Let's Encrypt)

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-apache

# Obtain certificate
sudo certbot --apache -d your-domain.com -d www.your-domain.com

# Auto-renewal
sudo systemctl enable certbot.timer
```

## Mobile App Deployment

### iOS App Store

1. **Prepare iOS Build:**

```bash
cd mobile/ios
pod install
```

2. **Archive in Xcode:**
   - Open `TravauxPro.xcworkspace`
   - Product > Archive
   - Upload to App Store Connect

3. **Configure App Store Connect:**
   - Screenshots (required for all screen sizes)
   - App description
   - Keywords
   - Privacy policy URL
   - Support URL

### Google Play Store

1. **Generate Signed APK:**

```bash
cd mobile/android
./gradlew bundleRelease
```

2. **Upload to Google Play Console:**
   - Create app listing
   - Upload AAB file
   - Complete store listing
   - Submit for review

### Update API Base URL

Before building, update:

```javascript
// mobile/src/services/api.js
const API_BASE_URL = 'https://your-domain.com/api';
```

## Environment Configuration

### Production Settings

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Enable production caching
CACHE_ENABLED=true
SESSION_SECURE=true
```

### Security Headers

Add to Apache config:

```apache
Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
Header always set X-Frame-Options "SAMEORIGIN"
Header always set X-Content-Type-Options "nosniff"
Header always set X-XSS-Protection "1; mode=block"
```

### Cron Jobs

Setup automated tasks:

```bash
sudo crontab -e
```

```cron
# Send email notifications every 5 minutes
*/5 * * * * php /var/www/travaux/scripts/send-notifications.php

# Clean expired sessions daily
0 2 * * * php /var/www/travaux/scripts/cleanup-sessions.php

# Generate statistics daily
0 3 * * * php /var/www/travaux/scripts/generate-stats.php

# Database backup daily
0 4 * * * /var/www/travaux/scripts/backup-database.sh
```

## Post-Deployment

### 1. Verify Installation

```bash
# Test web interface
curl https://your-domain.com

# Test API
curl https://your-domain.com/api/health

# Check database connection
php -r "new PDO('mysql:host=localhost;dbname=travaux_pro', 'travaux_user', 'password');"
```

### 2. Configure Monitoring

Setup monitoring tools:
- **Uptime:** UptimeRobot, Pingdom
- **Errors:** Sentry
- **Analytics:** Google Analytics, Mixpanel
- **Server:** New Relic, Datadog

### 3. Setup Backups

```bash
# Create backup script
sudo nano /var/www/travaux/scripts/backup.sh
```

```bash
#!/bin/bash
BACKUP_DIR="/backups"
DATE=$(date +%Y%m%d_%H%M%S)

# Backup database
mysqldump -u travaux_user -p travaux_pro > $BACKUP_DIR/db_$DATE.sql
gzip $BACKUP_DIR/db_$DATE.sql

# Backup uploads
tar -czf $BACKUP_DIR/uploads_$DATE.tar.gz /var/www/travaux/uploads

# Keep only last 7 days
find $BACKUP_DIR -name "db_*.sql.gz" -mtime +7 -delete
find $BACKUP_DIR -name "uploads_*.tar.gz" -mtime +7 -delete
```

```bash
chmod +x /var/www/travaux/scripts/backup.sh
```

### 4. Performance Optimization

```bash
# Enable OPcache
sudo nano /etc/php/8.1/apache2/conf.d/10-opcache.ini
```

```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=10000
opcache.revalidate_freq=60
```

```bash
# Restart Apache
sudo systemctl restart apache2
```

## Troubleshooting

### Database Connection Error

```bash
# Check MySQL is running
sudo systemctl status mysql

# Check credentials in .env
cat .env | grep DB_

# Test connection
mysql -u travaux_user -p -h localhost travaux_pro
```

### Permission Issues

```bash
# Fix permissions
sudo chown -R www-data:www-data /var/www/travaux
sudo chmod -R 755 /var/www/travaux
sudo chmod -R 775 /var/www/travaux/uploads
```

### API 500 Errors

```bash
# Check Apache error logs
sudo tail -f /var/log/apache2/travaux-error.log

# Check PHP errors
sudo tail -f /var/log/php8.1-fpm.log
```

### Mobile App Not Connecting

1. Check API URL in `mobile/src/services/api.js`
2. Verify SSL certificate is valid
3. Check CORS headers in Apache config
4. Test API endpoint: `curl https://your-domain.com/api/health`

## Scaling for Production

### Load Balancing

Use Nginx as reverse proxy:

```nginx
upstream travaux_backend {
    server 10.0.1.10:80;
    server 10.0.1.11:80;
    server 10.0.1.12:80;
}

server {
    listen 80;
    server_name your-domain.com;

    location / {
        proxy_pass http://travaux_backend;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
    }
}
```

### Database Replication

Setup MySQL master-slave replication for read scaling.

### CDN

Use Cloudflare or AWS CloudFront for:
- Static assets
- Image optimization
- DDoS protection
- Global caching

## Support

For deployment issues:
- **Email:** devops@travauxpro.com
- **Documentation:** https://docs.travauxpro.com
- **Community:** https://community.travauxpro.com

---

**Last Updated:** January 2024
