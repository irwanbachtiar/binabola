# 📱 Panduan Install PWA BinaBola di Android

## Cara Install Aplikasi PWA di HP Android

### 📲 **Metode 1: Melalui Chrome Browser**

1. **Buka Chrome** di HP Android Anda
2. **Akses URL aplikasi**: 
   - Jika di jaringan lokal: `http://[IP-LAPTOP]:8000`
   - Jika sudah online: `https://binabola.com`

3. **Install PWA**:
   - Tap icon **3 titik** (⋮) di pojok kanan atas
   - Pilih **"Tambahkan ke layar utama"** atau **"Install app"**
   - Tap **"Install"** atau **"Tambahkan"**

4. **Selesai!** Icon BinaBola akan muncul di home screen

### 🔔 **Metode 2: Melalui Banner Install**

Ketika membuka aplikasi pertama kali, akan muncul banner:
> "Install aplikasi untuk akses lebih cepat"

- Tap tombol **"Install App"**
- Ikuti instruksi yang muncul

---

## 🔐 Akun Demo untuk Pelatih

Gunakan kredensial berikut untuk login:

```
Email: pelatih@binabola.com
Password: password
```

---

## ✨ Fitur PWA BinaBola

### ✅ **Yang Sudah Bisa**:
- 📱 Install seperti aplikasi native
- 🚀 Akses cepat tanpa browser
- 📊 Input evaluasi siswa dengan slider
- 🔍 Search siswa
- 📈 Bottom navigation yang responsive
- 🎨 UI mobile-friendly
- 💾 Auto-save form progress
- 🔒 Login dengan remember me

### 🎯 **Fitur Evaluasi**:
- Input nilai per kategori (0-100) dengan slider
- Visual indicator warna (merah/kuning/hijau)
- Pilih siswa dari daftar
- Search siswa
- Catatan tambahan per evaluasi

---

## 🌐 Cara Akses dari HP (Jaringan Lokal)

### 1. **Pastikan Laptop dan HP Terhubung WiFi yang Sama**

### 2. **Cek IP Laptop**
```powershell
ipconfig
```
Cari **IPv4 Address** (contoh: `192.168.1.100`)

### 3. **Jalankan Server Laravel**
```powershell
php artisan serve --host=0.0.0.0 --port=8000
```

### 4. **Buka di HP Android**
```
http://192.168.1.100:8000
```
(Ganti `192.168.1.100` dengan IP laptop Anda)

### 5. **Install PWA** (ikuti Metode 1 di atas)

---

## 🧪 Testing Offline Mode

1. Install PWA terlebih dahulu
2. Buka aplikasi dari home screen
3. **Matikan WiFi/Data** di HP
4. Aplikasi tetap bisa dibuka (halaman yang sudah di-cache)
5. Assets (CSS, JS, Icons) tetap load

> **Note**: Untuk submit data evaluasi, tetap perlu koneksi internet karena harus kirim ke server.

---

## 🚀 Deploy ke Production (Agar Online dari Mana Saja)

### Opsi Deployment:

1. **Hosting Laravel**:
   - Heroku
   - DigitalOcean
   - AWS
   - Railway.app

2. **Domain & SSL**:
   - Beli domain (contoh: `binabola.com`)
   - Install SSL certificate (HTTPS wajib untuk PWA)

3. **Update URL di PWA**:
   - Edit `manifest.json` → ganti `start_url`
   - Update `sw.js` → sesuaikan cache URLs

---

## 📞 Support

Jika ada masalah:
- Pastikan server Laravel running
- Clear cache browser: Settings → Apps → Chrome → Storage → Clear Cache
- Uninstall PWA lalu install ulang

---

## 🎨 Customize Icon PWA

Icon saat ini: SVG dengan bola sepak + gradient

Untuk ganti icon PNG yang lebih bagus:
1. Buat icon 512x512 dan 192x192 (PNG)
2. Simpan di `public/icons/`
3. Ganti file `icon-512.png` dan `icon-192.png`

---

**Happy Coaching! ⚽🏆**
