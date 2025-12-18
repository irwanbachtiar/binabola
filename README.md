# Aplikasi Manajemen Sekolah Sepak Bola - BinaBola ⚽

Sistem manajemen lengkap untuk sekolah sepak bola yang mencakup data siswa, evaluasi/penilaian, absensi, dan dashboard analytics.

## 🎯 Fitur Utama

- **Manajemen Data Siswa** - CRUD lengkap dengan foto, multi-select posisi
- **Evaluasi & Penilaian** - Input nilai mingguan dengan grafik line & radar chart
- **Absensi Siswa** - Input harian, riwayat, dan statistik kehadiran
- **Dashboard Analytics** - Statistik real-time dan grafik tren

## 🛠️ Tech Stack

- Laravel 11.x, Bootstrap 5, Chart.js
- Database: SQLite (dev), MySQL/PostgreSQL (production)

## 📋 Quick Start

```bash
git clone https://github.com/irwanbachtiar/binabola.git
cd binabola
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## 🚀 Production Setup

### 1. Environment (.env)
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=binabola_db
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

### 2. Deploy Commands
```bash
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
chmod -R 775 storage bootstrap/cache public/uploads
```

### 3. Web Server
Point document root ke `/path/to/binabola/public`

## 👥 Team Collaboration

```bash
# Clone project
git clone https://github.com/irwanbachtiar/binabola.git

# Setup
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate

# Workflow
git checkout -b fitur-baru
# ... coding ...
git add .
git commit -m "Tambah fitur X"
git push origin fitur-baru
# Buat Pull Request di GitHub
```

## 📁 Database Schema

- `siswas` - Data siswa
- `evaluasi_siswas` - Penilaian mingguan
- `kategori_penilaians` - Kategori penilaian
- `absensis` - Data kehadiran

## 📞 Support

Buat issue di GitHub untuk pertanyaan/bug.

---
**BinaBola © 2025**
