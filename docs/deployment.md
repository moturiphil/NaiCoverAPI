# Deployment Guide

This guide covers deploying NaiCover API to various environments.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Development Deployment](#development-deployment)
- [Staging Deployment](#staging-deployment)
- [Production Deployment](#production-deployment)
- [Docker Deployment](#docker-deployment)
- [Cloud Deployment](#cloud-deployment)
- [Monitoring and Maintenance](#monitoring-and-maintenance)
- [Troubleshooting](#troubleshooting)

## Prerequisites

### Server Requirements

- **OS**: Ubuntu 20.04 LTS or CentOS 8+ 
- **PHP**: 8.2 or higher with extensions:
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath
  - Zip
  - GD
- **Web Server**: Nginx (recommended) or Apache
- **Database**: MySQL 8.0+ or PostgreSQL 13+
- **Process Manager**: Supervisor (for queues)
- **Memory**: 2GB minimum, 4GB+ recommended
- **Storage**: 20GB minimum

### Required Software

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP and extensions
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-pgsql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-bcmath php8.2-gd

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Install Nginx
sudo apt install nginx

# Install Supervisor
sudo apt install supervisor
```

## Development Deployment

### Quick Development Setup

```bash
# Clone repository
git clone https://github.com/moturiphil/NaiCoverAPI.git
cd NaiCoverAPI

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
touch database/database.sqlite
php artisan migrate --seed

# Passport setup
php artisan passport:install

# Start development server
php artisan serve
```

### Development with Queue Processing

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Queue worker
php artisan queue:work

# Terminal 3: Log monitoring
php artisan pail
```

## Staging Deployment

### Staging Server Setup

```bash
# Create application directory
sudo mkdir -p /var/www/naicoverapi-staging
cd /var/www/naicoverapi-staging

# Clone repository
sudo git clone https://github.com/moturiphil/NaiCoverAPI.git .

# Set permissions
sudo chown -R www-data:www-data /var/www/naicoverapi-staging
sudo chmod -R 755 /var/www/naicoverapi-staging/storage
sudo chmod -R 755 /var/www/naicoverapi-staging/bootstrap/cache

# Install dependencies
sudo -u www-data composer install --optimize-autoloader
```

### Staging Environment Configuration

```bash
# Copy and configure environment
sudo -u www-data cp .env.example .env
sudo -u www-data php artisan key:generate

# Configure .env for staging
sudo nano .env
```

**Staging .env configuration:**
```env
APP_NAME="NaiCover API - Staging"
APP_ENV=staging
APP_DEBUG=true
APP_URL=https://staging-api.naicoverapi.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=naicoverapi_staging
DB_USERNAME=naicoverapi_user
DB_PASSWORD=secure_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=mailtrap_username
MAIL_PASSWORD=mailtrap_password

QUEUE_CONNECTION=database
CACHE_STORE=database
```

### Staging Database Setup

```bash
# MySQL setup
mysql -u root -p
CREATE DATABASE naicoverapi_staging;
CREATE USER 'naicoverapi_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON naicoverapi_staging.* TO 'naicoverapi_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Run migrations
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan db:seed

# Setup Passport
sudo -u www-data php artisan passport:install
```

### Staging Nginx Configuration

```bash
sudo nano /etc/nginx/sites-available/naicoverapi-staging
```

```nginx
server {
    listen 80;
    server_name staging-api.naicoverapi.com;
    root /var/www/naicoverapi-staging/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/naicoverapi-staging /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## Production Deployment

### Production Server Hardening

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Configure firewall
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw --force enable

# Disable root login
sudo passwd -l root

# Create deployment user
sudo adduser deploy
sudo usermod -aG sudo deploy
sudo usermod -aG www-data deploy
```

### Production Application Setup

```bash
# Create application directory
sudo mkdir -p /var/www/naicoverapi
cd /var/www/naicoverapi

# Clone repository
sudo git clone https://github.com/moturiphil/NaiCoverAPI.git .

# Install dependencies (production)
sudo -u www-data composer install --optimize-autoloader --no-dev --no-interaction

# Set strict permissions
sudo chown -R www-data:www-data /var/www/naicoverapi
sudo find /var/www/naicoverapi -type f -exec chmod 644 {} \;
sudo find /var/www/naicoverapi -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/naicoverapi/storage
sudo chmod -R 775 /var/www/naicoverapi/bootstrap/cache
```

### Production Environment Configuration

**Production .env configuration:**
```env
APP_NAME="NaiCover API"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.naicoverapi.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=naicoverapi_production
DB_USERNAME=naicoverapi_prod
DB_PASSWORD=very_secure_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=sendgrid_api_key
MAIL_FROM_ADDRESS="noreply@naicoverapi.com"

QUEUE_CONNECTION=redis
CACHE_STORE=redis
SESSION_DRIVER=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=redis_password
REDIS_PORT=6379

PASSPORT_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----..."
PASSPORT_PUBLIC_KEY="-----BEGIN PUBLIC KEY-----..."
```

### Production Database Setup

```bash
# MySQL production setup
mysql -u root -p
CREATE DATABASE naicoverapi_production;
CREATE USER 'naicoverapi_prod'@'localhost' IDENTIFIED BY 'very_secure_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON naicoverapi_production.* TO 'naicoverapi_prod'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Run migrations
sudo -u www-data php artisan migrate --force

# Cache optimization
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# Setup Passport
sudo -u www-data php artisan passport:install
```

### Production Nginx Configuration

```bash
sudo nano /etc/nginx/sites-available/naicoverapi-production
```

```nginx
server {
    listen 80;
    server_name api.naicoverapi.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name api.naicoverapi.com;
    root /var/www/naicoverapi/public;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/api.naicoverapi.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/api.naicoverapi.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384;

    # Security Headers
    add_header X-Frame-Options "DENY";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains";

    # Rate Limiting
    limit_req_zone $binary_remote_addr zone=api:10m rate=30r/m;
    limit_req zone=api burst=5 nodelay;

    index index.php;
    charset utf-8;

    # Gzip Compression
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # API Documentation
    location /docs {
        try_files $uri $uri/ =404;
    }
}
```

### SSL Certificate Setup

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Get SSL certificate
sudo certbot --nginx -d api.naicoverapi.com

# Test renewal
sudo certbot renew --dry-run
```

### Queue Worker Setup

```bash
sudo nano /etc/supervisor/conf.d/naicoverapi-worker.conf
```

```ini
[program:naicoverapi-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/naicoverapi/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/naicoverapi/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
# Update supervisor configuration
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start naicoverapi-worker:*
```

### Production Monitoring

```bash
# Install monitoring tools
sudo apt install htop iotop nethogs

# Setup log rotation
sudo nano /etc/logrotate.d/naicoverapi
```

```
/var/www/naicoverapi/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 0644 www-data www-data
    postrotate
        php /var/www/naicoverapi/artisan queue:restart
    endscript
}
```

## Docker Deployment

### Dockerfile

```dockerfile
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libonig-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application
COPY . /var/www

# Install dependencies
RUN composer install --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage

EXPOSE 9000
CMD ["php-fpm"]
```

### docker-compose.yml

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    image: naicoverapi
    container_name: naicoverapi_app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
    networks:
      - naicoverapi

  webserver:
    image: nginx:alpine
    container_name: naicoverapi_webserver
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www
      - ./docker/nginx:/etc/nginx/conf.d
    networks:
      - naicoverapi

  database:
    image: mysql:8.0
    container_name: naicoverapi_db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: naicoverapi
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_PASSWORD: user_password
      MYSQL_USER: naicoverapi_user
    volumes:
      - dbdata:/var/lib/mysql
    ports:
      - "3306:3306"
    networks:
      - naicoverapi

  redis:
    image: redis:alpine
    container_name: naicoverapi_redis
    restart: unless-stopped
    networks:
      - naicoverapi

  queue:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: naicoverapi_queue
    restart: unless-stopped
    command: php artisan queue:work --sleep=3 --tries=3
    volumes:
      - ./:/var/www
    depends_on:
      - database
      - redis
    networks:
      - naicoverapi

networks:
  naicoverapi:
    driver: bridge

volumes:
  dbdata:
    driver: local
```

### Docker Deployment Commands

```bash
# Build and start containers
docker-compose up -d --build

# Run migrations
docker-compose exec app php artisan migrate --force

# Setup Passport
docker-compose exec app php artisan passport:install

# View logs
docker-compose logs -f app
```

## Cloud Deployment

### AWS Deployment

#### EC2 Instance Setup

```bash
# Launch EC2 instance (t3.medium recommended)
# Configure security groups (80, 443, 22)
# Associate Elastic IP

# Connect to instance
ssh -i your-key.pem ubuntu@your-elastic-ip

# Run production setup scripts
```

#### RDS Database Setup

```bash
# Create RDS MySQL instance
aws rds create-db-instance \
  --db-instance-identifier naicoverapi-prod \
  --db-instance-class db.t3.micro \
  --engine mysql \
  --master-username admin \
  --master-user-password your-secure-password \
  --allocated-storage 20 \
  --storage-type gp2 \
  --vpc-security-group-ids sg-xxxxxx
```

#### ElastiCache Redis Setup

```bash
# Create Redis cluster
aws elasticache create-cache-cluster \
  --cache-cluster-id naicoverapi-redis \
  --cache-node-type cache.t3.micro \
  --engine redis \
  --num-cache-nodes 1
```

### DigitalOcean App Platform

Create `app.yaml`:

```yaml
name: naicoverapi
services:
- name: web
  source_dir: /
  github:
    repo: moturiphil/NaiCoverAPI
    branch: main
    deploy_on_push: true
  run_command: |
    cp .env.example .env
    composer install --optimize-autoloader --no-dev
    php artisan key:generate
    php artisan migrate --force
    php artisan config:cache
    php artisan serve --host=0.0.0.0 --port=$PORT
  environment_slug: php
  instance_count: 1
  instance_size_slug: basic-xxs
  env:
  - key: APP_ENV
    value: "production"
  - key: APP_DEBUG
    value: "false"

databases:
- name: naicoverapi-db
  engine: MYSQL
  version: "8"

workers:
- name: queue-worker
  source_dir: /
  run_command: php artisan queue:work --sleep=3 --tries=3
  instance_count: 1
  instance_size_slug: basic-xxs
```

## Monitoring and Maintenance

### Application Monitoring

```bash
# Install Laravel Telescope for debugging
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate

# Install Laravel Horizon for queue monitoring
composer require laravel/horizon
php artisan horizon:install
```

### Server Monitoring

```bash
# Install monitoring stack
sudo apt install prometheus node-exporter grafana

# Setup alerts
sudo nano /etc/prometheus/alert.rules.yml
```

### Log Management

```bash
# Centralized logging with ELK Stack
docker run -d --name elasticsearch elasticsearch:7.17.0
docker run -d --name kibana --link elasticsearch:elasticsearch -p 5601:5601 kibana:7.17.0

# Configure Laravel to send logs to Elasticsearch
# In config/logging.php
```

### Backup Strategy

```bash
# Database backup script
#!/bin/bash
BACKUP_DIR="/backups"
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u username -p password naicoverapi_production > "$BACKUP_DIR/naicoverapi_$DATE.sql"
gzip "$BACKUP_DIR/naicoverapi_$DATE.sql"

# Setup cron for automated backups
0 2 * * * /path/to/backup-script.sh
```

### Health Checks

```bash
# Application health check endpoint
# In routes/web.php
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected',
        'cache' => Cache::has('health-check') ? 'working' : 'not working',
        'timestamp' => now()
    ]);
});
```

## Troubleshooting

### Common Issues

#### 500 Internal Server Error
```bash
# Check Laravel logs
tail -f /var/www/naicoverapi/storage/logs/laravel.log

# Check PHP-FPM logs
tail -f /var/log/php8.2-fpm.log

# Check Nginx error logs
tail -f /var/log/nginx/error.log
```

#### Permission Issues
```bash
# Fix permissions
sudo chown -R www-data:www-data /var/www/naicoverapi
sudo chmod -R 755 /var/www/naicoverapi/storage
sudo chmod -R 755 /var/www/naicoverapi/bootstrap/cache
```

#### Queue Not Processing
```bash
# Check queue worker status
sudo supervisorctl status naicoverapi-worker:*

# Restart queue workers
sudo supervisorctl restart naicoverapi-worker:*
php artisan queue:restart
```

#### Database Connection Issues
```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Check MySQL status
sudo systemctl status mysql
```

#### SSL Certificate Issues
```bash
# Test SSL certificate
openssl x509 -in /etc/letsencrypt/live/api.naicoverapi.com/fullchain.pem -text -noout

# Renew certificate
sudo certbot renew --force-renewal
```

### Performance Optimization

```bash
# Enable OPCache
sudo nano /etc/php/8.2/fpm/conf.d/10-opcache.ini

# Optimize PHP-FPM
sudo nano /etc/php/8.2/fpm/pool.d/www.conf

# Database query optimization
php artisan db:monitor
```

### Zero-Downtime Deployment

```bash
#!/bin/bash
# deployment script
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
sudo supervisorctl restart naicoverapi-worker:*
```

For more deployment help, consult the [Laravel deployment documentation](https://laravel.com/docs/deployment) or contact the development team.