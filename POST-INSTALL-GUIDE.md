# 📱 Panduan Setelah Install - BinaBola PWA

## 🎉 Aplikasi Sudah Terinstall! Sekarang Apa?

Setelah berhasil install aplikasi BinaBola ke home screen, berikut yang bisa Anda lakukan:

---

## 📍 1. Cara Membuka Aplikasi

### Di Android
1. **Cari icon BinaBola** di home screen atau app drawer
2. **Tap icon** untuk membuka aplikasi
3. Aplikasi akan terbuka **fullscreen** tanpa address bar browser
4. Terlihat dan terasa seperti **aplikasi native**

### Di iOS (iPhone/iPad)
1. **Cari icon BinaBola** di home screen
2. **Tap icon** untuk membuka
3. Aplikasi akan terbuka fullscreen
4. Swipe dari bawah untuk exit

### Di Windows/Mac Desktop
1. **Cari BinaBola** di Start Menu (Windows) atau Applications (Mac)
2. **Klik untuk membuka**
3. Aplikasi akan terbuka di **window terpisah**
4. Icon akan muncul di **taskbar/dock**

---

## ✨ 2. Fitur-Fitur yang Bisa Digunakan

### 🚀 Akses Lebih Cepat
- ✅ Buka langsung dari home screen
- ✅ Tidak perlu buka browser dulu
- ✅ Loading lebih cepat karena ada cache
- ✅ Seperti aplikasi native

### 🔌 Offline Support
- ✅ Halaman yang sudah pernah dibuka tetap bisa diakses
- ✅ Data tersimpan di cache browser
- ✅ Saat online, data akan sync otomatis
- ⚠️ Input data baru butuh koneksi internet

### 🔄 Auto Update
- ✅ Aplikasi akan otomatis check update setiap 1 jam
- ✅ Banner notifikasi akan muncul saat ada update
- ✅ Klik "Update" untuk apply versi baru
- ✅ Tidak perlu download ulang dari Play Store/App Store

### 💾 Data Tersimpan
- ✅ Login session tersimpan
- ✅ Cache halaman tersimpan
- ✅ Preferensi user tersimpan di LocalStorage
- ✅ Tidak hilang saat close app

---

## 🎯 3. Welcome Screen (First Launch)

Saat pertama kali membuka aplikasi yang sudah diinstall, Anda akan melihat:

### Welcome Page
- 🎉 Ucapan selamat
- 📋 Penjelasan fitur-fitur
- 🚀 Quick actions
- 📖 Tutorial penggunaan

**URL**: `http://127.0.0.1:8000/welcome-pwa`

### Standalone Mode Badge
- Badge hijau "Mode Aplikasi" di pojok kanan bawah
- Muncul selama 5 detik
- Menandakan app berjalan dalam standalone mode
- Hanya muncul di installed app, tidak di browser

---

## 📋 4. Menu & Navigasi

### Untuk Admin/Pelatih:
```
Dashboard       → Ringkasan statistik
Data Siswa      → Manajemen data siswa
Penilaian       → Input evaluasi siswa
Absensi         → Input kehadiran siswa
Laporan         → Lihat laporan lengkap
Kategori        → Manage kategori penilaian
User Orangtua   → Manajemen akun orangtua
```

### Untuk Orangtua:
```
Dashboard Orangtua → Info anak-anak
Absensi Siswa      → Riwayat kehadiran
Evaluasi Siswa     → Progress penilaian
```

---

## 🔑 5. Quick Actions

### Shortcut App Launcher (Long Press Icon)
Saat long-press icon BinaBola di home screen, akan muncul shortcut:
- **Dashboard** - Buka dashboard utama
- **Absensi** - Input absensi langsung
- **Penilaian** - Input penilaian langsung

**Cara aktifkan**: 
- Android: Long-press icon → Pilih shortcut
- iOS: Tidak support app shortcuts

---

## 🌐 6. Online vs Offline Mode

### Saat Online (Ada Internet)
- ✅ Semua fitur berfungsi normal
- ✅ Data real-time dari server
- ✅ Bisa input absensi/evaluasi
- ✅ Upload foto siswa
- ✅ Sync otomatis

### Saat Offline (Tidak Ada Internet)
- ⚠️ Banner kuning "Mode Offline" akan muncul
- ✅ Bisa buka halaman yang sudah di-cache
- ✅ Bisa lihat data yang sudah pernah dibuka
- ❌ Tidak bisa input data baru
- ❌ Tidak bisa upload foto
- ❌ Data tidak sync

**Auto-detect**: App otomatis tahu status koneksi dan show banner

---

## 🔄 7. Update Aplikasi

### Automatic Update Detection
Aplikasi akan otomatis check update setiap 1 jam. Saat ada update baru:

1. **Banner hijau muncul** di bagian atas
   ```
   Update tersedia! Versi baru aplikasi siap digunakan.
   [Update]
   ```

2. **Klik tombol "Update"**
3. **Aplikasi akan reload** dengan versi baru
4. **Done!** Tidak perlu install ulang

### Manual Check Update
```javascript
// Di browser console:
navigator.serviceWorker.getRegistration().then(reg => reg.update());
```

