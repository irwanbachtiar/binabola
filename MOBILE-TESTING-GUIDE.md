# 📱 Panduan Test PWA di Mobile Device

## 🎯 Cara 1: Via IP Address (Quick Test)

### Step 1: Cek IP Laptop
IP Address Laptop Anda: **192.168.1.4**

### Step 2: Setup
1. ✅ Pastikan laptop dan HP terhubung ke **WiFi yang sama**
2. ✅ Server Laravel sudah running: `http://127.0.0.1:8000`

### Step 3: Buka di Mobile
1. **Buka browser** di HP (Chrome/Safari)
2. **Ketik URL**: `http://192.168.1.4:8000`
3. **Login** dengan akun test
4. **Test semua fitur**

### ⚠️ Limitasi
- ❌ Install prompt **TIDAK muncul** (butuh HTTPS)
- ❌ Service Worker mungkin tidak register
- ❌ Tidak bisa test "Add to Home Screen"
- ✅ Bisa test UI mobile
- ✅ Bisa test responsive design
- ✅ Bisa test functionality

---

## 🌐 Cara 2: Via Ngrok (Full PWA Test)

Ngrok membuat HTTPS tunnel ke localhost, sehingga:
- ✅ Install prompt **AKAN MUNCUL**
- ✅ Service Worker register
- ✅ Semua PWA features bekerja
- ✅ Bisa test "Add to Home Screen"

### Step 1: Install Ngrok

#### Windows (via Chocolatey)
```powershell
# Install Chocolatey dulu jika belum ada
choco install ngrok
```

#### Manual Download
1. Download dari: https://ngrok.com/download
2. Extract file `ngrok.exe`
3. Simpan di folder (contoh: `C:\tools\ngrok\`)

### Step 2: Signup Ngrok (Free)
1. Buka: https://dashboard.ngrok.com/signup
2. Signup dengan email (gratis)
3. Copy **authtoken** dari dashboard
4. Jalankan:
```powershell
ngrok config add-authtoken YOUR_AUTH_TOKEN
```

### Step 3: Start Ngrok Tunnel
```powershell
ngrok http 8000
```

Output akan seperti ini:
```
Session Status: online
Forwarding: https://abc123.ngrok.io -> http://localhost:8000
```

### Step 4: Buka di Mobile
1. **Copy URL HTTPS** dari ngrok (contoh: `https://abc123.ngrok.io`)
2. **Buka di Chrome mobile**
3. **Tunggu 5 detik** → Install banner akan muncul!
4. **Klik "Install"** → Test full PWA experience

### Step 5: Test Checklist
- [ ] URL ngrok bisa dibuka
- [ ] Halaman load dengan benar
- [ ] Install banner muncul setelah 5 detik
- [ ] Klik install → Dialog muncul
- [ ] Accept install → Icon muncul di home screen
- [ ] Buka dari home screen → Fullscreen mode
- [ ] Badge "Mode Aplikasi" muncul
- [ ] Welcome page muncul saat first launch
- [ ] Test offline mode (matikan WiFi)
- [ ] Test pull to refresh

---

## 🔍 Cara 3: Via Laravel Valet (MacOS)

Jika menggunakan Mac:

### Step 1: Install Valet
```bash
composer global require laravel/valet
valet install
```

### Step 2: Park Project
```bash
cd /path/to/binabola
valet park
valet secure binabola
```

### Step 3: Akses
URL akan jadi: `https://binabola.test`

Bisa diakses dari mobile yang sama network.

---

## 🚀 Quick Start (Recommended)

### Opsi A: Test UI Only (Tanpa Install)
```
1. Buka: http://192.168.1.4:8000
2. Test di Chrome mobile
3. Check responsive design
```

### Opsi B: Test Full PWA (Dengan Install)
```
1. Download & install ngrok
2. Jalankan: ngrok http 8000
3. Copy HTTPS URL
4. Buka di mobile
5. Install app ke home screen
```

---

## 📱 Testing Guide

### Android (Chrome)
1. Buka URL (HTTPS via ngrok)
2. Tunggu 5 detik
3. Banner install muncul di bawah
4. Tap "Install"
5. Icon muncul di home screen
6. Tap icon → App terbuka fullscreen

### iOS (Safari)
1. Buka URL (HTTPS via ngrok)
2. Tap tombol **Share** (kotak + panah)
3. Scroll → "Add to Home Screen"
4. Tap "Add"
5. Icon muncul di home screen
6. Tap icon → App terbuka fullscreen

⚠️ **Note iOS**: 
- Tidak support install prompt/banner
- Harus manual via Share menu
- Beberapa PWA features terbatas

---

## 🐛 Troubleshooting

### Problem: HP tidak bisa akses IP laptop
**Solusi**:
1. Cek keduanya di WiFi yang sama
2. Disable firewall Windows sementara
3. Coba IP lain: `ipconfig` → cari "Wireless LAN adapter"

### Problem: Ngrok error "authtoken"
**Solusi**:
1. Signup di ngrok.com
2. Copy authtoken dari dashboard
3. Run: `ngrok config add-authtoken TOKEN`

### Problem: Install banner tidak muncul
**Check**:
1. Harus HTTPS (ngrok) bukan HTTP (IP)
2. Service Worker harus registered
3. Tunggu 5-10 detik
4. Tap/scroll di halaman dulu

### Problem: iOS tidak ada banner
**Ini Normal**: iOS tidak support beforeinstallprompt
**Solusi**: Manual via Share → Add to Home Screen

---

## ✅ Current Status

**Laravel Server**: ✅ Running di `http://127.0.0.1:8000`
**Laptop IP**: `192.168.1.4`

### Next Steps:

**Quick Test (UI Only)**:
```
http://192.168.1.4:8000
```

**Full PWA Test (Install Feature)**:
1. Install ngrok: https://ngrok.com/download
2. Run: `ngrok http 8000`
3. Use HTTPS URL di mobile

---

## 📞 Need Help?

Jika ada masalah:
1. Check firewall Windows
2. Check WiFi connection
3. Check Laravel server masih running
4. Check ngrok tunnel status
5. Check browser console for errors

**Ready to test!** 🚀
