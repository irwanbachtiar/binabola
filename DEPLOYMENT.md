# 🚀 Deployment Checklist - BinaBola Production

## Pre-Deployment

### 1. Code & Repository
- [ ] Semua fitur sudah tested
- [ ] Code sudah di-push ke GitHub
- [ ] README.md sudah lengkap
- [ ] .gitignore sudah proper

### 2. Server Requirements
- [ ] PHP >= 8.1
- [ ] Composer installed
- [ ] MySQL/PostgreSQL installed
- [ ] Apache/Nginx with mod_rewrite
- [ ] SSL Certificate (Let's Encrypt)

## Deployment Steps

### 1. Server Setup
```bash
# Login ke server via SSH
ssh user@your-server.com

# Update sistem
sudo apt update && sudo apt upgrade -y

# Install PHP 8.1+
sudo apt install php8.1 php8.1-fpm php8.1-mysql php8.1-mbstring php8.1-xml php8.1-curl php8.1-zip

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install MySQL
sudo apt install mysql-server
```

### 2. Database Setup
```bash
# Login MySQL
sudo mysql

# Buat database & user
CREATE DATABASE binabola_production;
CREATE USER 'binabola_user'@'localhost' IDENTIFIED BY 'password_kuat_123';
GRANT ALL PRIVILEGES ON binabola_production.* TO 'binabola_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Deploy Application
```bash
# Clone repository
cd /var/www
git clone https://github.com/irwanbachtiar/binabola.git
cd binabola

# Install dependencies (production only)
composer install --optimize-autoloader --no-dev

# Setup environment
cp .env.production .env
nano .env  # Edit dengan data production

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Create upload directory
mkdir -p public/uploads/siswa
chmod -R 775 public/uploads

# Set permissions
sudo chown -R www-data:www-data /var/www/binabola
sudo chmod -R 775 storage bootstrap/cache public/uploads

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4. Web Server Configuration

#### Nginx (/etc/nginx/sites-available/binabola)
```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/binabola/public;

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
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
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
sudo ln -s /etc/nginx/sites-available/binabola /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 5. SSL Certificate (Let's Encrypt)
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

## Post-Deployment

### 1. Testing
- [ ] Akses https://yourdomain.com
- [ ] Test CRUD siswa
- [ ] Test upload foto
- [ ] Test evaluasi dengan grafik
- [ ] Test absensi
- [ ] Test dashboard

### 2. Security
- [ ] APP_DEBUG=false di .env
- [ ] APP_ENV=production
- [ ] File .env chmod 600
- [ ] Setup firewall (UFW)
- [ ] Disable directory listing
- [ ] Setup backup database

### 3. Monitoring
- [ ] Setup log monitoring
- [ ] Setup uptime monitoring
- [ ] Setup backup otomatis

## Backup & Maintenance

### Database Backup (Cron Job)
```bash
# Buat script backup
sudo nano /usr/local/bin/backup-binabola.sh
```

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u binabola_user -p'password_kuat_123' binabola_production > /var/backups/binabola_$DATE.sql
find /var/backups/binabola_*.sql -mtime +7 -delete
```

```bash
chmod +x /usr/local/bin/backup-binabola.sh

# Setup cron (backup harian jam 2 pagi)
crontab -e
# Tambahkan:
0 2 * * * /usr/local/bin/backup-binabola.sh
```

### Update Application
```bash
cd /var/www/binabola
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl reload php8.1-fpm
sudo systemctl reload nginx
```

## Troubleshooting

### 500 Internal Server Error
```bash
# Check logs
tail -f storage/logs/laravel.log
tail -f /var/log/nginx/error.log

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Permission Errors
```bash
sudo chown -R www-data:www-data /var/www/binabola
sudo chmod -R 775 storage bootstrap/cache public/uploads
```

### Database Connection Error
- Cek kredensial di .env
- Cek MySQL service: `sudo systemctl status mysql`
- Test koneksi: `php artisan tinker` → `DB::connection()->getPdo();`

## Hosting Recommendations

### Shared Hosting (Murah, Mudah)
- **Niagahoster** - Rp 50.000/bulan
- **Hostinger** - Rp 40.000/bulan
- **Dewaweb** - Rp 80.000/bulan

### VPS (Lebih Powerful)
- **DigitalOcean** - $6/bulan
- **Vultr** - $5/bulan
- **AWS Lightsail** - $5/bulan

### Managed Laravel (Termudah)
- **Laravel Forge** - $12/bulan
- **Ploi.io** - $10/bulan

## Support

Jika ada masalah deployment, buat issue di GitHub dengan detail:
- Error message
- Server specs
- Steps yang sudah dilakukan

---
**Good Luck! 🚀**
