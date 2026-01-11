# Panduan Evaluasi Batch Siswa

## Fitur Baru: Penilaian Batch

Fitur ini memungkinkan pelatih untuk mengevaluasi banyak siswa sekaligus dalam satu form, berdasarkan tanggal latihan.

## Cara Menggunakan

### 1. Akses Halaman
- Buka menu **"Penilaian Batch"** di navigasi
- URL: `/evaluasi-batch`

### 2. Pilih Tanggal Latihan
- Pilih tanggal latihan yang ingin dievaluasi
- Sistem akan menampilkan minggu ke-berapa (auto-calculated)
- Klik tombol **"Tampilkan Siswa"**

### 3. Input Nilai Evaluasi
- Sistem akan menampilkan **hanya siswa yang hadir** pada tanggal tersebut
- Setiap siswa ditampilkan dalam satu baris
- Setiap kategori penilaian ditampilkan dalam kolom
- Input nilai 0-100 untuk setiap kategori per siswa

### 4. Simpan Data
- Klik tombol **"Simpan Semua Evaluasi"**
- Sistem akan menyimpan evaluasi untuk semua siswa sekaligus
- Jika evaluasi sudah ada untuk minggu tersebut, data akan di-update

## Perbedaan dengan Penilaian Regular

### Penilaian Regular (`/evaluasi`)
- **Proses**: Pilih siswa → Input nilai per kategori
- **Fokus**: Satu siswa per sesi
- **Cocok untuk**: Evaluasi mendalam per individu
- **Tampilan**: Detail lengkap dengan grafik progress

### Penilaian Batch (`/evaluasi-batch`)
- **Proses**: Pilih tanggal → Input nilai semua siswa sekaligus
- **Fokus**: Banyak siswa dalam satu form
- **Cocok untuk**: Evaluasi harian/latihan rutin
- **Tampilan**: Tabel dengan semua siswa dan kategori

## Alur Kerja yang Disarankan

1. **Sebelum Latihan**:
   - Input absensi siswa (menu Absensi)

2. **Setelah Latihan**:
   - Gunakan Penilaian Batch untuk evaluasi cepat semua siswa
   - Pilih tanggal latihan hari ini
   - Input nilai untuk semua siswa yang hadir

3. **Evaluasi Detail**:
   - Gunakan Penilaian Regular untuk siswa yang perlu perhatian khusus
   - Lihat grafik progress dan trend per siswa

## Validasi Data

- **Tanggal**: Maksimal hari ini (tidak bisa future date)
- **Minggu**: Antara 1-52
- **Nilai**: Antara 0-100 (integer)
- **Absensi**: Hanya siswa dengan status "Hadir" yang ditampilkan

## Teknologi

- **Frontend**: Bootstrap 5, JavaScript (vanilla)
- **Backend**: Laravel 10, Controller: `EvaluasiController`
- **Database**: Table `evaluasi_siswas`
- **AJAX**: Fetch API untuk load data siswa dinamis

## Routes

```php
GET  /evaluasi-batch              -> Form utama
GET  /evaluasi/batch/siswa        -> API: Get siswa by tanggal
POST /evaluasi-batch              -> Save batch evaluasi
```

## Database Schema

### Table: `evaluasi_siswas`
- `siswa_id`: Foreign key ke table siswas
- `kategori_penilaian_id`: Foreign key ke kategori_penilaians
- `minggu`: Week number (1-52)
- `tahun`: Year (e.g., 2025)
- `nilai`: Score (0-100)
- `tanggal_evaluasi`: Date of evaluation

### Logic
- Jika record sudah ada (sama siswa_id, kategori_id, minggu, tahun) → **UPDATE**
- Jika belum ada → **CREATE NEW**

## Error Handling

- Jika tidak ada siswa hadir: Tampil pesan warning
- Jika gagal load data: Alert error message
- Jika validasi gagal: Highlight input yang error
- Database error: Rollback transaction + error message

## Tips Penggunaan

1. **Pastikan absensi sudah diinput**: Tanpa data absensi, tidak ada siswa yang tampil
2. **Input nilai lengkap**: Semua input harus diisi sebelum save
3. **Gunakan minggu yang tepat**: Week number penting untuk tracking progress
4. **Double check sebelum save**: Gunakan confirmation dialog

## Update Log

- **2025-01-01**: Fitur batch evaluation dibuat
  - Form pilih tanggal
  - Dynamic load siswa berdasarkan absensi
  - Bulk save dengan transaction
  - Navigation menu ditambahkan
