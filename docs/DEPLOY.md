# Guide de déploiement — Hub Import-Export 2026

Guide d'installation en production pour un serveur Ubuntu 24.04 LTS.

---

## Prérequis serveur

```bash
# PHP 8.3 + extensions
apt install php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml php8.3-curl \
            php8.3-zip php8.3-gd php8.3-intl php8.3-bcmath php8.3-redis

# MySQL 8
apt install mysql-server-8.0

# Node.js 20
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install nodejs

# Nginx
apt install nginx

# Supervisor (queue worker)
apt install supervisor

# Composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
```

---

## Déploiement initial

```bash
# 1. Cloner le dépôt
git clone <repo-url> /var/www/hub-ie
cd /var/www/hub-ie

# 2. Dépendances PHP (production)
composer install --no-dev --optimize-autoloader

# 3. Variables d'environnement
cp .env.example .env
nano .env  # remplir APP_KEY, DB_*, MAIL_*, APP_URL

# 4. Clé d'application
php artisan key:generate

# 5. Migrations
php artisan migrate --force

# 6. Assets
npm ci && npm run build

# 7. Optimisation Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Permissions
chown -R www-data:www-data /var/www/hub-ie/storage
chown -R www-data:www-data /var/www/hub-ie/bootstrap/cache
chmod -R 775 /var/www/hub-ie/storage
```

---

## Configuration Nginx

```nginx
server {
    listen 443 ssl http2;
    server_name hubimportexport.ci www.hubimportexport.ci;
    root /var/www/hub-ie/public;
    index index.php;

    ssl_certificate     /etc/letsencrypt/live/hubimportexport.ci/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/hubimportexport.ci/privkey.pem;

    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Frame-Options DENY always;
    add_header X-Content-Type-Options nosniff always;
    add_header Referrer-Policy strict-origin-when-cross-origin always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff2)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}

server {
    listen 80;
    server_name hubimportexport.ci www.hubimportexport.ci;
    return 301 https://$host$request_uri;
}
```

---

## Supervisor (queue worker)

Fichier `/etc/supervisor/conf.d/hub-ie-worker.conf` :

```ini
[program:hub-ie-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/hub-ie/artisan queue:work database --sleep=3 --tries=3 --timeout=120
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/hub-ie-worker.log
```

```bash
supervisorctl reread
supervisorctl update
supervisorctl start hub-ie-worker:*
```

---

## Cron (scheduler Laravel)

Ajouter dans crontab de `www-data` :

```
* * * * * php /var/www/hub-ie/artisan schedule:run >> /dev/null 2>&1
```

---

## Variables d'environnement de production

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://hubimportexport.ci

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hub_ie_prod
DB_USERNAME=hub_ie_user
DB_PASSWORD=<mot_de_passe_fort>

QUEUE_CONNECTION=database
SESSION_DRIVER=file
CACHE_STORE=file

MAIL_MAILER=smtp
MAIL_HOST=<smtp_host>
MAIL_PORT=587
MAIL_USERNAME=<smtp_user>
MAIL_PASSWORD=<smtp_pass>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hub-import-export@commerce.gouv.ci
MAIL_FROM_NAME="Hub Import-Export 2026"

HUB_APPLICATION_OPENS_AT=2026-03-01
HUB_APPLICATION_CLOSES_AT=2026-05-15
```

---

## Mise à jour (déploiement continu)

```bash
cd /var/www/hub-ie
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
supervisorctl restart hub-ie-worker:*
```

---

## Sauvegardes

```bash
# Sauvegarde base de données (à planifier quotidiennement)
mysqldump -u hub_ie_user -p hub_ie_prod | gzip > /backup/hub-ie-$(date +%Y%m%d).sql.gz

# Sauvegarde storage (badges, convocations, documents uploadés)
tar -czf /backup/storage-$(date +%Y%m%d).tar.gz /var/www/hub-ie/storage/app/
```

---

## Restauration

```bash
# Restaurer la base de données
gunzip -c /backup/hub-ie-YYYYMMDD.sql.gz | mysql -u hub_ie_user -p hub_ie_prod

# Restaurer le storage
tar -xzf /backup/storage-YYYYMMDD.tar.gz -C /
chown -R www-data:www-data /var/www/hub-ie/storage
```
