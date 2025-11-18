#!/bin/bash

# Travaux Pro - Deployment Script
# This script automates the deployment process

set -e

echo "========================================="
echo "   Travaux Pro - Deployment Script"
echo "========================================="
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "❌ Error: .env file not found!"
    echo "Please copy .env.example to .env and configure it"
    exit 1
fi

# Load environment variables
source .env

echo "✓ Environment variables loaded"

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Error: Docker is not installed!"
    echo "Please install Docker first: https://docs.docker.com/get-docker/"
    exit 1
fi

echo "✓ Docker found"

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Error: Docker Compose is not installed!"
    echo "Please install Docker Compose: https://docs.docker.com/compose/install/"
    exit 1
fi

echo "✓ Docker Compose found"

# Stop existing containers
echo ""
echo "Stopping existing containers..."
docker-compose down

# Build and start containers
echo ""
echo "Building and starting containers..."
docker-compose up -d --build

# Wait for database to be ready
echo ""
echo "Waiting for database to be ready..."
sleep 10

# Run database migrations
echo ""
echo "Running database migrations..."
docker-compose exec -T db mysql -u${DB_USER} -p${DB_PASSWORD} ${DB_NAME} < database/schema.sql
docker-compose exec -T db mysql -u${DB_USER} -p${DB_PASSWORD} ${DB_NAME} < database/advanced_features.sql
docker-compose exec -T db mysql -u${DB_USER} -p${DB_PASSWORD} ${DB_NAME} < database/internationalization.sql
docker-compose exec -T db mysql -u${DB_USER} -p${DB_PASSWORD} ${DB_NAME} < database/custom_form_examples.sql
docker-compose exec -T db mysql -u${DB_USER} -p${DB_PASSWORD} ${DB_NAME} < database/payments.sql

echo "✓ Database migrations completed"

# Set permissions
echo ""
echo "Setting permissions..."
docker-compose exec web chown -R www-data:www-data /var/www/html
docker-compose exec web chmod -R 755 /var/www/html
docker-compose exec web chmod -R 775 /var/www/html/uploads
docker-compose exec web chmod -R 775 /var/www/html/storage

echo "✓ Permissions set"

# Show container status
echo ""
echo "========================================="
echo "Deployment completed successfully! 🎉"
echo "========================================="
echo ""
docker-compose ps
echo ""
echo "Application is running at: http://localhost"
echo "PHPMyAdmin (dev): http://localhost:8080"
echo ""
echo "To view logs: docker-compose logs -f"
echo "To stop: docker-compose down"
echo ""
