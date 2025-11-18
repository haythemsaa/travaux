#!/bin/bash

# Travaux Pro - System Monitoring Script
# Run every 5 minutes: */5 * * * * /var/www/travaux/scripts/monitor-system.sh

set -e

# Configuration
ALERT_EMAIL="admin@travauxpro.com"
LOG_FILE="/var/log/travaux-monitor.log"
ALERT_THRESHOLD_CPU=80
ALERT_THRESHOLD_MEMORY=85
ALERT_THRESHOLD_DISK=90

# Colors
RED='\033[0;31m'
YELLOW='\033[1;33m'
GREEN='\033[0;32m'
NC='\033[0m' # No Color

# Logging function
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a $LOG_FILE
}

# Alert function
alert() {
    local subject=$1
    local message=$2
    echo "$message" | mail -s "ALERT: $subject" $ALERT_EMAIL
    log "ALERT: $subject - $message"
}

log "========================================="
log "Starting system monitoring..."

# 1. Check CPU Usage
log "Checking CPU usage..."
CPU_USAGE=$(top -bn1 | grep "Cpu(s)" | sed "s/.*, *\([0-9.]*\)%* id.*/\1/" | awk '{print 100 - $1}')
CPU_USAGE_INT=${CPU_USAGE%.*}

if [ "$CPU_USAGE_INT" -gt "$ALERT_THRESHOLD_CPU" ]; then
    log "${RED}WARNING: CPU usage is ${CPU_USAGE}%${NC}"
    alert "High CPU Usage" "CPU usage is ${CPU_USAGE}% (threshold: ${ALERT_THRESHOLD_CPU}%)"
else
    log "${GREEN}CPU usage: ${CPU_USAGE}%${NC}"
fi

# 2. Check Memory Usage
log "Checking memory usage..."
MEMORY_USAGE=$(free | grep Mem | awk '{print ($3/$2) * 100.0}')
MEMORY_USAGE_INT=${MEMORY_USAGE%.*}

if [ "$MEMORY_USAGE_INT" -gt "$ALERT_THRESHOLD_MEMORY" ]; then
    log "${RED}WARNING: Memory usage is ${MEMORY_USAGE}%${NC}"
    alert "High Memory Usage" "Memory usage is ${MEMORY_USAGE}% (threshold: ${ALERT_THRESHOLD_MEMORY}%)"
else
    log "${GREEN}Memory usage: ${MEMORY_USAGE}%${NC}"
fi

# 3. Check Disk Usage
log "Checking disk usage..."
DISK_USAGE=$(df -h / | awk 'NR==2 {print $5}' | sed 's/%//')

if [ "$DISK_USAGE" -gt "$ALERT_THRESHOLD_DISK" ]; then
    log "${RED}WARNING: Disk usage is ${DISK_USAGE}%${NC}"
    alert "High Disk Usage" "Disk usage is ${DISK_USAGE}% (threshold: ${ALERT_THRESHOLD_DISK}%)"
else
    log "${GREEN}Disk usage: ${DISK_USAGE}%${NC}"
fi

# 4. Check Web Server
log "Checking web server..."
if systemctl is-active --quiet apache2 || systemctl is-active --quiet nginx; then
    log "${GREEN}Web server is running${NC}"
else
    log "${RED}WARNING: Web server is not running${NC}"
    alert "Web Server Down" "Web server (Apache/Nginx) is not running"
fi

# 5. Check MySQL
log "Checking MySQL..."
if systemctl is-active --quiet mysql; then
    log "${GREEN}MySQL is running${NC}"
else
    log "${RED}WARNING: MySQL is not running${NC}"
    alert "MySQL Down" "MySQL service is not running"
fi

# 6. Check PHP-FPM
log "Checking PHP-FPM..."
if systemctl is-active --quiet php8.1-fpm; then
    log "${GREEN}PHP-FPM is running${NC}"
else
    log "${YELLOW}WARNING: PHP-FPM is not running${NC}"
fi

# 7. Check Application Health
log "Checking application health..."
HEALTH_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/health.php)

if [ "$HEALTH_RESPONSE" -eq 200 ]; then
    log "${GREEN}Application health check passed (200)${NC}"
else
    log "${RED}WARNING: Application health check failed ($HEALTH_RESPONSE)${NC}"
    alert "Application Health Check Failed" "Health endpoint returned HTTP $HEALTH_RESPONSE"
fi

# 8. Check Database Connection
log "Checking database connection..."
if mysql -u root -e "SELECT 1" > /dev/null 2>&1; then
    log "${GREEN}Database connection OK${NC}"
else
    log "${RED}WARNING: Database connection failed${NC}"
    alert "Database Connection Failed" "Cannot connect to MySQL database"
fi

# 9. Check SSL Certificate Expiry
log "Checking SSL certificate..."
if [ -f /etc/letsencrypt/live/travauxpro.com/cert.pem ]; then
    CERT_EXPIRY=$(openssl x509 -enddate -noout -in /etc/letsencrypt/live/travauxpro.com/cert.pem | cut -d= -f2)
    CERT_EXPIRY_EPOCH=$(date -d "$CERT_EXPIRY" +%s)
    NOW_EPOCH=$(date +%s)
    DAYS_UNTIL_EXPIRY=$(( ($CERT_EXPIRY_EPOCH - $NOW_EPOCH) / 86400 ))

    if [ "$DAYS_UNTIL_EXPIRY" -lt 30 ]; then
        log "${YELLOW}WARNING: SSL certificate expires in $DAYS_UNTIL_EXPIRY days${NC}"
        alert "SSL Certificate Expiring Soon" "Certificate expires in $DAYS_UNTIL_EXPIRY days"
    else
        log "${GREEN}SSL certificate valid for $DAYS_UNTIL_EXPIRY days${NC}"
    fi
fi

# 10. Check Application Error Logs
log "Checking error logs..."
ERROR_COUNT=$(grep -c "ERROR" /var/log/travaux/*.log 2>/dev/null || echo 0)
if [ "$ERROR_COUNT" -gt 100 ]; then
    log "${YELLOW}WARNING: $ERROR_COUNT errors in logs${NC}"
fi

# Summary
log "========================================="
log "Monitoring completed"
log "========================================="

exit 0
