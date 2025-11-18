#!/bin/bash

# Travaux Pro - Automated Database Backup Script
# Run this script daily via cron: 0 4 * * * /var/www/travaux/scripts/backup-database.sh

set -e

# Configuration
BACKUP_DIR="/var/backups/travaux-pro"
DATE=$(date +%Y%m%d_%H%M%S)
KEEP_DAYS=30

# Load environment variables
if [ -f /var/www/travaux/.env ]; then
    source /var/www/travaux/.env
else
    echo "❌ Error: .env file not found!"
    exit 1
fi

# Create backup directory if it doesn't exist
mkdir -p $BACKUP_DIR

echo "========================================="
echo "  Travaux Pro - Database Backup"
echo "  $(date)"
echo "========================================="

# Backup database
echo "Creating database backup..."
BACKUP_FILE="$BACKUP_DIR/travaux_pro_$DATE.sql"

mysqldump -h ${DB_HOST:-localhost} \
          -u ${DB_USER} \
          -p${DB_PASSWORD} \
          ${DB_NAME} > $BACKUP_FILE

if [ $? -eq 0 ]; then
    echo "✓ Database backup created: $BACKUP_FILE"

    # Compress backup
    echo "Compressing backup..."
    gzip $BACKUP_FILE
    echo "✓ Backup compressed: ${BACKUP_FILE}.gz"

    # Calculate size
    SIZE=$(du -h "${BACKUP_FILE}.gz" | cut -f1)
    echo "✓ Backup size: $SIZE"
else
    echo "❌ Error: Database backup failed!"
    exit 1
fi

# Backup uploads directory
echo "Backing up uploads..."
UPLOADS_BACKUP="$BACKUP_DIR/uploads_$DATE.tar.gz"
tar -czf $UPLOADS_BACKUP /var/www/travaux/uploads 2>/dev/null

if [ $? -eq 0 ]; then
    UPLOADS_SIZE=$(du -h "$UPLOADS_BACKUP" | cut -f1)
    echo "✓ Uploads backup created: $UPLOADS_SIZE"
fi

# Delete old backups (keep last 30 days)
echo "Cleaning old backups (keeping last $KEEP_DAYS days)..."
find $BACKUP_DIR -name "travaux_pro_*.sql.gz" -mtime +$KEEP_DAYS -delete
find $BACKUP_DIR -name "uploads_*.tar.gz" -mtime +$KEEP_DAYS -delete
echo "✓ Old backups cleaned"

# Create backup log
echo "$DATE - Backup completed successfully" >> $BACKUP_DIR/backup.log

# Optional: Upload to cloud storage (S3, Google Cloud, etc.)
# Uncomment and configure for cloud backup
# aws s3 cp ${BACKUP_FILE}.gz s3://your-bucket/backups/

echo "========================================="
echo "  Backup completed successfully! ✓"
echo "========================================="

exit 0