---

## 📊 8. Monitoring App Status

### Check Service Worker
1. Buka app di browser (bukan installed app)
2. Tekan **F12** (DevTools)
3. Tab **Application** → Service Workers
4. Lihat status: "activated and is running"

### Check Cache
1. DevTools → Application → Cache Storage
2. Lihat **binabola-v6**
3. List file yang di-cache

### Check Manifest
1. DevTools → Application → Manifest
2. Verify icon, name, theme color

---

## 🗑️ 9. Uninstall Aplikasi

### Android
1. **Long-press icon** BinaBola di home screen
2. **Drag ke "Uninstall"** atau tap "App Info" → Uninstall
3. **Confirm**

### iOS
1. **Long-press icon** BinaBola
2. **Tap "Remove App"** → Delete App
3. **Confirm**

### Windows
1. **Settings** → Apps → BinaBola
2. **Uninstall**

### Mac
1. **Applications folder** → BinaBola
2. **Drag to Trash**

### Chrome Desktop (Alternative)
1. Buka `chrome://apps`
2. **Right-click** BinaBola
3. **Remove from Chrome**

---

## 💡 10. Tips & Tricks

### Maximize Performance
- ✅ Buka app dari home screen (bukan browser)
- ✅ Clear cache jika app lambat: Settings → Clear browsing data
- ✅ Pastikan selalu ada koneksi untuk sync data
- ✅ Update app saat ada notifikasi

### Troubleshooting
**App tidak muncul di home screen?**
- Check di app drawer (Android)
- Search "BinaBola" di spotlight (iOS)
- Coba install ulang

**App loading lambat?**
- Check koneksi internet
- Clear cache browser
- Restart device

**Data tidak update?**
- Pull to refresh (swipe down di top)
- Check status online/offline
- Logout dan login ulang

**Icon tidak muncul?**
- Icon cache mungkin perlu waktu
- Restart device
- Reinstall app

---

## 🎯 11. Best Practices

### Daily Usage
1. **Buka dari home screen** - Jangan dari browser
2. **Check banner update** - Update saat tersedia
3. **Sync regular** - Buka app saat online untuk sync
4. **Backup data** - Jangan hanya rely on cache

### Data Input
1. **Pastikan online** saat input absensi/evaluasi
2. **Tunggu konfirmasi** sukses sebelum close
3. **Screenshot** sebagai backup jika perlu
4. **Refresh page** untuk lihat data terbaru

### Security
1. **Logout** saat selesai (shared device)
2. **Lock device** untuk keamanan
3. **Update regular** untuk security patches
4. **Don't share credentials** dengan orang lain

---

## 📞 12. FAQ

### Q: Apakah app ini gratis?
**A**: Ya, PWA adalah web app yang diinstall, tidak ada biaya download/install.

### Q: Apakah perlu update manual?
**A**: Tidak, app auto-update. Anda hanya perlu klik "Update" saat banner muncul.

### Q: Berapa space yang dibutuhkan?
**A**: Sangat kecil (~5-10 MB) untuk cache. Jauh lebih kecil dari native app.

### Q: Apakah bisa digunakan di multiple device?
**A**: Ya, install di semua device. Login dengan akun yang sama.

### Q: Apakah offline data akan sync?
**A**: Ya, otomatis sync saat kembali online.

### Q: Bagaimana jika lupa password?
**A**: Hubungi admin untuk reset password.

### Q: Apakah ada perbedaan dengan browser version?
**A**: Tidak ada perbedaan fitur, hanya experience (fullscreen, faster, offline).

### Q: Bisa install di iOS?
**A**: Ya, tapi iOS tidak support install prompt. Harus manual via Share → Add to Home Screen.

### Q: Apakah push notification support?
**A**: Belum. Fitur ini bisa ditambahkan di future update.

---

## 🚀 Next Steps

Setelah install dan familiar dengan app:

1. **Explore semua menu** - Pahami setiap fitur
2. **Test offline mode** - Matikan internet, coba akses
3. **Setup shortcuts** - Long-press icon untuk quick access
4. **Share dengan tim** - Ajak rekan untuk install juga
5. **Provide feedback** - Beri tahu admin jika ada issue

---

## 📧 Support

Jika ada masalah atau pertanyaan:
- **Admin**: Hubungi admin sekolah
- **Tech Support**: Check [PWA-TROUBLESHOOTING.md](PWA-TROUBLESHOOTING.md)
- **Bug Report**: Lapor ke developer

---

## ✅ Checklist Post-Install

Pastikan semua ini sudah dilakukan:

- [ ] App icon muncul di home screen
- [ ] Bisa buka app dari home screen
- [ ] Welcome screen muncul saat first launch
- [ ] Standalone badge muncul (hijau pojok kanan bawah)
- [ ] Test login dan navigasi
- [ ] Test offline mode (matikan internet)
- [ ] Check update notification
- [ ] Setup app shortcuts (Android only)
- [ ] Familiar dengan semua menu
- [ ] Sudah input data test

---

**Selamat menggunakan BinaBola PWA!** ⚽📱

Nikmati pengalaman aplikasi native dengan fleksibilitas web app. Happy coaching! 🎉
